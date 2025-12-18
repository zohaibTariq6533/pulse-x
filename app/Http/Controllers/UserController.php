<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function loginPage(){
        return view('login/login');
    }
    public function login(Request $request){
        $credentials=$request->validate([
            'email'=>'required | email',
            'password'=>'required'
        ]);
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        } else {
            return back()->with([
                'error' => 'The provided credentials do not match our records.',
            ])->withInput(); // Keeps entered email in the input field
        }
    }


    public function signupPage(){
        return view('signup/basicSignup');
    }

    public function signup1(Request $req){
        try{
            $req->validate([
                'first_name'=> 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|max:24',
                'age' => 'nullable',
                'height'=> 'nullable',
                'weight'=>'nullable'
            ]);
    
            $user = User::create([
                'name'=>$req->first_name,
                'email'=>$req->email,
                'password'=>Hash::make($req->password)
            ]);
    
            // Authenticate the user
            Auth::login($user);
    
            return redirect()->route('signup-page2');
        }catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function signup2Page(){
        return view('signup/signup2');
    }

    public function signup2(Request $req){
        try{
            $req->validate([
                'gender' => 'nullable|in:male,female',
                'age' => 'nullable|integer|min:1|max:120',
                'height' => 'nullable|numeric|min:50|max:250',
                'weight' => 'nullable|numeric|min:20|max:300'
            ]);
            
            $gender = $req->gender;
            $age = $req->age;
            $height = $req->height;
            $weight = $req->weight;

            // Calculate BMR (Basal Metabolic Rate) using Mifflin-St Jeor equation
            $bmr = null;
            if ($gender && $age && $height && $weight) {
                $genderValue = ($gender == 'male' ? 5 : -161);
                $bmr = round((10 * $weight) + (6.25 * $height) - (5 * $age) + $genderValue);
            }
            
            $userId = Auth::id();
    
            if ($userId) {
                $updateData = [
                    'gender' => $req->gender,
                    'age' => $req->age,
                    'height' => $req->height,
                    'weight' => $req->weight
                ];
                
                if ($bmr !== null) {
                    $updateData['bmr'] = $bmr;
                }
                
                DB::table('users')->where('id', $userId)->update($updateData);
            }
    
            return redirect()->route('signup-page3');
        }catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function signup3Page(){
        return view('signup/signup3');
    }

    public function signup3(Request $req){
        try{
            $req->validate([
                'activity_level' => 'nullable|in:basic,moderate,high',
                'goes_to_gym' => 'nullable|boolean'
            ]);
            
            $userId = Auth::id();
            $bmr = DB::table('users')->where('id', $userId)->value('bmr');
            $activity_factor=null;
            if($req->activity_level=='basic'){
                $activity_factor=1.37;
            }elseif($req->activity_level=='moderate'){
                $activity_factor=1.55;
            }elseif($req->activity_level){
                $activity_factor=1.7;
            }
            $cals=$bmr*$activity_factor;
            if ($userId) {
                DB::table('users')->where('id', $userId)->update([
                    'activity_level' => $req->activity_level,
                    'current_calories'=>$cals,
                    'goes_to_gym' => $req->has('goes_to_gym') ? 1 : 0
                ]);
            }
    
            return redirect()->route('signup-page4');
        }catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function signup4Page(){
        $userId = Auth::id();
        $goesToGym = $userId ? DB::table('users')->where('id', $userId)->value('goes_to_gym') : false;
        return view('signup/signup4', ['goesToGym' => $goesToGym]);
    }

    public function signup4(Request $req){
        try{
            $userId = Auth::id();
            $goesToGym = DB::table('users')->where('id', $userId)->value('goes_to_gym');
            
            $allowedGoals = ['weight_lose', 'weight_maintain', 'weight_gain'];
            if ($goesToGym) {
                $allowedGoals = array_merge($allowedGoals, ['cut', 'bulk']);
            }
            
            $req->validate([
                'goal' => 'required|in:' . implode(',', $allowedGoals)
            ]);
            
            // Calculate calories_gap based on goal
            $caloriesGap = 0;
            switch($req->goal) {
                case 'weight_lose':
                    $caloriesGap = -500; // Weight Loss / Lose fat
                    break;
                case 'weight_maintain':
                    $caloriesGap = 0; // Maintain / Stay at current weight
                    break;
                case 'weight_gain':
                    $caloriesGap = 500; // Weight gain
                    break;
                case 'bulk':
                    $caloriesGap = 250; // Lean Bulk / Muscle Gain
                    break;
                case 'cut':
                    $caloriesGap = -250; // Cut
                    break;
            }
            
            if ($userId) {
                // Get current_calories and weight to calculate goal_calories
                $userData = DB::table('users')->where('id', $userId)->select('current_calories', 'weight','goes_to_gym','goal_calories')->first();
                $currentCalories = $userData->current_calories ?? 0;
                $weight = $userData->weight ?? null;
                $goes_to_gym=$userData->goes_to_gym ?? null;
                $protein_goal=null;
                $fat_goal=null;
                $goalCalories = $currentCalories + $caloriesGap;
                if($goes_to_gym===1){
                    $protein_goal=1.5*$weight;
                    $fat_goal=(($goalCalories/100)*25)/9;
                }else{
                    $protein_goal=0.8*$weight;
                    $fat_goal=($goalCalories/100)*30;
                }
                $carbs = ($goalCalories - ($protein_goal * 4) - ($fat_goal * 9)) / 4;

                
                
                
                DB::table('users')->where('id', $userId)->update([
                    'goal' => $req->goal,
                    'calories_gap' => $caloriesGap,
                    'goal_calories' => $goalCalories,
                    'goal_protein'=>$protein_goal,
                    'goal_fat'=>$fat_goal,
                    'goal_carbs'=>$carbs,
                ]);
            }
    
            return redirect()->route('dashboard');
        }catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }


    public function logoutUser(){
        Auth::logout();
        return redirect()->route('login-Page');
    }
}
