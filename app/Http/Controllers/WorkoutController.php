<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use App\Services\WorkoutGenerationService;

class WorkoutController extends Controller
{
    public function workoutTypePage(){
        // Get user gym status
        $user = DB::table('users')->where('id', Auth::id())->select('goes_to_gym')->first();
        $goesToGym = $user->goes_to_gym == 1 || $user->goes_to_gym == true;
        
        return view('myworkout.gym.workout-type', compact('goesToGym'));
    }

    public function workoutIntensityPage($type){
        // Store workout type in session
        session(['workout_type' => $type]);
        $gym=DB::table('users')->where('id', Auth::id())->select('goes_to_gym')->first();
        if($gym->goes_to_gym==1){
            session(['reccomended_intensity' => 'Medium']);
        }
        else{
            session(['reccomended_intensity' => 'low']);
        }
        
        return view('myworkout.gym.workout-intensity');
    }

    // public function workouts(){
    //     return view('myworkout.gym.workout');
    // }
    public function createWorkoutPlanPage($intensity){
        // Store intensity in session
        session(['workout_intensity' => $intensity]);

        // Get user details (use first() to get single object instead of collection)
        $userDetail=DB::table('users')->where('id',Auth::id())->first();
        
        // Create workout data object from session/parameters
        $workoutData = (object)[
            'type' => session('workout_type'),
            'intensity' => $intensity
        ];
        
        return view('myworkout.gym.generate-workout',compact('userDetail','workoutData'));
        
    }

    /**
     * Generate personalized workout plan
     */
    public function generateWorkout(Request $request){
        try {
            $workoutType = session('workout_type');
            $intensity = session('workout_intensity');
            
            if (!$workoutType || !$intensity) {
                return redirect()->route('workoutTypePage')
                    ->with('error', 'Please select workout type and intensity first.');
            }

            // Get user details
            $userDetail = DB::table('users')->where('id', Auth::id())->first();
            
            if (!$userDetail) {
                return redirect()->route('dashboard')
                    ->with('error', 'User not found.');
            }

            // Generate workout plan using service
            $workoutService = new WorkoutGenerationService();
            $workoutPlan = $workoutService->generateWorkoutPlan($userDetail, $workoutType, $intensity);

            if (empty($workoutPlan)) {
                return redirect()->back()
                    ->with('error', 'Failed to generate workout plan. Please try again.');
            }

            // Store workout plan in database
            $planId = DB::table('workout_plans')->insertGetId([
                'user_id' => Auth::id(),
                'type' => $workoutType,
                'intensity' => $intensity,
                'plan' => json_encode($workoutPlan),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Clear session data
            session()->forget(['workout_type', 'workout_intensity', 'reccomended_intensity']);

            return redirect()->route('showGeneratedWorkout', ['planId' => $planId])
                ->with('success', 'Workout plan generated successfully!');

        } catch (\Exception $e) {
            \Log::error('Workout generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while generating your workout plan. Please try again.');
        }
    }

    /**
     * Show generated workout plan
     */
    public function showGeneratedWorkout($planId){
        $workoutPlan = DB::table('workout_plans')
            ->where('id', $planId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$workoutPlan) {
            return redirect()->route('dashboard')
                ->with('error', 'Workout plan not found.');
        }

        $plan = json_decode($workoutPlan->plan, true);
        $userDetail = DB::table('users')->where('id', Auth::id())->first();

        return view('myworkout.gym.generated-workout', compact('plan', 'workoutPlan', 'userDetail'));
    }


    public function pplWorkoutPage(){
        return view('myworkout.workouts.ppl');
    }

    public function workouts($day = null){
        
        // PPL Workout JSON data
        $pplWorkouts = [
            'push' => [
                [
                    'id' => 1,
                    'exercise' => 'Bench Press',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 2,
                    'exercise' => 'Cable Fly',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 3,
                    'exercise' => 'Incline Dumbbell Press',
                    'reps' => 12,
                    'sets' => 3
                ],
                [
                    'id' => 4,
                    'exercise' => 'Tricep Dips',
                    'reps' => 12,
                    'sets' => 3
                ],
                [
                    'id' => 5,
                    'exercise' => 'Overhead Tricep Extension',
                    'reps' => 12,
                    'sets' => 3
                ],
                [
                    'id' => 6,
                    'exercise' => 'Front Delt Raise',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 7,
                    'exercise' => 'Lateral Raise',
                    'reps' => 15,
                    'sets' => 3
                ]
            ],
            'pull' => [
                [
                    'id' => 1,
                    'exercise' => 'Pull-ups',
                    'reps' => 10,
                    'sets' => 3
                ],
                [
                    'id' => 2,
                    'exercise' => 'Barbell Row',
                    'reps' => 12,
                    'sets' => 3
                ],
                [
                    'id' => 3,
                    'exercise' => 'Lat Pulldown',
                    'reps' => 12,
                    'sets' => 3
                ],
                [
                    'id' => 4,
                    'exercise' => 'Bicep Curls',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 5,
                    'exercise' => 'Hammer Curls',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 6,
                    'exercise' => 'Rear Delt Fly',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 7,
                    'exercise' => 'Face Pulls',
                    'reps' => 15,
                    'sets' => 3
                ]
            ],
            'legs' => [
                [
                    'id' => 1,
                    'exercise' => 'Squats',
                    'reps' => 12,
                    'sets' => 3
                ],
                [
                    'id' => 2,
                    'exercise' => 'Leg Press',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 3,
                    'exercise' => 'Romanian Deadlift',
                    'reps' => 12,
                    'sets' => 3
                ],
                [
                    'id' => 4,
                    'exercise' => 'Leg Curls',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 5,
                    'exercise' => 'Leg Extensions',
                    'reps' => 15,
                    'sets' => 3
                ],
                [
                    'id' => 6,
                    'exercise' => 'Calf Raises',
                    'reps' => 20,
                    'sets' => 3
                ],
                [
                    'id' => 7,
                    'exercise' => 'Hip Thrusts',
                    'reps' => 12,
                    'sets' => 3
                ]
            ]
        ];

        // Get workout data based on day
        $workoutData = null;
        $dayName = null;
        
        if ($day && isset($pplWorkouts[$day])) {
            $workoutData = $pplWorkouts[$day];
            $dayName = ucfirst($day);
        }
        
        return view('myworkout.workouts.workouts', compact('workoutData', 'dayName'));
    }

    public function muscleGroupsPage(){
        return view('all-workouts.muscle-group-page-1');
    }

    public function singleMusclePage($bodyPart){
        // Map display names to API body part names
        $bodyPartMap = [
            'chest' => 'chest',
            'back' => 'back',
            'shoulders' => 'shoulders',
            'upper-arms' => 'upper arms',
            'lower-arms' => 'lower arms',
            'upper-legs' => 'upper legs',
            'lower-legs' => 'lower legs',
            'waist' => 'waist',
            'cardio' => 'cardio',
            'neck' => 'neck',
        ];

        // Get the API body part name
        $apiBodyPart = $bodyPartMap[$bodyPart] ?? $bodyPart;
        
        // Display name for the page title
        $displayNames = [
            'chest' => 'Chest',
            'back' => 'Back',
            'shoulders' => 'Shoulders',
            'upper-arms' => 'Bicep & Tricep',
            'lower-arms' => 'Forearms',
            'upper-legs' => 'Legs',
            'lower-legs' => 'Calves',
            'waist' => 'Abs',
        ];
        $displayName = $displayNames[$bodyPart] ?? ucfirst($bodyPart);

        try {
            // Call ExerciseDB API
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => env('EXCERCISE_API_KEY'),
                'X-RapidAPI-Host' => env('EXCERCISE_API_Host'),
            ])->get("https://exercisedb.p.rapidapi.com/exercises/bodyPart/{$apiBodyPart}", [
                'limit' => 50,
            ]);

            if ($response->successful()) {
                $exercises = $response->json();
            } else {
                $exercises = [];
            }
        } catch (\Exception $e) {
            $exercises = [];
        }

        return view('all-workouts.single-muscle', compact('exercises', 'displayName', 'bodyPart'));
    }

