<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\UsdaFoodService;

class MealController extends Controller
{
    protected UsdaFoodService $foodService;

    public function __construct(UsdaFoodService $foodService)
    {
        $this->foodService = $foodService;
    }

    /**
     * Main meal logger page
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $date = $request->get('date', now()->format('Y-m-d'));

        // Get daily stats using Query Builder
        $dailyStats = $this->getDailyStats($userId, $date);

        // Get meal logs grouped by meal type
        $mealLogs = DB::table('meal_logs')
            ->where('user_id', $userId)
            ->where('date', $date)
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('meal_type');

        // Get user goals
        $user = DB::table('users')->where('id', $userId)->first();

        return view('meals.index', compact('dailyStats', 'mealLogs', 'user', 'date'));
    }

    /**
     * Show individual meal details
     */
    public function showMeal(Request $request, $mealType, $date = null)
    {
        $userId = Auth::id();
        $date = $date ?? now()->format('Y-m-d');

        // Validate meal type
        $validMealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
        if (!in_array($mealType, $validMealTypes)) {
            abort(404);
        }

        // Get meal logs for this specific meal type and date
        $mealLogs = DB::table('meal_logs')
            ->where('user_id', $userId)
            ->where('meal_type', $mealType)
            ->where('date', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate meal total
        $mealTotal = $mealLogs->sum('total_calories');

        // Get meal info for display
        $mealInfo = [
            'breakfast' => ['label' => 'Breakfast', 'icon' => '🌅'],
            'lunch' => ['label' => 'Lunch', 'icon' => '☀️'],
            'dinner' => ['label' => 'Dinner', 'icon' => '🌙'],
            'snacks' => ['label' => 'Snacks', 'icon' => '🍎']
        ][$mealType] ?? ['label' => ucfirst($mealType), 'icon' => '🍽️'];

        return view('meals.show', compact('mealType', 'mealLogs', 'mealTotal', 'mealInfo', 'date'));
    }

    /**
     * Search foods (check cache first, then API)
     */
    public function searchFoods(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['foods' => [], 'totalHits' => 0]);
        }
        
        // First, check cached foods
        $cachedFoods = $this->foodService->searchCachedFoods($query, 10);
        
        // Also search USDA API
        $apiResults = $this->foodService->searchFoods($query, 1, 20);
        $apiFoods = $apiResults['foods'] ?? [];
        
        // Combine and format results
        $results = [];
        $cachedFdcIds = collect($cachedFoods)->pluck('fdc_id')->toArray();
        
        // Add cached foods first
        foreach ($cachedFoods as $food) {
            $results[] = [
                'fdc_id' => $food->fdc_id,
                'name' => $food->name,
                'calories' => $food->calories_per_serving,
                'protein' => $food->protein_per_serving,
                'cached' => true,
            ];
        }
        
        // Add API results (if not already in cache)
        foreach ($apiFoods as $apiFood) {
            $fdcId = $apiFood['fdcId'];
            
            if (!in_array($fdcId, $cachedFdcIds)) {
                $results[] = [
                    'fdc_id' => $fdcId,
                    'name' => $apiFood['description'] ?? 'Unknown',
                    'calories' => $this->foodService->extractNutrient($apiFood, 1008) ?? $this->foodService->extractNutrient($apiFood, 208) ?? 0,
                    'cached' => false,
                ];
            }
        }
        
