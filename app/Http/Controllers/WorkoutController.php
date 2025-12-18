<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    public function workoutTypePage(){
        return view('myworkout.gym.workout-type');
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
        
        // Insert workout plan
        DB::table('workout_plans')->insert([
            'user_id'=>Auth::id(),
            'type'=>session('workout_type'),
            'intensity'=>$intensity,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // Get user details (use first() to get single object instead of collection)
        $userDetail=DB::table('users')->where('id',Auth::id())->first();
        
        // Create workout data object from session/parameters
        $workoutData = (object)[
            'type' => session('workout_type'),
            'intensity' => $intensity
        ];
        
        return view('myworkout.gym.generate-workout',compact('userDetail','workoutData'));
        
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
}
