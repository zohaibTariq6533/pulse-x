<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UsdaFoodService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.usda.base_url');
        $this->apiKey = config('services.usda.api_key');
    }

    /**
     * Search foods via USDA API
     */
    public function searchFoods(string $query, int $pageNumber = 1, int $pageSize = 50): array
    {
        try {
            $response = Http::timeout(30)->get($this->baseUrl . 'foods/search', [
                'api_key' => $this->apiKey,
                'query' => $query,
                'pageNumber' => $pageNumber,
                'pageSize' => $pageSize,
                'dataType' => 'Foundation,SR Legacy,Survey (FNDDS),Branded',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('USDA API search failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['foods' => [], 'totalHits' => 0];
        } catch (\Exception $e) {
            Log::error('USDA API search exception', ['error' => $e->getMessage()]);
            return ['foods' => [], 'totalHits' => 0];
        }
    }

    /**
     * Get detailed food information by FDC ID
     */
    public function getFoodDetails(int $fdcId): ?array
    {
        try {
            $response = Http::timeout(30)->get($this->baseUrl . "food/{$fdcId}", [
                'api_key' => $this->apiKey,
                'nutrients' => '203,204,205,208,269', // Protein, Fat, Carbs, Energy, Sugar
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('USDA API food details failed', [
                'fdcId' => $fdcId,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('USDA API food details exception', [
                'fdcId' => $fdcId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Normalize USDA API response to our database format
     */
    public function normalizeFoodData(array $usdaFood): array
    {
        // Extract nutrients from foodNutrients array
        $nutrients = [];
        foreach ($usdaFood['foodNutrients'] ?? [] as $nutrient) {
            $nutrientId = $nutrient['nutrient']['id'] ?? $nutrient['nutrientId'] ?? null;
            $value = $nutrient['amount'] ?? $nutrient['value'] ?? 0;
            $nutrients[$nutrientId] = $value;
        }

        // Extract food portions (for physical quantity options like "1 egg", "1 cup", etc.)
        $portions = [];
        if (isset($usdaFood['foodPortions']) && is_array($usdaFood['foodPortions'])) {
            foreach ($usdaFood['foodPortions'] as $portion) {
                // Only add portions with valid gramWeight
                if (isset($portion['gramWeight']) && $portion['gramWeight'] > 0) {
                    $portions[] = [
                        'modifier' => $portion['modifier'] ?? '',
                        'amount' => $portion['amount'] ?? 1,
                        'gramWeight' => (float) $portion['gramWeight'],
                        'portionDescription' => $portion['portionDescription'] ?? ($portion['modifier'] ?? ''),
                    ];
                }
            }
        }
        
        // If no portions from API, create some default common portions based on food name
        if (empty($portions)) {
            $portions = $this->generateDefaultPortions($usdaFood['description'] ?? '');
        }
        
        // Log if no portions found (for debugging)
        if (empty($portions)) {
            Log::warning('No portions found for food', [
                'fdc_id' => $usdaFood['fdcId'] ?? null,
                'name' => $usdaFood['description'] ?? '',
                'has_foodPortions' => isset($usdaFood['foodPortions']),
            ]);
        }

        // USDA Nutrient IDs: 1008=Energy (kcal), 1003=Protein, 1005=Carbs, 1004=Total Fat
        $baseWeight = 100; // USDA data is per 100g

        return [
            'fdc_id' => $usdaFood['fdcId'],
            'name' => $usdaFood['description'] ?? 'Unknown Food',
            'serving_size' => '100g',
            'serving_weight_grams' => $baseWeight,
            'calories_per_serving' => $nutrients[1008] ?? $nutrients[208] ?? 0,
            'protein_per_serving' => $nutrients[1003] ?? $nutrients[203] ?? 0,
            'carbs_per_serving' => $nutrients[1005] ?? $nutrients[205] ?? 0,
            'fats_per_serving' => $nutrients[1004] ?? $nutrients[204] ?? 0,
            'portions' => !empty($portions) ? json_encode($portions) : null,
            'api_source' => 'usda',
            'is_custom' => false,
            'cached_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Cache food from USDA API response (using Query Builder)
     */
    public function cacheFood(array $usdaFood): ?object
    {
        $data = $this->normalizeFoodData($usdaFood);
        
        // Check if food already exists
        $existing = DB::table('foods')->where('fdc_id', $data['fdc_id'])->first();
        
        if ($existing) {
            // Update existing record
            $updateData = [
                'name' => $data['name'],
                'calories_per_serving' => $data['calories_per_serving'],
                'protein_per_serving' => $data['protein_per_serving'],
                'carbs_per_serving' => $data['carbs_per_serving'],
                'fats_per_serving' => $data['fats_per_serving'],
                'cached_at' => now(),
                'updated_at' => now(),
            ];
            
            // Only update portions if they exist in the new data
            if (isset($data['portions'])) {
                $updateData['portions'] = $data['portions'];
            }
            
            DB::table('foods')
                ->where('fdc_id', $data['fdc_id'])
                ->update($updateData);
            
            return DB::table('foods')->where('fdc_id', $data['fdc_id'])->first();
        } else {
            // Insert new record
            $id = DB::table('foods')->insertGetId($data);
            return DB::table('foods')->where('id', $id)->first();
        }
    }

    /**
     * Check if food is already cached (using Query Builder)
     */
    public function isCached(int $fdcId): bool
    {
        return DB::table('foods')->where('fdc_id', $fdcId)->exists();
    }

    /**
     * Get cached food by FDC ID (using Query Builder)
     */
    public function getCachedFood(int $fdcId): ?object
    {
        return DB::table('foods')->where('fdc_id', $fdcId)->first();
    }

    /**
     * Search cached foods by name (using Query Builder)
     */
    public function searchCachedFoods(string $query, int $limit = 20): array
    {
        return DB::table('foods')
            ->where('name', 'like', "%{$query}%")
            ->orderBy('times_logged', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Helper method to extract nutrient from USDA API response
     */
    public function extractNutrient(array $apiFood, int $nutrientId): ?float
    {
        foreach ($apiFood['foodNutrients'] ?? [] as $nutrient) {
            $id = $nutrient['nutrient']['id'] ?? $nutrient['nutrientId'] ?? null;
            if ($id == $nutrientId) {
                return (float) ($nutrient['amount'] ?? $nutrient['value'] ?? 0);
            }
        }
        return null;
    }

    /**
     * Generate default portions for foods that don't have portion data from API
     */
    public function generateDefaultPortions(string $foodName): array
    {
        $portions = [];
        $name = strtolower($foodName);
        
        // Common portion sizes by food type
        $defaultPortions = [
            // Fruits
            'banana' => [
                ['portionDescription' => '1 small', 'gramWeight' => 81],
                ['portionDescription' => '1 medium', 'gramWeight' => 118],
                ['portionDescription' => '1 large', 'gramWeight' => 136],
            ],
            'apple' => [
                ['portionDescription' => '1 small', 'gramWeight' => 149],
                ['portionDescription' => '1 medium', 'gramWeight' => 182],
                ['portionDescription' => '1 large', 'gramWeight' => 223],
            ],
            'orange' => [
                ['portionDescription' => '1 small', 'gramWeight' => 96],
                ['portionDescription' => '1 medium', 'gramWeight' => 131],
                ['portionDescription' => '1 large', 'gramWeight' => 184],
            ],
            // Eggs
            'egg' => [
                ['portionDescription' => '1 small', 'gramWeight' => 38],
                ['portionDescription' => '1 medium', 'gramWeight' => 44],
                ['portionDescription' => '1 large', 'gramWeight' => 50],
                ['portionDescription' => '1 extra large', 'gramWeight' => 56],
            ],
            // Chicken
            'chicken' => [
                ['portionDescription' => '1 oz', 'gramWeight' => 28],
                ['portionDescription' => '3 oz', 'gramWeight' => 85],
                ['portionDescription' => '4 oz', 'gramWeight' => 113],
            ],
            // Rice
            'rice' => [
                ['portionDescription' => '1 cup cooked', 'gramWeight' => 158],
                ['portionDescription' => '1/2 cup cooked', 'gramWeight' => 79],
            ],
            // Bread
            'bread' => [
                ['portionDescription' => '1 slice', 'gramWeight' => 28],
                ['portionDescription' => '2 slices', 'gramWeight' => 56],
            ],
        ];
        
        // Check if we have default portions for this food
        foreach ($defaultPortions as $key => $values) {
            if (str_contains($name, $key)) {
                return $values;
            }
        }
        
        // Generic defaults if no match
        return [
            ['portionDescription' => '1 serving (100g)', 'gramWeight' => 100],
            ['portionDescription' => '1/2 serving (50g)', 'gramWeight' => 50],
            ['portionDescription' => '2 servings (200g)', 'gramWeight' => 200],
        ];
    }
}

