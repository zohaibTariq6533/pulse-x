<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ValidUser;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\MealController;
use Illuminate\Support\Facades\Response;

// PWA Routes
Route::get('/manifest.json', function () {
    return Response::file(public_path('manifest.json'), [
        'Content-Type' => 'application/json',
    ]);
})->name('manifest');

Route::get('/sw.js', function () {
    return Response::file(public_path('sw.js'), [
        'Content-Type' => 'application/javascript',
        'Service-Worker-Allowed' => '/',
    ]);
})->name('service-worker');

// Offline page for PWA
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// Icon generator route
Route::get('/create-icons', function () {
    $iconsDir = public_path('icons');
    if (!is_dir($iconsDir)) {
        mkdir($iconsDir, 0755, true);
    }

    if (!function_exists('imagecreatetruecolor')) {
        return response('GD library is not available. Please install php-gd extension.', 500);
    }

    function createSimpleIcon($size, $filename) {
        $img = imagecreatetruecolor($size, $size);
        
        $darkBlue = imagecolorallocate($img, 15, 32, 39);
        $midBlue = imagecolorallocate($img, 32, 58, 67);
        $lightBlue = imagecolorallocate($img, 44, 83, 100);
        $accentBlue = imagecolorallocate($img, 74, 144, 226);
        $white = imagecolorallocate($img, 255, 255, 255);
        
        // Gradient fill
        for ($y = 0; $y < $size; $y++) {
            $ratio = $y / $size;
            if ($ratio < 0.5) {
                $r = 15 + ($ratio * 2) * 17;
                $g = 32 + ($ratio * 2) * 26;
                $b = 39 + ($ratio * 2) * 28;
            } else {
                $r = 32 + (($ratio - 0.5) * 2) * 12;
                $g = 58 + (($ratio - 0.5) * 2) * 25;
                $b = 67 + (($ratio - 0.5) * 2) * 33;
            }
            $color = imagecolorallocate($img, (int)$r, (int)$g, (int)$b);
            imageline($img, 0, $y, $size, $y, $color);
        }
        
        // Draw "P"
        $fontSize = 5;
        $text = 'P';
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($size - $textWidth) / 2;
        $y = ($size - $textHeight) / 2;
        imagestring($img, $fontSize, (int)$x, (int)$y, $text, $white);
        
        // Small "X"
        imagestring($img, 3, (int)($size * 0.7), (int)($size * 0.15), 'X', $accentBlue);
        
        imagepng($img, $filename);
        imagedestroy($img);
        
        return file_exists($filename);
    }

    $icons = [
        ['size' => 192, 'file' => $iconsDir . '/icon-192x192.png'],
        ['size' => 512, 'file' => $iconsDir . '/icon-512x512.png'],
        ['size' => 180, 'file' => $iconsDir . '/apple-touch-icon.png'],
    ];

    $created = [];
    foreach ($icons as $icon) {
        if (createSimpleIcon($icon['size'], $icon['file'])) {
            $created[] = basename($icon['file']);
        }
    }

    if (empty($created)) {
        return response('Failed to create icons. Check if GD library is installed.', 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'Icons created successfully!',
        'icons' => $created,
        'next_steps' => [
            '1. Refresh your app',
            '2. Clear browser cache',
            '3. Try "Add to Home Screen" again'
        ]
    ]);
});

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