        return response()->json([
            'foods' => $results,
            'totalHits' => $apiResults['totalHits'] ?? count($results),
        ]);
    }

    /**
     * Get food details (check cache, fetch from API if needed)
     */
    public function getFoodDetails($fdcId)
    {
        // Check cache first
        $cached = $this->foodService->getCachedFood((int)$fdcId);
        
        if ($cached) {
            // Decode portions if stored as JSON
            $portions = null;
            if (isset($cached->portions)) {
                if (is_string($cached->portions)) {
                    $portions = json_decode($cached->portions, true);
                } else {
                    $portions = $cached->portions;
                }
            }
            
            // If no portions, generate defaults
            if (empty($portions)) {
                $portions = $this->foodService->generateDefaultPortions($cached->name ?? '');
            }
            
            $cached->portions = $portions;
            
            return response()->json([
                'success' => true,
                'food' => $cached,
                'cached' => true,
            ]);
        }
        
        // Fetch from API
        $apiFood = $this->foodService->getFoodDetails((int)$fdcId);
        
        if (!$apiFood) {
            return response()->json([
                'success' => false,
                'message' => 'Food not found',
            ], 404);
        }
        
        // Cache it
        $food = $this->foodService->cacheFood($apiFood);
        
        // Decode portions for response
        $portions = null;
        if (isset($food->portions)) {
            if (is_string($food->portions)) {
                $portions = json_decode($food->portions, true);
            } else {
                $portions = $food->portions;
            }
        }
        
        // If no portions, generate defaults
        if (empty($portions)) {
            $portions = $this->foodService->generateDefaultPortions($food->name ?? '');
        }
        
        $food->portions = $portions;
        
        return response()->json([
            'success' => true,
            'food' => $food,
            'cached' => false,
        ]);
    }

    /**
     * Log a food to a meal
     */
    public function logMeal(Request $request)
    {
        $userId = Auth::id();
        
        $validated = $request->validate([
            'fdc_id' => 'required_without:food_id|integer',
            'food_id' => 'required_without:fdc_id|integer',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snacks',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0.01',
            'serving_size' => 'nullable|string',
        ]);
        
        // Get food data
        $food = null;
        $fdcId = $validated['fdc_id'] ?? null;
        
        if (isset($validated['food_id'])) {
            $food = DB::table('foods')->where('id', $validated['food_id'])->first();
            if ($food) {
                $fdcId = $food->fdc_id;
            }
        } elseif ($fdcId) {
            // Check cache, fetch from API if needed
            $food = $this->foodService->getCachedFood($fdcId);
            
            if (!$food) {
                $apiFood = $this->foodService->getFoodDetails($fdcId);
                if ($apiFood) {
                    $food = $this->foodService->cacheFood($apiFood);
                }
            }
        }
        
        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food not found',
            ], 404);
        }
        
        // Calculate nutrition totals
        $quantity = $validated['quantity'];
        $totalCalories = $food->calories_per_serving * $quantity;
        $totalProtein = $food->protein_per_serving * $quantity;
        $totalCarbs = $food->carbs_per_serving * $quantity;
        $totalFats = $food->fats_per_serving * $quantity;
        
        // Insert meal log
        $mealLogId = DB::table('meal_logs')->insertGetId([
            'user_id' => $userId,
            'food_id' => $food->id ?? null,
            'food_name' => $food->name,
            'fdc_id' => $fdcId,
            'meal_type' => $validated['meal_type'],
            'date' => $validated['date'],
            'quantity' => $quantity,
            'serving_size' => $validated['serving_size'] ?? $food->serving_size,
            'serving_weight_grams' => $food->serving_weight_grams ?? 100,
            'total_calories' => $totalCalories,
            'total_protein' => $totalProtein,
            'total_carbs' => $totalCarbs,
            'total_fats' => $totalFats,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Increment food usage counter
        if (isset($food->id)) {
            DB::table('foods')
                ->where('id', $food->id)
                ->increment('times_logged');
        }
        
        // Get updated daily stats
        $dailyStats = $this->getDailyStats($userId, $validated['date']);
        
        return response()->json([
            'success' => true,
            'message' => 'Food logged successfully',
            'meal_log_id' => $mealLogId,
            'daily_stats' => $dailyStats,
        ]);
    }

    /**
     * Add custom food (manual entry)
     */
    public function addCustomFood(Request $request)
    {
        $userId = Auth::id();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serving_size' => 'required|string',
            'serving_weight_grams' => 'nullable|numeric',
            'calories_per_serving' => 'required|numeric|min:0',
            'protein_per_serving' => 'required|numeric|min:0',
            'carbs_per_serving' => 'required|numeric|min:0',
            'fats_per_serving' => 'required|numeric|min:0',
        ]);
        
        // Get next FDC ID for custom foods (use negative numbers to avoid conflicts)
        $maxCustomFdcId = DB::table('foods')
            ->where('is_custom', true)
            ->where('fdc_id', '<', 0)
            ->min('fdc_id');
        
        $fdcId = ($maxCustomFdcId ?? 0) - 1;
        
        $foodId = DB::table('foods')->insertGetId([
            'fdc_id' => $fdcId,
            'name' => $validated['name'],
            'serving_size' => $validated['serving_size'],
            'serving_weight_grams' => $validated['serving_weight_grams'] ?? 100,
            'calories_per_serving' => $validated['calories_per_serving'],
            'protein_per_serving' => $validated['protein_per_serving'],
            'carbs_per_serving' => $validated['carbs_per_serving'],
            'fats_per_serving' => $validated['fats_per_serving'],
            'api_source' => 'custom',
            'is_custom' => true,
            'created_by_user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $food = DB::table('foods')->where('id', $foodId)->first();
        
        return response()->json([
            'success' => true,
            'message' => 'Custom food created successfully',
            'food' => $food,
        ]);
    }

    /**
     * Get daily nutrition stats (using Query Builder)
     */
    public function getDailyStats($userId = null, $date = null)
    {
        $userId = $userId ?? Auth::id();
        $date = $date ?? now()->format('Y-m-d');
        
        $stats = DB::table('meal_logs')
            ->where('user_id', $userId)
            ->where('date', $date)
            ->selectRaw('
                COALESCE(SUM(total_calories), 0) as total_calories,
                COALESCE(SUM(total_protein), 0) as total_protein,
                COALESCE(SUM(total_carbs), 0) as total_carbs,
                COALESCE(SUM(total_fats), 0) as total_fats
            ')
            ->first();
        
        // Get user goals
        $user = DB::table('users')->where('id', $userId)->first();
        
        return [
            'consumed' => [
                'calories' => (float) ($stats->total_calories ?? 0),
                'protein' => (float) ($stats->total_protein ?? 0),
                'carbs' => (float) ($stats->total_carbs ?? 0),
                'fats' => (float) ($stats->total_fats ?? 0),
            ],
            'goals' => [
                'calories' => (float) ($user->goal_calories ?? 2000),
                'protein' => (float) ($user->goal_protein ?? 0),
                'carbs' => (float) ($user->goal_carbs ?? 0),
                'fats' => (float) ($user->goal_fat ?? 0),
            ],
            'remaining' => [
                'calories' => (float) (($user->goal_calories ?? 2000) - ($stats->total_calories ?? 0)),
                'protein' => (float) (($user->goal_protein ?? 0) - ($stats->total_protein ?? 0)),
                'carbs' => (float) (($user->goal_carbs ?? 0) - ($stats->total_carbs ?? 0)),
                'fats' => (float) (($user->goal_fat ?? 0) - ($stats->total_fats ?? 0)),
            ],
        ];
    }

    /**
     * Delete meal log entry
     */
    public function deleteMealLog($id)
    {
        $userId = Auth::id();
        
        $mealLog = DB::table('meal_logs')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
        
        if (!$mealLog) {
            return response()->json([
                'success' => false,
                'message' => 'Meal log not found',
            ], 404);
        }
        
        DB::table('meal_logs')->where('id', $id)->delete();
        
        // Get updated stats
        $dailyStats = $this->getDailyStats($userId, $mealLog->date);
        
        return response()->json([
            'success' => true,
            'message' => 'Meal log deleted successfully',
            'daily_stats' => $dailyStats,
        ]);
    }

    /**
     * Update meal log entry
     */
    public function updateMealLog(Request $request, $id)
    {
        $userId = Auth::id();
        
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
        ]);
        
        $mealLog = DB::table('meal_logs')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
        
        if (!$mealLog) {
            return response()->json([
                'success' => false,
                'message' => 'Meal log not found',
            ], 404);
        }
        
        // Get food data to recalculate
        $food = null;
        if ($mealLog->food_id) {
            $food = DB::table('foods')->where('id', $mealLog->food_id)->first();
        }
        
        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food data not found',
            ], 404);
        }
        
        // Recalculate totals
        $quantity = $validated['quantity'];
        $totalCalories = $food->calories_per_serving * $quantity;
        $totalProtein = $food->protein_per_serving * $quantity;
        $totalCarbs = $food->carbs_per_serving * $quantity;
        $totalFats = $food->fats_per_serving * $quantity;
        
        // Update meal log
        DB::table('meal_logs')
            ->where('id', $id)
            ->update([
                'quantity' => $quantity,
                'total_calories' => $totalCalories,
                'total_protein' => $totalProtein,
                'total_carbs' => $totalCarbs,
                'total_fats' => $totalFats,
                'updated_at' => now(),
            ]);
        
        // Get updated stats
        $dailyStats = $this->getDailyStats($userId, $mealLog->date);
        
        return response()->json([
            'success' => true,
            'message' => 'Meal log updated successfully',
            'daily_stats' => $dailyStats,
        ]);
    }

    /**
     * Meal report page with detailed statistics and charts
     */
    public function report(Request $request, $date = null)
    {
        $userId = Auth::id();
        $date = $date ?? $request->get('date', now()->format('Y-m-d'));
        
        // Validate date format
        try {
            $dateObj = \Carbon\Carbon::parse($date);
            $date = $dateObj->format('Y-m-d');
        } catch (\Exception $e) {
            $date = now()->format('Y-m-d');
        }
        
        // Get daily stats
        $dailyStats = $this->getDailyStats($userId, $date);
        
        // Get meal logs grouped by meal type
        $mealLogs = DB::table('meal_logs')
            ->where('user_id', $userId)
            ->where('date', $date)
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('meal_type');
        
        // Get user goals
        $user = DB::table('users')->where('id', $userId)->first();
        
        // Calculate meal type statistics
        $mealStats = [];
        $mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];
        
        foreach ($mealTypes as $mealType) {
            $logs = $mealLogs[$mealType] ?? collect();
            $mealStats[$mealType] = [
                'calories' => $logs->sum('total_calories'),
                'protein' => $logs->sum('total_protein'),
                'carbs' => $logs->sum('total_carbs'),
                'fats' => $logs->sum('total_fats'),
                'food_count' => $logs->count(),
                'foods' => $logs->map(function($log) {
                    return [
                        'name' => $log->food_name,
                        'quantity' => $log->quantity,
                        'serving_size' => $log->serving_size,
                        'calories' => $log->total_calories,
                        'protein' => $log->total_protein,
                    ];
                })->toArray(),
            ];
        }
        
        // Calculate percentages
        $caloriesPercent = $dailyStats['goals']['calories'] > 0 
            ? min(100, ($dailyStats['consumed']['calories'] / $dailyStats['goals']['calories']) * 100) 
            : 0;
        $proteinPercent = $dailyStats['goals']['protein'] > 0 
            ? min(100, ($dailyStats['consumed']['protein'] / $dailyStats['goals']['protein']) * 100) 
            : 0;
        
        // Get previous and next dates
        $prevDate = \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d');
        $nextDate = \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d');
        $today = now()->format('Y-m-d');
        
        return view('meals.report', compact(
            'date',
            'dailyStats',
            'mealLogs',
            'mealStats',
            'user',
            'caloriesPercent',
            'proteinPercent',
            'prevDate',
            'nextDate',
            'today'
        ));
    }
}

