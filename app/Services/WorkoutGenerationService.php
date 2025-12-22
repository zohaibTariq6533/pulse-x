<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WorkoutGenerationService
{
    protected string $apiKey;
    protected string $apiHost;
    protected string $baseUrl = 'https://exercisedb.p.rapidapi.com';

    public function __construct()
    {
        $this->apiKey = env('EXCERCISE_API_KEY', '');
        $this->apiHost = env('EXCERCISE_API_Host', 'exercisedb.p.rapidapi.com');
    }

    /**
     * Generate personalized workout plan based on user details
     *
     * @param object $userDetail User object with all profile details
     * @param string $workoutType 'ppl' or 'bro'
     * @param string $intensity 'low', 'Medium', or 'High'
     * @return array Generated workout plan structure
     */
    public function generateWorkoutPlan($userDetail, string $workoutType, string $intensity): array
    {
        // Normalize intensity to lowercase
        $intensity = strtolower(trim($intensity));
        
        // Handle case variations (Low, low, LOW -> low, Medium -> medium, High -> high)
        $intensityMap = ['low' => 'low', 'medium' => 'medium', 'high' => 'high'];
        $intensity = $intensityMap[$intensity] ?? 'medium';
        
        $goesToGym = $userDetail->goes_to_gym == 1 || $userDetail->goes_to_gym == true;
        
        if ($workoutType === 'ppl') {
            return $this->generatePPLWorkout($userDetail, $intensity, $goesToGym);
        } elseif ($workoutType === 'bro') {
            return $this->generateBroSplitWorkout($userDetail, $intensity, $goesToGym);
        }

        return [];
    }

    /**
     * Generate Push/Pull/Legs workout plan
     */
    protected function generatePPLWorkout($userDetail, string $intensity, bool $goesToGym): array
    {
        $workoutDays = [
            $this->createWorkoutDay('Push Day', $this->getPushExercises($goesToGym), $userDetail, $intensity, 1),
            $this->createWorkoutDay('Pull Day', $this->getPullExercises($goesToGym), $userDetail, $intensity, 2),
            $this->createWorkoutDay('Legs Day', $this->getLegsExercises($goesToGym), $userDetail, $intensity, 3),
        ];

        return [
            'days' => $workoutDays,
            'total_workouts' => 3,
            'generated_at' => now()->toDateString(),
            'workout_type' => 'ppl',
            'intensity' => $intensity,
        ];
    }

    /**
     * Generate Bro Split workout plan
     */
    protected function generateBroSplitWorkout($userDetail, string $intensity, bool $goesToGym): array
    {
        $workoutDays = [
            $this->createWorkoutDay('Chest Day', $this->getChestExercises($goesToGym), $userDetail, $intensity, 1),
            $this->createWorkoutDay('Back Day', $this->getBackExercises($goesToGym), $userDetail, $intensity, 2),
            $this->createWorkoutDay('Shoulders Day', $this->getShoulderExercises($goesToGym), $userDetail, $intensity, 3),
            $this->createWorkoutDay('Arms Day', $this->getArmsExercises($goesToGym), $userDetail, $intensity, 4),
            $this->createWorkoutDay('Legs Day', $this->getLegsExercises($goesToGym), $userDetail, $intensity, 5),
        ];

        return [
            'days' => $workoutDays,
            'total_workouts' => 5,
            'generated_at' => now()->toDateString(),
            'workout_type' => 'bro',
            'intensity' => $intensity,
        ];
    }

    /**
     * Create a workout day structure
     */
    protected function createWorkoutDay(string $dayName, array $bodyParts, $userDetail, string $intensity, int $dayNumber): array
    {
        $exercises = [];
        $totalExercises = $this->calculateExerciseCount($userDetail, $intensity);

        foreach ($bodyParts as $bodyPart) {
            $bodyPartExercises = $this->fetchExercisesByBodyPart($bodyPart, $userDetail->goes_to_gym == 1);
            
            if (empty($bodyPartExercises)) {
                // Log if we couldn't fetch exercises for a body part
                Log::warning("No exercises found for body part: {$bodyPart}", [
                    'day_name' => $dayName,
                    'goes_to_gym' => $userDetail->goes_to_gym ?? 0,
                    'user_id' => $userDetail->id ?? null,
                ]);
                continue;
            }

            // Select exercises for this body part based on user profile
            $selectedExercises = $this->selectExercises($bodyPartExercises, $bodyPart, $userDetail, $intensity);
            
            if (empty($selectedExercises)) {
                Log::warning("No exercises selected after filtering for body part: {$bodyPart}", [
                    'available_exercises' => count($bodyPartExercises),
                    'day_name' => $dayName,
                    'intensity' => $intensity,
                ]);
                
                // If selection returned empty but we have exercises, try to get at least one
                if (count($bodyPartExercises) > 0) {
                    $selectedExercises = array_slice($bodyPartExercises, 0, 1);
                    Log::info("Using fallback exercise for body part: {$bodyPart}");
                }
            }
            
            if (!empty($selectedExercises)) {
                $exercises = array_merge($exercises, $selectedExercises);
            }
        }

        // Ensure we have at least some exercises
        if (empty($exercises)) {
            Log::warning("No exercises generated for {$dayName}", [
                'bodyParts' => $bodyParts,
                'user_id' => $userDetail->id ?? null,
            ]);
            // Return empty day structure if no exercises found
            return [
                'day' => $dayNumber,
                'day_name' => $dayName,
                'exercises' => [],
                'estimated_duration_minutes' => 0,
            ];
        }

        // Limit total exercises per day based on intensity and activity level
        $exercises = array_slice($exercises, 0, $totalExercises);

        // Format exercises with sets/reps
        $formattedExercises = [];
        foreach ($exercises as $index => $exercise) {
            $setsReps = $this->calculateSetsReps($intensity, $exercise, $userDetail);
            $formattedExercises[] = [
                'exercise_id' => $exercise['id'] ?? null,
                'name' => $exercise['name'] ?? 'Unknown Exercise',
                'sets' => $setsReps['sets'],
                'reps' => $setsReps['reps'],
                'bodyPart' => $exercise['bodyPart'] ?? '',
                'target' => $exercise['target'] ?? '',
                'equipment' => $exercise['equipment'] ?? '',
                'rest_seconds' => $this->calculateRestTime($intensity, $userDetail),
                'gifUrl' => $exercise['gifUrl'] ?? null,
            ];
        }

        return [
            'day' => $dayNumber,
            'day_name' => $dayName,
            'exercises' => $formattedExercises,
            'estimated_duration_minutes' => $this->calculateWorkoutDuration($formattedExercises),
        ];
    }

    /**
     * Calculate number of exercises based on user profile
     * Considers: activity level, age, intensity
     */
    protected function calculateExerciseCount($userDetail, string $intensity): int
    {
        $baseCount = 6;
        
        // Adjust based on activity level
        $activityMultiplier = [
            'basic' => 0.8,
            'moderate' => 1.0,
            'high' => 1.2,
        ];
        
        $multiplier = $activityMultiplier[strtolower($userDetail->activity_level ?? 'moderate')] ?? 1.0;
        
        // Adjust based on age (reduce volume for older users)
        $age = $userDetail->age ?? null;
        if ($age && $age > 50) {
            $multiplier *= 0.9; // Slightly reduce for users over 50
        } elseif ($age && $age < 20) {
            $multiplier *= 1.1; // Slightly increase for younger users
        }
        
        // Adjust based on intensity
        $intensityAdjustment = [
            'low' => -1,
            'medium' => 0,
            'high' => +1,
        ];
        
        $adjustment = $intensityAdjustment[strtolower($intensity)] ?? 0;
        
        return max(4, min(10, (int)($baseCount * $multiplier) + $adjustment));
    }

    /**
     * Get body parts for Push day
     */
    protected function getPushExercises(bool $goesToGym): array
    {
        return ['chest', 'shoulders', 'upper arms']; // chest, shoulders, triceps
    }

    /**
     * Get body parts for Pull day
     */
    protected function getPullExercises(bool $goesToGym): array
    {
        return ['back', 'upper arms']; // back, biceps
    }

    /**
     * Get body parts for Legs day
     */
    protected function getLegsExercises(bool $goesToGym): array
    {
        return ['upper legs', 'lower legs', 'waist'];
    }

    /**
     * Get body parts for Chest day (Bro Split)
     */
    protected function getChestExercises(bool $goesToGym): array
    {
        return ['chest'];
    }

    /**
     * Get body parts for Back day (Bro Split)
     */
    protected function getBackExercises(bool $goesToGym): array
    {
        return ['back'];
    }

    /**
     * Get body parts for Shoulders day (Bro Split)
     */
    protected function getShoulderExercises(bool $goesToGym): array
    {
        return ['shoulders'];
    }

    /**
     * Get body parts for Arms day (Bro Split)
     */
    protected function getArmsExercises(bool $goesToGym): array
    {
        return ['upper arms', 'lower arms'];
    }

    /**
     * Fetch exercises from ExerciseDB API by body part
     */
    protected function fetchExercisesByBodyPart(string $bodyPart, bool $goesToGym): array
    {
        try {
            // Increase limit to get more exercises for filtering
            $response = Http::timeout(30)->withHeaders([
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => $this->apiHost,
            ])->get("{$this->baseUrl}/exercises/bodyPart/{$bodyPart}", [
                'limit' => 100, // Increased from 50 to get more options for home filtering
            ]);

            if ($response->successful()) {
                $exercises = $response->json();
                
                // Ensure we have an array
                if (!is_array($exercises)) {
                    $exercises = [];
                }
                
                // Filter exercises based on equipment availability
                if (!$goesToGym && !empty($exercises)) {
                    $originalCount = count($exercises);
                    $filteredExercises = $this->filterHomeExercises($exercises);
                    
                    // If filtering removed all or too many exercises, use original as fallback
                    // This ensures we always have exercises even if equipment tags aren't perfect
                    if (empty($filteredExercises) && count($exercises) > 0) {
                        Log::warning("Filtering removed all exercises for body part: {$bodyPart}. Using unfiltered exercises as fallback.", [
                            'total_exercises' => $originalCount,
                            'sample_equipment' => array_slice(array_unique(array_column($exercises, 'equipment')), 0, 5),
                        ]);
                        // Use original exercises as fallback - better to have exercises than none
                        $filteredExercises = $exercises;
                    } elseif (count($filteredExercises) < 3 && count($exercises) >= 3) {
                        // If we have very few filtered exercises but many total, use original
                        // This handles cases where equipment tags aren't consistently applied
                        Log::info("Very few filtered exercises for body part: {$bodyPart}. Using unfiltered exercises.", [
                            'filtered_count' => count($filteredExercises),
                            'total_count' => $originalCount,
                        ]);
                        $filteredExercises = $exercises;
                    }
                    
                    $exercises = $filteredExercises;
                }
                
                return $exercises;
            }

            Log::warning("ExerciseDB API failed for body part: {$bodyPart}", [
                'status' => $response->status(),
            ]);

            // Return empty array if API fails - will use fallback or fewer exercises
            return [];
        } catch (\Exception $e) {
            Log::error("ExerciseDB API exception for body part: {$bodyPart}", [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Filter exercises for home workouts (bodyweight/dumbbells)
     */
    protected function filterHomeExercises(array $exercises): array
    {
        // More flexible equipment matching - handle variations
        $homeEquipment = [
            'body weight', 'bodyweight', 'body-weight',
            'dumbbell', 'dumbbells',
            'resistance band', 'resistance bands', 'band',
            'medicine ball', 'medicine-ball',
            'stability ball', 'stability-ball',
            'kettlebell', 'kettlebells',
            'pull-up bar', 'pull up bar',
            'none' // Some exercises have no equipment listed
        ];
        
        $filtered = array_filter($exercises, function ($exercise) use ($homeEquipment) {
            $equipment = strtolower(trim($exercise['equipment'] ?? ''));
            
            // Check exact match
            if (in_array($equipment, $homeEquipment)) {
                return true;
            }
            
            // Check if equipment contains any home equipment keyword
            foreach ($homeEquipment as $homeEq) {
                if (strpos($equipment, $homeEq) !== false) {
                    return true;
                }
            }
            
            // If equipment is empty or "none", include it (likely bodyweight)
            if (empty($equipment) || $equipment === 'none') {
                return true;
            }
            
            return false;
        });
        
        return $filtered;
    }

    /**
     * Select exercises based on user profile and goals
     */
    protected function selectExercises(array $exercises, string $bodyPart, $userDetail, string $intensity): array
    {
        if (empty($exercises)) {
            return [];
        }

        // Shuffle for variety
        shuffle($exercises);

        // Prioritize compound movements
        usort($exercises, function ($a, $b) {
            $compoundExercises = ['bench', 'squat', 'deadlift', 'press', 'row', 'pull', 'dip'];
            $aIsCompound = false;
            $bIsCompound = false;
            
            $aName = strtolower($a['name'] ?? '');
            $bName = strtolower($b['name'] ?? '');
            
            foreach ($compoundExercises as $compound) {
                if (strpos($aName, $compound) !== false) $aIsCompound = true;
                if (strpos($bName, $compound) !== false) $bIsCompound = true;
            }
            
            if ($aIsCompound && !$bIsCompound) return -1;
            if (!$aIsCompound && $bIsCompound) return 1;
            return 0;
        });

        // Select exercises based on body part and intensity
        $exerciseCount = $this->getExerciseCountForBodyPart($bodyPart, $intensity);
        
        return array_slice($exercises, 0, min($exerciseCount, count($exercises)));
    }

    /**
     * Get exercise count per body part based on intensity
     */
    protected function getExerciseCountForBodyPart(string $bodyPart, string $intensity): int
    {
        $bodyPartLower = strtolower($bodyPart);
        $intensityLower = strtolower($intensity);
        
        $baseCount = [
            'chest' => 3,
            'back' => 3,
            'shoulders' => 2,
            'upper arms' => 2,
            'lower arms' => 1,
            'upper legs' => 4,
            'lower legs' => 2,
            'waist' => 2,
        ];

        $count = $baseCount[$bodyPartLower] ?? 2;

        // Adjust for intensity
        if ($intensityLower === 'high') {
            $count += 1;
        } elseif ($intensityLower === 'low') {
            $count = max(1, $count - 1);
        }

        return $count;
    }

    /**
     * Calculate sets and reps based on intensity and user profile
     * Considers: intensity, exercise type, activity level, age, gender
     */
    protected function calculateSetsReps(string $intensity, array $exercise, $userDetail): array
    {
        $isCompound = $this->isCompoundExercise($exercise['name'] ?? '');
        
        // Base sets/reps by intensity (normalize to lowercase)
        $intensityLower = strtolower($intensity);
        $intensityProfiles = [
            'low' => [
                'sets' => 3,
                'reps' => $isCompound ? '12-15' : '15-20',
            ],
            'medium' => [
                'sets' => 3,
                'reps' => $isCompound ? '10-12' : '12-15',
            ],
            'high' => [
                'sets' => 4,
                'reps' => $isCompound ? '6-10' : '10-12',
            ],
        ];

        $profile = $intensityProfiles[$intensityLower] ?? $intensityProfiles['medium'];

        // Adjust based on activity level
        $activityLevel = strtolower($userDetail->activity_level ?? 'moderate');
        if ($activityLevel === 'basic') {
            $profile['sets'] = max(2, $profile['sets'] - 1);
        } elseif ($activityLevel === 'high') {
            if ($intensityLower === 'high') {
                $profile['sets'] += 1;
            }
        }

        // Adjust based on age
        $age = $userDetail->age ?? null;
        if ($age && $age > 50) {
            // Older users: slightly more reps, fewer sets for joint health
            if ($profile['sets'] > 3) {
                $profile['sets'] = 3;
            }
        }

        // Gender can influence recovery - adjust rest periods (handled in calculateRestTime)
        // Weight/height can influence exercise selection (handled in selectExercises)
        
        return $profile;
    }

    /**
     * Check if exercise is compound movement
     */
    protected function isCompoundExercise(string $exerciseName): bool
    {
        $compoundKeywords = ['bench', 'squat', 'deadlift', 'press', 'row', 'pull-up', 'dip', 'lunge'];
        $name = strtolower($exerciseName);
        
        foreach ($compoundKeywords as $keyword) {
            if (strpos($name, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Calculate rest time between sets
     * Considers: intensity, activity level, age
     */
    protected function calculateRestTime(string $intensity, $userDetail): int
    {
        $intensityLower = strtolower($intensity);
        $baseRest = [
            'low' => 60,
            'medium' => 90,
            'high' => 120,
        ];

        $rest = $baseRest[$intensityLower] ?? 90;

        // Adjust for activity level
        $activityLevel = strtolower($userDetail->activity_level ?? 'moderate');
        if ($activityLevel === 'basic') {
            $rest += 30; // More rest for beginners
        } elseif ($activityLevel === 'high') {
            $rest = max(60, $rest - 15); // Less rest for advanced
        }

        // Adjust for age (older users may need more recovery)
        $age = $userDetail->age ?? null;
        if ($age && $age > 50) {
            $rest += 15; // Additional rest for users over 50
        }

        return $rest;
    }

    /**
     * Calculate estimated workout duration in minutes
     */
    protected function calculateWorkoutDuration(array $exercises): int
    {
        $totalMinutes = 0;
        
        foreach ($exercises as $exercise) {
            $sets = $exercise['sets'];
            $restSeconds = $exercise['rest_seconds'];
            $exerciseTime = ($sets * 2) + (($sets - 1) * ($restSeconds / 60)); // 2 min per set + rest
            $totalMinutes += $exerciseTime;
        }

        // Add warm-up and cool-down
        $totalMinutes += 10;

        return (int)ceil($totalMinutes);
    }
}