    public function exerciseDetailPage($exerciseId){
        try {
            // Call ExerciseDB API to get single exercise by ID
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => env('EXCERCISE_API_KEY'),
                'X-RapidAPI-Host' => env('EXCERCISE_API_Host'),
            ])->get("https://exercisedb.p.rapidapi.com/exercises/exercise/{$exerciseId}");

            if ($response->successful()) {
                $exercise = $response->json();
                
                // Format exercise ID with leading zeros (e.g., 9 -> 0009)
                $formattedId = str_pad($exerciseId, 4, '0', STR_PAD_LEFT);
                
                // Use proxy route for image (which will handle headers)
                $imageUrl = route('exerciseImage', $formattedId);
                
                // Map API body part names back to URL-friendly format for back button
                $reverseBodyPartMap = [
                    'chest' => 'chest',
                    'back' => 'back',
                    'shoulders' => 'shoulders',
                    'upper arms' => 'upper-arms',
                    'lower arms' => 'lower-arms',
                    'upper legs' => 'upper-legs',
                    'lower legs' => 'lower-legs',
                    'waist' => 'waist',
                    'cardio' => 'cardio',
                    'neck' => 'neck',
                ];
                
                $bodyPartSlug = $reverseBodyPartMap[$exercise['bodyPart']] ?? str_replace(' ', '-', strtolower($exercise['bodyPart']));
            } else {
                $exercise = null;
                $bodyPartSlug = null;
                $imageUrl = null;
            }
        } catch (\Exception $e) {
            $exercise = null;
            $bodyPartSlug = null;
            $imageUrl = null;
        }

        return view('all-workouts.muscle-detail', compact('exercise', 'bodyPartSlug', 'imageUrl'));
    }

    public function exerciseImage($exerciseId){
        try {
            // Format exercise ID with leading zeros if needed
            $formattedId = str_pad($exerciseId, 4, '0', STR_PAD_LEFT);
            
            // Fetch image from ExerciseDB API with headers
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => env('EXCERCISE_API_KEY'),
                'X-RapidAPI-Host' => env('EXCERCISE_API_Host'),
            ])->get("https://exercisedb.p.rapidapi.com/image", [
                'resolution' => 180,
                'exerciseId' => $formattedId,
            ]);

            if ($response->successful()) {
                // Get the image content
                $imageContent = $response->body();
                
                // Determine content type from response headers
                $contentType = $response->header('Content-Type') ?? 'image/gif';
                
                // Return image with proper headers
                return response($imageContent, 200)
                    ->header('Content-Type', $contentType)
                    ->header('Cache-Control', 'public, max-age=3600');
            } else {
                // Return 404 if image not found
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
