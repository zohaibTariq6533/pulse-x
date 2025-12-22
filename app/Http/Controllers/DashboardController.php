<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{
    public function dashboard(){
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();
        
        // Get today's meal stats
        $today = now()->format('Y-m-d');
        $stats = DB::table('meal_logs')
            ->where('user_id', $userId)
            ->where('date', $today)
            ->selectRaw('
                COALESCE(SUM(total_calories), 0) as total_calories,
                COALESCE(SUM(total_protein), 0) as total_protein
            ')
            ->first();
        
        $consumedCalories = (float) ($stats->total_calories ?? 0);
        $goalCalories = (float) ($user->goal_calories ?? 2000);
        $remainingCalories = $goalCalories - $consumedCalories;
        $caloriesPercent = $goalCalories > 0 ? min(100, ($consumedCalories / $goalCalories) * 100) : 0;
        
        $consumedProtein = (float) ($stats->total_protein ?? 0);
        $goalProtein = (float) ($user->goal_protein ?? 150);
        $proteinPercent = $goalProtein > 0 ? min(100, ($consumedProtein / $goalProtein) * 100) : 0;
        
        // Get latest workout plan
        $latestWorkoutPlan = DB::table('workout_plans')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $workoutPlan = null;
        if ($latestWorkoutPlan && !empty($latestWorkoutPlan->plan)) {
            $planData = json_decode($latestWorkoutPlan->plan, true);
            if (!empty($planData) && isset($planData['days'])) {
                $workoutPlan = [
                    'id' => $latestWorkoutPlan->id,
                    'type' => $latestWorkoutPlan->type,
                    'intensity' => $latestWorkoutPlan->intensity,
                    'total_days' => $planData['total_workouts'] ?? count($planData['days'] ?? []),
                    'created_at' => $latestWorkoutPlan->created_at,
                ];
            }
        }
        
        return view('dashboard', [
            'user' => $user,
            'consumedCalories' => $consumedCalories,
            'goalCalories' => $goalCalories,
            'remainingCalories' => $remainingCalories,
            'caloriesPercent' => $caloriesPercent,
            'consumedProtein' => $consumedProtein,
            'goalProtein' => $goalProtein,
            'proteinPercent' => $proteinPercent,
            'workoutPlan' => $workoutPlan,
        ]);
    }
    
    /**
     * Get nutrition carousel data (AJAX endpoint)
     */
    public function getNutritionCarousel()
    {
        $userId = Auth::id();
        $today = now()->format('Y-m-d');
        
        // Get nutrition data for last 7 days for carousel
        $carouselDays = [];
        for ($i = 6; $i >= 0; $i--) {
            $carouselDate = \Carbon\Carbon::parse($today)->subDays($i)->format('Y-m-d');
            $dayStats = $this->getDailyStats($userId, $carouselDate);
            $carouselDays[] = [
                'date' => $carouselDate,
                'dateFormatted' => \Carbon\Carbon::parse($carouselDate)->format('M d'),
                'stats' => $dayStats,
            ];
        }
        
        return response()->json([
            'success' => true,
            'carouselDays' => $carouselDays,
            'today' => $today,
        ]);
    }
    
    /**
     * Get daily nutrition stats (helper method)
     */
    private function getDailyStats($userId, $date)
    {
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
        ];
    }
}

