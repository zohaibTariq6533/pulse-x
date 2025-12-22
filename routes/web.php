<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ValidUser;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\MealController;


//Login and signup
Route::get('/login', [UserController::class,'loginPage'])->name('login-Page');
Route::post('login-user',[UserController::class,'login'])->name('login-user');
//signup
Route::get('signup-1',[UserController::class,'signupPage'])->name('signup-page1');
Route::post('signup',[UserController::class,'signup1'])->name('signup');
Route::get('signup-2',[UserController::class,'signup2Page'])->name('signup-page2');
Route::post('signup2',[UserController::class,'signup2'])->name('signup2');
Route::get('signup-3',[UserController::class,'signup3Page'])->name('signup-page3');
Route::post('signup3',[UserController::class,'signup3'])->name('signup3');
Route::get('signup-4',[UserController::class,'signup4Page'])->name('signup-page4');
Route::post('signup4',[UserController::class,'signup4'])->name('signup4');

Route::middleware(['isUserValid'])->group(function(){
    Route::get('/',[DashboardController::class,'dashboard'])->name('dashboard');
    Route::get('/api/nutrition-carousel',[DashboardController::class,'getNutritionCarousel'])->name('dashboard.nutrition-carousel');
    Route::get('/logout-user',[UserController::class,'logoutUser'])->name('logoutUser');


    //personalized workout  -gym
    Route::get('personalized-workout/gym/typePage',[WorkoutController::class,'workoutTypePage'])->name('workoutTypePage');
    Route::get('personalized-workout/intensity/{type}',[WorkoutController::class,'workoutIntensityPage'])->name('workoutIntensityPage');

    Route::get('personalized-workout/gym/workouts/{intensity}',[WorkoutController::class,'createWorkoutPlanPage'])->name('createWorkoutPlanPage');
    Route::post('personalized-workout/gym/generate',[WorkoutController::class,'generateWorkout'])->name('generateWorkout');
    Route::get('personalized-workout/my-plan/{planId}',[WorkoutController::class,'showGeneratedWorkout'])->name('showGeneratedWorkout');
    // Route::get('personalized-workout/gym/review-workout',[WorkoutController::class,'createWorkoutPage'])->name('workouts');

    Route::get('personalized-workout/gym/ppl-workout',[WorkoutController::class,'pplWorkoutPage'])->name('pplWorkoutPage');
    Route::get('personalized-workout/my-workouts/{day?}',[WorkoutController::class,'workouts'])->name('workoutsPage');
    
    // All workouts - muscle groups
    Route::get('all-workouts/muscle-groups',[WorkoutController::class,'muscleGroupsPage'])->name('muscleGroupsPage');
    Route::get('all-workouts/exercises/{bodyPart}',[WorkoutController::class,'singleMusclePage'])->name('singleMusclePage');
    Route::get('all-workouts/exercise-detail/{exerciseId}',[WorkoutController::class,'exerciseDetailPage'])->name('exerciseDetailPage');
    Route::get('all-workouts/exercise-image/{exerciseId}',[WorkoutController::class,'exerciseImage'])->name('exerciseImage');
    
    // Meal Logger Routes
    Route::prefix('meals')->group(function () {
        Route::get('/', [MealController::class, 'index'])->name('meals.index');
        Route::get('/report/{date?}', [MealController::class, 'report'])->name('meals.report');
        Route::post('/search', [MealController::class, 'searchFoods'])->name('meals.search');
        Route::get('/food/{fdcId}', [MealController::class, 'getFoodDetails'])->name('meals.food.details');
        Route::post('/log', [MealController::class, 'logMeal'])->name('meals.log');
        Route::post('/custom-food', [MealController::class, 'addCustomFood'])->name('meals.custom-food');
        Route::get('/stats/{date?}', [MealController::class, 'getDailyStats'])->name('meals.stats');
        Route::delete('/log/{id}', [MealController::class, 'deleteMealLog'])->name('meals.log.delete');
        Route::put('/log/{id}', [MealController::class, 'updateMealLog'])->name('meals.log.update');
        Route::get('/{mealType}/{date?}', [MealController::class, 'showMeal'])->name('meals.show');
    });
});

