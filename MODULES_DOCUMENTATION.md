# Pulse-X - Comprehensive Module Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Module 1: Authentication & User Management](#module-1-authentication--user-management)
4. [Module 2: Dashboard Module](#module-2-dashboard-module)
5. [Module 3: Meal Logging & Nutrition Tracking](#module-3-meal-logging--nutrition-tracking)
6. [Module 4: Workout Generation System](#module-4-workout-generation-system)
7. [Module 5: Exercise Database Integration](#module-5-exercise-database-integration)
8. [Database Schema](#database-schema)
9. [API Integrations](#api-integrations)
10. [Configuration](#configuration)
11. [Key Algorithms & Calculations](#key-algorithms--calculations)
12. [Data Flow Examples](#data-flow-examples)
13. [Error Handling](#error-handling)
14. [Performance Optimizations](#performance-optimizations)
15. [Security Considerations](#security-considerations)

---

## Project Overview

**Pulse-X** is a comprehensive fitness and nutrition tracking application built with Laravel 12. The application helps users track their meals, generate personalized workout plans, and monitor their fitness goals through an integrated dashboard.

### Key Features
- Multi-step user registration with goal calculation
- Real-time nutrition tracking with USDA food database integration
- AI-powered personalized workout plan generation
- Exercise database with detailed exercise information
- Comprehensive dashboard with progress visualization

---

## System Architecture

### Technology Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite (development) / MySQL/PostgreSQL (production)
- **Frontend**: Blade Templates with JavaScript
- **External APIs**: 
  - USDA FoodData Central API
  - ExerciseDB API (via RapidAPI)

### Directory Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php    # Dashboard logic
│   │   ├── MealController.php         # Meal logging & nutrition
│   │   ├── UserController.php         # Authentication & signup
│   │   └── WorkoutController.php      # Workout generation & display
│   └── Middleware/
│       └── ValidUser.php              # Authentication middleware
├── Models/
│   └── User.php                       # User Eloquent model
└── Services/
    ├── UsdaFoodService.php            # USDA API integration
    └── WorkoutGenerationService.php   # Workout plan generation logic
```

---

## Module 1: Authentication & User Management

### Overview
The authentication module handles user registration, login, and profile setup through a multi-step onboarding process.

### Components

#### 1.1 Login System
**File**: `app/Http/Controllers/UserController.php` (methods: `loginPage()`, `login()`)

**How it works:**
1. User accesses `/login` route which displays the login form
2. On form submission, credentials are validated:
   - Email must be valid format
   - Password is required
3. Laravel's `Auth::attempt()` validates credentials against the database
4. On success: redirects to dashboard
5. On failure: returns to login page with error message

**Security Features:**
- Passwords are hashed using Laravel's `Hash::make()`
- Session-based authentication
- CSRF protection via Laravel middleware

#### 1.2 Multi-Step Registration Process

The registration is divided into 4 steps, each collecting different user information:

##### Step 1: Basic Information (`signup1()`)
**Route**: `POST /signup`

**Data Collected:**
- First name
- Email (unique)
- Password (8-24 characters)
- Optional: age, height, weight

**Process:**
1. Validates input data
2. Creates user account with hashed password
3. Automatically logs in the user using `Auth::login($user)`
4. Redirects to Step 2

**Database Action:**
- Inserts into `users` table: `name`, `email`, `password`

##### Step 2: Physical Attributes (`signup2()`)
**Route**: `POST /signup2`

**Data Collected:**
- Gender (male/female)
- Age (1-120)
- Height in cm (50-250)
- Weight in kg (20-300)

**Process:**
1. Validates physical attributes
2. **Calculates BMR (Basal Metabolic Rate)** using Mifflin-St Jeor equation:
   ```
   BMR = (10 × weight) + (6.25 × height) - (5 × age) + gender_factor
   gender_factor = 5 (male) or -161 (female)
   ```
3. Updates user record with physical data and calculated BMR

**Database Action:**
- Updates `users` table: `gender`, `age`, `height`, `weight`, `bmr`

##### Step 3: Activity Level (`signup3()`)
**Route**: `POST /signup3`

**Data Collected:**
- Activity level: `basic`, `moderate`, or `high`
- Gym attendance: boolean (`goes_to_gym`)

**Process:**
1. Validates activity level and gym status
2. **Calculates TDEE (Total Daily Energy Expenditure)**:
   ```
   Activity Factors:
   - basic: 1.37
   - moderate: 1.55
   - high: 1.7
   
   TDEE = BMR × activity_factor
   ```
3. Stores `current_calories` (TDEE) and activity preferences

**Database Action:**
- Updates `users` table: `activity_level`, `current_calories`, `goes_to_gym`

##### Step 4: Fitness Goals (`signup4()`)
**Route**: `POST /signup4`

**Data Collected:**
- Goal type:
  - Non-gym users: `weight_lose`, `weight_maintain`, `weight_gain`
  - Gym users: Above + `cut`, `bulk`

**Process:**
1. Validates goal based on gym status
2. **Calculates calorie gap** based on goal:
   ```
   weight_lose: -500 calories
   weight_maintain: 0 calories
   weight_gain: +500 calories
   bulk: +250 calories
   cut: -250 calories
   ```
3. **Calculates goal calories**: `goal_calories = current_calories + calories_gap`
4. **Calculates macronutrient goals**:
   - **Gym users**:
     - Protein: 1.5g per kg body weight
     - Fat: 25% of goal calories (÷ 9 to convert to grams)
     - Carbs: Remaining calories after protein and fat
   - **Non-gym users**:
     - Protein: 0.8g per kg body weight
     - Fat: 30% of goal calories (÷ 9 to convert to grams)
     - Carbs: Remaining calories after protein and fat
5. Formula for carbs: `(goal_calories - (protein × 4) - (fat × 9)) / 4`

**Database Action:**
- Updates `users` table: `goal`, `calories_gap`, `goal_calories`, `goal_protein`, `goal_fat`, `goal_carbs`

#### 1.3 Authentication Middleware
**File**: `app/Http/Middleware/ValidUser.php`

**How it works:**
1. Registered as `isUserValid` in `bootstrap/app.php`
2. Checks if user is authenticated using `Auth::check()`
3. If authenticated: allows request to proceed
4. If not authenticated: redirects to login page

**Usage:**
- Applied to all protected routes via route middleware group

---

## Module 2: Dashboard Module

### Overview
The dashboard provides a comprehensive overview of the user's daily nutrition progress, workout plans, and quick access to main features.

### Components

#### 2.1 Main Dashboard (`dashboard()`)
**File**: `app/Http/Controllers/DashboardController.php`
**Route**: `GET /`

**Data Processing:**
1. **Retrieves user profile** from database
2. **Calculates today's nutrition stats**:
   - Queries `meal_logs` table for today's date
   - Sums: `total_calories`, `total_protein`
   - Calculates remaining calories: `goal_calories - consumed_calories`
   - Calculates percentage: `(consumed / goal) × 100` (capped at 100%)
3. **Retrieves latest workout plan**:
   - Fetches most recent `workout_plans` entry for user
   - Decodes JSON plan data
   - Extracts: plan ID, type, intensity, total workout days
4. **Passes data to view**:
   - User profile
   - Calorie metrics (consumed, goal, remaining, percentage)
   - Protein metrics (consumed, goal, percentage)
   - Workout plan summary

**View**: `resources/views/dashboard.blade.php`

#### 2.2 Nutrition Carousel API (`getNutritionCarousel()`)
**Route**: `GET /api/nutrition-carousel`

**Purpose**: Provides nutrition data for the last 7 days for a carousel display

**Process:**
1. Gets authenticated user ID
2. Loops through last 7 days (today - 6 days to today)
3. For each day, calls `getDailyStats()` helper method
4. Formats data with:
   - Date (Y-m-d format)
   - Formatted date (e.g., "Dec 20")
   - Complete nutrition stats (consumed + goals)
5. Returns JSON response with carousel data

**Response Format:**
```json
{
  "success": true,
  "carouselDays": [
    {
      "date": "2025-12-20",
      "dateFormatted": "Dec 20",
      "stats": {
        "consumed": { "calories": 1800, "protein": 120, "carbs": 200, "fats": 60 },
        "goals": { "calories": 2000, "protein": 150, "carbs": 250, "fats": 65 }
      }
    }
  ],
  "today": "2025-12-21"
}
```

#### 2.3 Daily Stats Helper (`getDailyStats()`)
**Method**: Private helper method in `DashboardController`

**Process:**
1. Queries `meal_logs` for specific user and date
2. Aggregates nutrition data:
   - `SUM(total_calories)` as `total_calories`
   - `SUM(total_protein)` as `total_protein`
   - `SUM(total_carbs)` as `total_carbs`
   - `SUM(total_fats)` as `total_fats`
3. Retrieves user goals from `users` table
4. Returns structured array with:
   - `consumed`: Actual values from meal logs
   - `goals`: Target values from user profile

---

## Module 3: Meal Logging & Nutrition Tracking

### Overview
This module enables users to search for foods, log meals, track daily nutrition, and view detailed reports. It integrates with the USDA FoodData Central API for comprehensive food database access.

### Components

#### 3.1 USDA Food Service
**File**: `app/Services/UsdaFoodService.php`

**Purpose**: Handles all interactions with USDA FoodData Central API and local food caching.

##### Key Methods:

**`searchFoods($query, $pageNumber, $pageSize)`**
- Searches USDA API for foods matching query
- Parameters:
  - `query`: Search term (minimum 2 characters)
  - `pageNumber`: Page number for pagination
  - `pageSize`: Results per page (default 50)
- API Endpoint: `GET /foods/search`
- Returns: Array with `foods` array and `totalHits` count
- Error Handling: Returns empty array on failure, logs errors

**`getFoodDetails($fdcId)`**
- Fetches detailed nutrition information for a specific food
- Parameters:
  - `fdcId`: USDA FoodData Central ID
- API Endpoint: `GET /food/{fdcId}`
- Requests specific nutrients: Protein (203), Fat (204), Carbs (205), Energy (208, 1008), Sugar (269)
- Returns: Full food object with nutrition data

**`normalizeFoodData($usdaFood)`**
- Converts USDA API response to application format
- Extracts nutrients from `foodNutrients` array:
  - Energy: Uses nutrient ID 1008 (preferred) or 208 (fallback)
  - Protein: Uses 1003 or 203
  - Carbs: Uses 1005 or 205
  - Fats: Uses 1004 or 204
- Extracts portion data from `foodPortions` array:
  - Creates array with `modifier`, `amount`, `gramWeight`, `portionDescription`
- Generates default portions if API doesn't provide them
- Returns normalized array ready for database insertion

**`cacheFood($usdaFood)`**
- Stores food data in local `foods` table
- Checks if food already exists by `fdc_id`
- If exists: Updates record with latest data
- If new: Inserts new record
- Returns: Cached food object

**`getCachedFood($fdcId)`**
- Retrieves food from local cache
- Returns: Food object or null

**`searchCachedFoods($query, $limit)`**
- Searches local food cache by name
- Uses `LIKE` query with `%query%` pattern
- Orders by `times_logged` (popularity) descending
- Returns: Array of matching foods

**`generateDefaultPortions($foodName)`**
- Creates default portion sizes for foods without API portion data
- Contains predefined portions for common foods:
  - Fruits: banana, apple, orange (small/medium/large)
  - Eggs: small/medium/large/extra large
  - Chicken: 1oz, 3oz, 4oz
  - Rice: 1 cup cooked, 1/2 cup cooked
  - Bread: 1 slice, 2 slices
- Falls back to generic portions (100g, 50g, 200g) if no match

#### 3.2 Meal Controller
**File**: `app/Http/Controllers/MealController.php`

##### 3.2.1 Main Meal Logger Page (`index()`)
**Route**: `GET /meals`

**Process:**
1. Gets authenticated user ID
2. Retrieves date from query parameter (defaults to today)
3. Calls `getDailyStats()` to get nutrition summary
4. Queries `meal_logs` for the date, grouped by `meal_type`
5. Retrieves user goals
6. Passes data to view: `dailyStats`, `mealLogs`, `user`, `date`

**View**: `resources/views/meals/index.blade.php`

##### 3.2.2 Food Search (`searchFoods()`)
**Route**: `POST /meals/search`

**Process:**
1. Validates query (minimum 2 characters)
2. **Searches local cache first** using `searchCachedFoods()`
3. **Searches USDA API** using `searchFoods()`
4. **Combines results**:
   - Adds cached foods first (with `cached: true` flag)
   - Adds API results that aren't already in cache
   - Formats each result with: `fdc_id`, `name`, `calories`, `protein`, `cached` flag
5. Returns JSON response with foods array and total hits

**Response Format:**
```json
{
  "foods": [
    {
      "fdc_id": 123456,
      "name": "Chicken breast",
      "calories": 165,
      "protein": 31,
      "cached": true
    }
  ],
  "totalHits": 150
}
```

##### 3.2.3 Get Food Details (`getFoodDetails()`)
**Route**: `GET /meals/food/{fdcId}`

**Process:**
1. **Checks local cache first** using `getCachedFood()`
2. If cached:
   - Decodes portions JSON if stored as string
   - Generates default portions if none exist
   - Returns cached food with portions
3. If not cached:
   - Fetches from USDA API using `getFoodDetails()`
   - Caches food using `cacheFood()`
   - Decodes/generates portions
   - Returns food data
4. Returns JSON response with food object and `cached` flag

**Response Format:**
```json
{
  "success": true,
  "food": {
    "id": 1,
    "fdc_id": 123456,
    "name": "Chicken breast",
    "calories_per_serving": 165,
    "protein_per_serving": 31,
    "carbs_per_serving": 0,
    "fats_per_serving": 3.6,
    "serving_size": "100g",
    "serving_weight_grams": 100,
    "portions": [
      {
        "portionDescription": "3 oz",
        "gramWeight": 85,
        "modifier": "3 oz",
        "amount": 1
      }
    ]
  },
  "cached": false
}
```

##### 3.2.4 Log Meal (`logMeal()`)
**Route**: `POST /meals/log`

**Validation:**
- `fdc_id` OR `food_id`: Required (one or the other)
- `meal_type`: Required, must be: `breakfast`, `lunch`, `dinner`, `snacks`
- `date`: Required, must be valid date
- `quantity`: Required, numeric, minimum 0.01
- `serving_size`: Optional string

**Process:**
1. Gets authenticated user ID
2. **Retrieves food data**:
   - If `food_id` provided: Gets from `foods` table
   - If `fdc_id` provided: Checks cache, fetches from API if needed, caches result
3. **Calculates nutrition totals**:
   ```
   total_calories = calories_per_serving × quantity
   total_protein = protein_per_serving × quantity
   total_carbs = carbs_per_serving × quantity
   total_fats = fats_per_serving × quantity
   ```
4. **Inserts meal log** into `meal_logs` table with:
   - User ID, food ID, food name (denormalized), FDC ID
   - Meal type, date, quantity, serving size
   - Calculated totals for all macronutrients
5. **Increments food usage counter** (`times_logged`) for popularity tracking
6. **Recalculates daily stats** for the date
7. Returns JSON response with success status and updated daily stats

**Response Format:**
```json
{
  "success": true,
  "message": "Food logged successfully",
  "meal_log_id": 123,
  "daily_stats": {
    "consumed": { "calories": 1800, "protein": 120, "carbs": 200, "fats": 60 },
    "goals": { "calories": 2000, "protein": 150, "carbs": 250, "fats": 65 },
    "remaining": { "calories": 200, "protein": 30, "carbs": 50, "fats": 5 }
  }
}
```

##### 3.2.5 Add Custom Food (`addCustomFood()`)
**Route**: `POST /meals/custom-food`

**Validation:**
- `name`: Required, max 255 characters
- `serving_size`: Required string
- `serving_weight_grams`: Optional numeric
- `calories_per_serving`: Required, numeric, minimum 0
- `protein_per_serving`: Required, numeric, minimum 0
- `carbs_per_serving`: Required, numeric, minimum 0
- `fats_per_serving`: Required, numeric, minimum 0

**Process:**
1. Gets authenticated user ID
2. **Generates custom FDC ID**:
   - Uses negative numbers to avoid conflicts with USDA IDs
   - Finds minimum existing custom FDC ID
   - Subtracts 1 for new ID
3. **Inserts custom food** into `foods` table:
   - Sets `is_custom = true`
   - Sets `api_source = 'custom'`
   - Sets `created_by_user_id` to current user
4. Returns JSON response with created food object

##### 3.2.6 Daily Stats API (`getDailyStats()`)
**Route**: `GET /meals/stats/{date?}`

**Process:**
1. Gets user ID (from auth or parameter)
2. Gets date (from parameter or defaults to today)
3. Queries `meal_logs` for the date
4. Aggregates: `SUM(total_calories)`, `SUM(total_protein)`, `SUM(total_carbs)`, `SUM(total_fats)`
5. Retrieves user goals from `users` table
6. Calculates remaining values: `goal - consumed`
7. Returns JSON with `consumed`, `goals`, and `remaining` arrays

##### 3.2.7 Delete Meal Log (`deleteMealLog()`)
**Route**: `DELETE /meals/log/{id}`

**Process:**
1. Gets authenticated user ID
2. Verifies meal log belongs to user
3. Retrieves meal log date before deletion
4. Deletes meal log entry
5. Recalculates daily stats for the date
6. Returns JSON with success status and updated stats

##### 3.2.8 Update Meal Log (`updateMealLog()`)
**Route**: `PUT /meals/log/{id}`

**Validation:**
- `quantity`: Required, numeric, minimum 0.01

**Process:**
1. Gets authenticated user ID
2. Verifies meal log belongs to user
3. Retrieves associated food data from `foods` table
4. **Recalculates nutrition totals** based on new quantity
5. Updates meal log with new quantity and recalculated totals
6. Recalculates daily stats
7. Returns JSON with success status and updated stats

##### 3.2.9 Show Individual Meal (`showMeal()`)
**Route**: `GET /meals/{mealType}/{date?}`

**Process:**
1. Validates meal type: `breakfast`, `lunch`, `dinner`, `snacks`
2. Gets date (from parameter or defaults to today)
3. Queries `meal_logs` for specific meal type and date
4. Calculates meal total calories
5. Gets meal info (label and icon) for display
6. Passes data to view: `mealType`, `mealLogs`, `mealTotal`, `mealInfo`, `date`

**View**: `resources/views/meals/show.blade.php`

##### 3.2.10 Meal Report (`report()`)
**Route**: `GET /meals/report/{date?}`

**Process:**
1. Validates and formats date
2. Gets daily stats using `getDailyStats()`
3. Queries meal logs grouped by meal type
4. **Calculates meal type statistics**:
   - For each meal type (breakfast, lunch, dinner, snacks):
     - Sums calories, protein, carbs, fats
     - Counts number of foods
     - Lists all foods with details
5. **Calculates percentages**:
   - Calories: `(consumed / goal) × 100`
   - Protein: `(consumed / goal) × 100`
6. Calculates previous and next dates for navigation
7. Passes comprehensive data to view

**View**: `resources/views/meals/report.blade.php`

---

## Module 4: Workout Generation System

### Overview
This module generates personalized workout plans based on user profile, preferences, and fitness goals. It integrates with ExerciseDB API to fetch exercise data and creates customized workout routines.

### Components

#### 4.1 Workout Generation Service
**File**: `app/Services/WorkoutGenerationService.php`

**Purpose**: Core logic for generating personalized workout plans.

##### Key Methods:

**`generateWorkoutPlan($userDetail, $workoutType, $intensity)`**
- Main entry point for workout generation
- Parameters:
  - `$userDetail`: User object with all profile data
  - `$workoutType`: `'ppl'` (Push/Pull/Legs) or `'bro'` (Bro Split)
  - `$intensity`: `'low'`, `'medium'`, or `'high'`
- Normalizes intensity to lowercase
- Routes to appropriate generator:
  - PPL: Calls `generatePPLWorkout()`
  - Bro Split: Calls `generateBroSplitWorkout()`
- Returns: Array with workout plan structure

**`generatePPLWorkout($userDetail, $intensity, $goesToGym)`**
- Creates 3-day workout plan:
  - Day 1: Push Day (chest, shoulders, triceps)
  - Day 2: Pull Day (back, biceps)
  - Day 3: Legs Day (legs, calves, abs)
- For each day:
  - Calls `createWorkoutDay()` with appropriate body parts
- Returns plan structure with metadata

**`generateBroSplitWorkout($userDetail, $intensity, $goesToGym)`**
- Creates 5-day workout plan:
  - Day 1: Chest Day
  - Day 2: Back Day
  - Day 3: Shoulders Day
  - Day 4: Arms Day
  - Day 5: Legs Day
- Similar structure to PPL but with more focused days

**`createWorkoutDay($dayName, $bodyParts, $userDetail, $intensity, $dayNumber)`**
- Core method that creates a single workout day
- Process:
  1. **Calculates exercise count** based on user profile
  2. **Fetches exercises** for each body part from ExerciseDB API
  3. **Filters exercises** based on equipment availability (if home workout)
  4. **Selects exercises** based on user profile and intensity
  5. **Formats exercises** with sets, reps, rest times
  6. **Calculates estimated duration**
- Returns: Day structure with exercises array

**`calculateExerciseCount($userDetail, $intensity)`**
- Determines how many exercises per day
- Base count: 6 exercises
- **Activity level multiplier**:
  - `basic`: 0.8 (fewer exercises)
  - `moderate`: 1.0 (standard)
  - `high`: 1.2 (more exercises)
- **Age adjustment**:
  - Over 50: Reduces by 10%
  - Under 20: Increases by 10%
- **Intensity adjustment**:
  - `low`: -1 exercise
  - `medium`: 0
  - `high`: +1 exercise
- Returns: Integer between 4-10 exercises

**`fetchExercisesByBodyPart($bodyPart, $goesToGym)`**
- Fetches exercises from ExerciseDB API
- API Endpoint: `GET /exercises/bodyPart/{bodyPart}`
- Parameters:
  - `limit`: 100 (to get more options for filtering)
- **Home workout filtering**:
  - If user doesn't go to gym, filters exercises by equipment
  - Allowed equipment: bodyweight, dumbbells, resistance bands, medicine ball, stability ball, kettlebells, pull-up bar, none
  - Uses flexible matching (handles variations like "body weight", "bodyweight", etc.)
  - Fallback: If filtering removes all exercises, uses unfiltered list
- Returns: Array of exercise objects

**`filterHomeExercises($exercises)`**
- Filters exercises for home workouts
- Checks equipment field against allowed home equipment list
- Handles variations in equipment naming
- Returns: Filtered array

**`selectExercises($exercises, $bodyPart, $userDetail, $intensity)`**
- Selects appropriate exercises from available pool
- Process:
  1. Shuffles exercises for variety
  2. **Prioritizes compound movements**:
     - Keywords: bench, squat, deadlift, press, row, pull, dip, lunge
     - Compound exercises sorted to top
  3. **Gets exercise count** for body part based on intensity
  4. Returns top N exercises
- Returns: Selected exercises array

**`getExerciseCountForBodyPart($bodyPart, $intensity)`**
- Determines how many exercises per body part
- Base counts:
  - Chest: 3
  - Back: 3
  - Shoulders: 2
  - Upper arms: 2
  - Lower arms: 1
  - Upper legs: 4
  - Lower legs: 2
  - Waist: 2
- Intensity adjustment:
  - `high`: +1 exercise
  - `low`: -1 exercise (minimum 1)
- Returns: Integer count

**`calculateSetsReps($intensity, $exercise, $userDetail)`**
- Calculates sets and reps for each exercise
- **Base profiles by intensity**:
  - `low`:
    - Sets: 3
    - Reps: 12-15 (compound) or 15-20 (isolation)
  - `medium`:
    - Sets: 3
    - Reps: 10-12 (compound) or 12-15 (isolation)
  - `high`:
    - Sets: 4
    - Reps: 6-10 (compound) or 10-12 (isolation)
- **Activity level adjustment**:
  - `basic`: -1 set (minimum 2)
  - `high`: +1 set (if intensity is high)
- **Age adjustment**:
  - Over 50: Caps sets at 3 (for joint health)
- Returns: Array with `sets` and `reps` keys

**`isCompoundExercise($exerciseName)`**
- Determines if exercise is compound movement
- Checks for keywords: bench, squat, deadlift, press, row, pull-up, dip, lunge
- Returns: Boolean

**`calculateRestTime($intensity, $userDetail)`**
- Calculates rest time between sets in seconds
- Base rest times:
  - `low`: 60 seconds
  - `medium`: 90 seconds
  - `high`: 120 seconds
- **Activity level adjustment**:
  - `basic`: +30 seconds (more rest for beginners)
  - `high`: -15 seconds (less rest for advanced)
- **Age adjustment**:
  - Over 50: +15 seconds (more recovery time)
- Returns: Integer (seconds)

**`calculateWorkoutDuration($exercises)`**
- Estimates total workout time
- Formula per exercise:
  - `(sets × 2 minutes) + ((sets - 1) × (rest_seconds / 60))`
- Adds 10 minutes for warm-up and cool-down
- Returns: Integer (total minutes)

#### 4.2 Workout Controller
**File**: `app/Http/Controllers/WorkoutController.php`

##### 4.2.1 Workout Type Selection (`workoutTypePage()`)
**Route**: `GET /personalized-workout/gym/typePage`

**Process:**
1. Gets user's gym status
2. Passes `goesToGym` flag to view
3. View displays appropriate workout type options

**View**: `resources/views/myworkout/gym/workout-type.blade.php`

##### 4.2.2 Intensity Selection (`workoutIntensityPage()`)
**Route**: `GET /personalized-workout/intensity/{type}`

**Process:**
1. Stores workout type in session: `session(['workout_type' => $type])`
2. **Sets recommended intensity**:
   - Gym users: `'Medium'`
   - Non-gym users: `'low'`
3. Stores recommendation in session
4. Displays intensity selection page

**View**: `resources/views/myworkout/gym/workout-intensity.blade.php`

##### 4.2.3 Generate Workout Page (`createWorkoutPlanPage()`)
**Route**: `GET /personalized-workout/gym/workouts/{intensity}`

**Process:**
1. Stores intensity in session
2. Retrieves user details
3. Creates workout data object from session
4. Displays workout generation page with user details

**View**: `resources/views/myworkout/gym/generate-workout.blade.php`

##### 4.2.4 Generate Workout (`generateWorkout()`)
**Route**: `POST /personalized-workout/gym/generate`

**Process:**
1. Retrieves workout type and intensity from session
2. Validates session data exists
3. Retrieves user details from database
4. **Calls WorkoutGenerationService**:
   ```php
   $workoutService = new WorkoutGenerationService();
   $workoutPlan = $workoutService->generateWorkoutPlan($userDetail, $workoutType, $intensity);
   ```
5. **Stores workout plan** in database:
   - Table: `workout_plans`
   - Fields: `user_id`, `type`, `intensity`, `plan` (JSON)
6. Clears session data
7. Redirects to generated workout view

**Error Handling:**
- Logs errors with full trace
- Returns user-friendly error messages
- Redirects back on failure

##### 4.2.5 Show Generated Workout (`showGeneratedWorkout()`)
**Route**: `GET /personalized-workout/my-plan/{planId}`

**Process:**
1. Retrieves workout plan from database
2. Verifies plan belongs to authenticated user
3. Decodes JSON plan data
4. Retrieves user details
5. Passes data to view

**View**: `resources/views/myworkout/gym/generated-workout.blade.php`

##### 4.2.6 My Workouts (`workouts()`)
**Route**: `GET /personalized-workout/my-workouts/{day?}`

**Process:**
1. Contains hardcoded PPL workout data (Push, Pull, Legs)
2. If day parameter provided, filters to that day's workout
3. Passes workout data and day name to view

**View**: `resources/views/myworkout/workouts/workouts.blade.php`

**Note**: This appears to be a static workout display, separate from generated plans.

---

## Module 5: Exercise Database Integration

### Overview
This module provides access to a comprehensive exercise database through the ExerciseDB API, allowing users to browse exercises by muscle group and view detailed exercise information.

### Components

#### 5.1 Muscle Groups Page (`muscleGroupsPage()`)
**Route**: `GET /all-workouts/muscle-groups`

**Process:**
1. Displays page with muscle group options
2. No API calls at this stage

**View**: `resources/views/all-workouts/muscle-group-page-1.blade.php`

#### 5.2 Single Muscle Group (`singleMusclePage()`)
**Route**: `GET /all-workouts/exercises/{bodyPart}`

**Process:**
1. **Maps URL-friendly names to API names**:
   - `chest` → `chest`
   - `back` → `back`
   - `shoulders` → `shoulders`
   - `upper-arms` → `upper arms`
   - `lower-arms` → `lower arms`
   - `upper-legs` → `upper legs`
   - `lower-legs` → `lower legs`
   - `waist` → `waist`
   - `cardio` → `cardio`
   - `neck` → `neck`
2. **Gets display name** for page title
3. **Calls ExerciseDB API**:
   - Endpoint: `GET /exercises/bodyPart/{apiBodyPart}`
   - Headers:
     - `X-RapidAPI-Key`: From `EXCERCISE_API_KEY` env
     - `X-RapidAPI-Host`: From `EXCERCISE_API_Host` env
   - Parameters: `limit: 50`
4. **Error handling**: Returns empty array on failure
5. Passes exercises, display name, and body part slug to view

**View**: `resources/views/all-workouts/single-muscle.blade.php`

#### 5.3 Exercise Detail (`exerciseDetailPage()`)
**Route**: `GET /all-workouts/exercise-detail/{exerciseId}`

**Process:**
1. **Calls ExerciseDB API**:
   - Endpoint: `GET /exercises/exercise/{exerciseId}`
   - Headers: Same as above
2. **Formats exercise ID** with leading zeros (e.g., `9` → `0009`)
3. **Generates image URL** using proxy route:
   - Route: `exerciseImage`
   - Format: `/all-workouts/exercise-image/{formattedId}`
4. **Maps body part back to URL format** for navigation
5. **Error handling**: Sets exercise to null on failure
6. Passes exercise data, body part slug, and image URL to view

**View**: `resources/views/all-workouts/muscle-detail.blade.php`

#### 5.4 Exercise Image Proxy (`exerciseImage()`)
**Route**: `GET /all-workouts/exercise-image/{exerciseId}`

**Purpose**: Proxies exercise images from ExerciseDB API with proper headers

**Process:**
1. Formats exercise ID with leading zeros
2. **Calls ExerciseDB API**:
   - Endpoint: `GET /image`
   - Parameters:
     - `resolution: 180`
     - `exerciseId: {formattedId}`
3. **Returns image** with proper headers:
   - `Content-Type`: From API response
   - `Cache-Control`: `public, max-age=3600` (1 hour cache)
4. **Error handling**: Returns 404 if image not found

**Why Proxy?**
- ExerciseDB API requires specific headers that browsers may not send
- Proxy ensures proper authentication and caching

---

## Database Schema

### Tables

#### `users`
**Purpose**: Stores user accounts and profile information

**Columns:**
- `id`: Primary key
- `name`: User's name
- `email`: Unique email address
- `password`: Hashed password
- `gender`: Enum (`male`, `female`)
- `age`: Integer (1-120)
- `height`: Decimal (cm)
- `weight`: Decimal (kg)
- `activity_level`: Enum (`basic`, `moderate`, `high`)
- `goal`: Enum (`weight_lose`, `weight_gain`, `weight_maintain`, `cut`, `bulk`)
- `goes_to_gym`: Boolean
- `gym_experience`: String (nullable)
- `current_calories`: Integer (TDEE)
- `goal_calories`: Integer
- `goal_protein`: Integer (grams)
- `goal_carbs`: Integer (grams)
- `goal_fat`: Integer (grams)
- `bmr`: Integer (Basal Metabolic Rate)
- `calories_gap`: Integer (calorie adjustment for goal)
- `timestamps`: `created_at`, `updated_at`

**Indexes:**
- `email`: Unique index

#### `foods`
**Purpose**: Caches food data from USDA API and custom foods

**Columns:**
- `id`: Primary key
- `fdc_id`: Unique USDA FoodData Central ID (indexed)
- `name`: Food name (indexed)
- `serving_size`: String (default: "100g")
- `serving_weight_grams`: Decimal (default: 100)
- `calories_per_serving`: Decimal
- `protein_per_serving`: Decimal
- `carbs_per_serving`: Decimal
- `fats_per_serving`: Decimal
- `portions`: JSON (array of portion options)
- `api_source`: String (default: "usda")
- `is_custom`: Boolean (default: false)
- `created_by_user_id`: Foreign key to users (nullable)
- `times_logged`: Integer (popularity counter, default: 0)
- `cached_at`: Timestamp (when cached from API)
- `timestamps`: `created_at`, `updated_at`

**Indexes:**
- `fdc_id`: Unique index
- `name`: Index
- `is_custom`, `created_by_user_id`: Composite index

**Relationships:**
- `created_by_user_id` → `users.id` (nullable, set null on delete)

#### `meal_logs`
**Purpose**: Stores individual meal entries

**Columns:**
- `id`: Primary key
- `user_id`: Foreign key to users
- `food_id`: Foreign key to foods (nullable)
- `food_name`: String (denormalized for historical data)
- `fdc_id`: Integer (denormalized USDA ID)
- `meal_type`: Enum (`breakfast`, `lunch`, `dinner`, `snacks`)
- `date`: Date
- `quantity`: Decimal (number of servings)
- `serving_size`: String
- `serving_weight_grams`: Decimal
- `total_calories`: Decimal (calculated)
- `total_protein`: Decimal (calculated)
- `total_carbs`: Decimal (calculated)
- `total_fats`: Decimal (calculated)
- `timestamps`: `created_at`, `updated_at`

**Indexes:**
- `user_id`, `date`: Composite index
- `user_id`, `date`, `meal_type`: Composite index
- `food_id`: Index

**Relationships:**
- `user_id` → `users.id` (cascade delete)
- `food_id` → `foods.id` (set null on delete)

#### `workout_plans`
**Purpose**: Stores generated workout plans

**Columns:**
- `id`: Primary key
- `user_id`: Foreign key to users
- `type`: String (`ppl`, `bro`, `home`)
- `intensity`: String (`low`, `medium`, `high`)
- `plan`: JSON (complete workout plan structure)
- `timestamps`: `created_at`, `updated_at`

**Relationships:**
- `user_id` → `users.id` (cascade delete)

**Plan JSON Structure:**
```json
{
  "days": [
    {
      "day": 1,
      "day_name": "Push Day",
      "exercises": [
        {
          "exercise_id": "0001",
          "name": "Bench Press",
          "sets": 3,
          "reps": "10-12",
          "bodyPart": "chest",
          "target": "pectorals",
          "equipment": "barbell",
          "rest_seconds": 90,
          "gifUrl": "https://..."
        }
      ],
      "estimated_duration_minutes": 45
    }
  ],
  "total_workouts": 3,
  "generated_at": "2025-12-21",
  "workout_type": "ppl",
  "intensity": "medium"
}
```

---

## API Integrations

### 1. USDA FoodData Central API

**Base URL**: `https://api.nal.usda.gov/fdc/v1/`

**Configuration**: `config/services.php`
```php
'usda' => [
    'api_key' => env('USDA_API_KEY'),
    'base_url' => 'https://api.nal.usda.gov/fdc/v1/',
    'timeout' => 30,
]
```

**Endpoints Used:**
- `GET /foods/search`: Search foods by query
- `GET /food/{fdcId}`: Get detailed food information

**Authentication**: API key via query parameter

**Service Class**: `App\Services\UsdaFoodService`

### 2. ExerciseDB API (via RapidAPI)

**Base URL**: `https://exercisedb.p.rapidapi.com`

**Configuration**: Environment variables
- `EXCERCISE_API_KEY`: RapidAPI key
- `EXCERCISE_API_Host`: `exercisedb.p.rapidapi.com`

**Endpoints Used:**
- `GET /exercises/bodyPart/{bodyPart}`: Get exercises by body part
- `GET /exercises/exercise/{exerciseId}`: Get single exercise details
- `GET /image`: Get exercise GIF image

**Authentication**: Headers
- `X-RapidAPI-Key`: API key
- `X-RapidAPI-Host`: Host name

**Service Class**: `App\Services\WorkoutGenerationService` (for workout generation)
**Controller**: `App\Http\Controllers\WorkoutController` (for exercise browsing)

---

## Configuration

### Environment Variables

**Required:**
```env
# Application
APP_NAME="Pulse-X"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# USDA API
USDA_API_KEY=your_usda_api_key_here

# ExerciseDB API (RapidAPI)
EXCERCISE_API_KEY=your_rapidapi_key_here
EXCERCISE_API_Host=exercisedb.p.rapidapi.com
```

### Service Configuration

**File**: `config/services.php`

USDA API configuration is defined here. ExerciseDB API uses environment variables directly.

### Middleware Registration

**File**: `bootstrap/app.php` (or `app/Http/Kernel.php` in older Laravel versions)

The `ValidUser` middleware should be registered as `isUserValid` and applied to protected routes.

---

## Key Algorithms & Calculations

### 1. BMR Calculation (Mifflin-St Jeor Equation)
```
BMR = (10 × weight_kg) + (6.25 × height_cm) - (5 × age_years) + gender_factor
gender_factor = 5 (male) or -161 (female)
```

### 2. TDEE Calculation
```
TDEE = BMR × activity_factor
activity_factor = 1.37 (basic) | 1.55 (moderate) | 1.7 (high)
```

### 3. Goal Calories
```
goal_calories = current_calories (TDEE) + calories_gap
calories_gap = -500 (lose) | 0 (maintain) | +500 (gain) | +250 (bulk) | -250 (cut)
```

### 4. Macronutrient Goals

**Gym Users:**
```
protein_goal = weight_kg × 1.5
fat_goal = (goal_calories × 0.25) / 9
carbs_goal = (goal_calories - (protein_goal × 4) - (fat_goal × 9)) / 4
```

**Non-Gym Users:**
```
protein_goal = weight_kg × 0.8
fat_goal = (goal_calories × 0.30) / 9
carbs_goal = (goal_calories - (protein_goal × 4) - (fat_goal × 9)) / 4
```

**Note**: 
- Protein: 4 calories per gram
- Carbs: 4 calories per gram
- Fat: 9 calories per gram

### 5. Exercise Count Calculation
```
base_count = 6
multiplier = activity_multiplier[activity_level] × age_adjustment
exercise_count = (base_count × multiplier) + intensity_adjustment
exercise_count = clamp(exercise_count, 4, 10)
```

### 6. Workout Duration Estimation
```
duration_per_exercise = (sets × 2) + ((sets - 1) × (rest_seconds / 60))
total_duration = sum(duration_per_exercise) + 10 (warm-up/cool-down)
```

### 7. Nutrition Calculation for Meal Logging
```
total_calories = calories_per_serving × quantity
total_protein = protein_per_serving × quantity
total_carbs = carbs_per_serving × quantity
total_fats = fats_per_serving × quantity
```

---

## Data Flow Examples

### Example 1: Logging a Meal

1. User searches for "chicken breast"
2. Frontend calls `POST /meals/search` with query
3. Controller searches cache, then USDA API
4. User selects food and enters quantity
5. Frontend calls `POST /meals/log` with food data
6. Controller:
   - Retrieves/caches food data
   - Calculates nutrition totals
   - Inserts meal log
   - Updates food popularity counter
7. Returns updated daily stats
8. Frontend updates UI with new totals

### Example 2: Generating a Workout Plan

1. User selects workout type (PPL) and intensity (Medium)
2. Frontend calls `POST /personalized-workout/gym/generate`
3. Controller:
   - Retrieves user profile
   - Calls `WorkoutGenerationService::generateWorkoutPlan()`
4. Service:
   - Determines exercise count based on profile
   - Fetches exercises from ExerciseDB API for each body part
   - Filters exercises (if home workout)
   - Selects and prioritizes exercises
   - Calculates sets, reps, rest times
   - Formats workout structure
5. Controller stores plan in database as JSON
6. Redirects to workout display page
7. View decodes and displays workout plan

### Example 3: Daily Nutrition Tracking

1. User views dashboard (`GET /`)
2. Controller:
   - Queries `meal_logs` for today
   - Aggregates nutrition totals
   - Retrieves user goals
   - Calculates percentages and remaining values
3. View displays:
   - Progress bars for calories and protein
   - Remaining calories
   - Latest workout plan summary
4. Frontend may call `/api/nutrition-carousel` for 7-day history
5. Controller loops through last 7 days, calculates stats for each
6. Returns JSON for carousel display

### Example 4: User Registration Flow

1. User enters basic info (name, email, password)
2. System creates account and logs user in
3. User enters physical attributes (gender, age, height, weight)
4. System calculates BMR
5. User selects activity level and gym status
6. System calculates TDEE
7. User selects fitness goal
8. System calculates:
   - Goal calories
   - Macronutrient goals (protein, carbs, fats)
9. User redirected to dashboard

---

## Error Handling

### USDA API Errors
- Logged with `Log::error()`
- Returns empty arrays/objects
- Application continues with cached data when possible

### ExerciseDB API Errors
- Logged with `Log::warning()` or `Log::error()`
- Returns empty arrays
- Fallback to unfiltered exercises if filtering fails
- 404 responses for missing exercises/images

### Database Errors
- Caught in try-catch blocks
- User-friendly error messages
- Redirects with error flash messages

### Validation Errors
- Laravel validation rules applied
- Returns validation errors to user
- Prevents invalid data from being stored

---

## Performance Optimizations

### Food Caching
- All USDA foods are cached locally after first fetch
- Reduces API calls significantly
- Popular foods (high `times_logged`) appear first in search

### Database Indexing
- Composite indexes on `meal_logs` for efficient date/user queries
- Indexed `fdc_id` for fast food lookups
- Indexed `name` for food search

### API Request Optimization
- Batch fetching with higher limits (100 exercises) for filtering
- Timeout settings (30 seconds) prevent hanging requests
- Caching reduces redundant API calls

### Query Optimization
- Uses Query Builder for efficient aggregations
- Groups meal logs by meal type in application (not database)
- Limits exercise fetching to necessary body parts only

---

## Security Considerations

### Authentication
- Password hashing via Laravel's Hash facade
- Session-based authentication
- Middleware protection on all routes

### Data Validation
- Input validation on all user inputs
- SQL injection prevention via Query Builder/Eloquent
- CSRF protection on all forms

### User Data Isolation
- All queries filtered by `user_id`
- Meal logs and workout plans scoped to authenticated user
- Custom foods can be user-specific

### API Security
- API keys stored in environment variables
- Never exposed in client-side code
- Proper error handling prevents information leakage

---

## Future Enhancement Opportunities

1. **Meal Planning**: Weekly meal planning feature
2. **Progress Tracking**: Weight/measurement tracking over time
3. **Social Features**: Share workouts/meals with friends
4. **Mobile App**: React Native or Flutter mobile application
5. **AI Recommendations**: ML-based food and exercise recommendations
6. **Recipe Integration**: Recipe database with nutrition calculation
7. **Workout History**: Track completed workouts and progress
8. **Export Data**: CSV/PDF export of nutrition and workout data
9. **Water Intake Tracking**: Daily water consumption logging
10. **Sleep Tracking**: Integration with sleep tracking devices
11. **Meal Reminders**: Push notifications for meal times
12. **Barcode Scanner**: Scan barcodes for quick food logging

---

## Conclusion

Pulse-X is a comprehensive fitness and nutrition application with sophisticated algorithms for personalized recommendations. The modular architecture allows for easy maintenance and future enhancements. Each module is designed to work independently while integrating seamlessly with others to provide a cohesive user experience.

The application successfully integrates multiple external APIs, implements intelligent caching strategies, and provides personalized recommendations based on user profiles. The multi-step registration process ensures accurate goal setting, while the meal logging system provides comprehensive nutrition tracking with real-time updates.

---

## Additional Notes

### Code Quality
- Follows Laravel best practices
- Uses Query Builder for database operations
- Service classes separate business logic from controllers
- Proper error handling and logging throughout

### Testing Considerations
- Unit tests should cover calculation methods
- Integration tests for API interactions
- Feature tests for user workflows
- Mock external APIs in tests

### Deployment Considerations
- Ensure API keys are set in production environment
- Database migrations should be run
- Cache should be cleared after deployment
- Consider queue workers for heavy operations

---

**Document Version**: 1.0  
**Last Updated**: December 2025  
**Maintained By**: Development Team



