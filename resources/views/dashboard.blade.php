@extends('layout.main')
@section('content')

{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen pb-20">
    <div class="max-w-xl mx-auto px-5 py-8 text-white space-y-6">

        {{-- 1. Header Section --}}
        <header class="flex justify-between items-center p-2 rounded-2xl  bg-opacity-5">
            <div class="flex items-center relative">
                {{-- Profile Icon with Dropdown --}}
                <div class="relative">
                    <button onclick="toggleProfileDropdown()" 
                            class="p-2.5 rounded-full shadow-lg transition duration-150 hover:opacity-90 focus:outline-none cursor-pointer"
                            style="background-image: linear-gradient(to top left, #3498db, #2980b9);">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div id="profileDropdown" 
                         class="hidden absolute left-0 mt-2 w-48 rounded-lg shadow-2xl border border-white border-opacity-20 overflow-hidden z-50"
                         style="background: rgba(26, 26, 46, 0.95); backdrop-filter: blur(10px);">
                        <div class="py-1">
                            <a href="#" 
                               class="px-4 py-3 text-sm text-white hover:bg-white hover:bg-opacity-10 transition flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Profile</span>
                            </a>
                            <a href="{{ route('logoutUser') }}" 
                               class="px-4 py-3 text-sm text-white hover:bg-white hover:bg-opacity-10 transition flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm tracking-wider" style="color: rgba(255, 255, 255, 0.7);">Welcome Back,</p>
                    <h1 class="text-2xl font-bold tracking-wide text-[#c38b61]">{{ $user->name }}</h1>
                </div>
            </div>
        </header>

        {{-- 2. Daily Goal Card --}}
        <section class="p-5 rounded-[8px] shadow-2xl" 
                 style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.2));">
            <div class="space-y-5">
                {{-- Calories Section --}}
                <h2 class="text-lg font-bold">Daily Calories</h2>
                
                <div class="flex justify-between items-center">
                    <div class="flex space-x-6">
                        {{-- Consumed Calories --}}
                        <div>
                            <p class="text-xs font-light opacity-70">Consumed</p>
                            <p class="text-4xl font-bold">{{ number_format($consumedCalories) }}</p>
                        </div>
                        {{-- Protein --}}
                        <div>
                            <p class="text-xs font-light opacity-70">Protein</p>
                            <p class="text-2xl font-bold">{{ number_format($consumedProtein, 1) }}g</p>
                        </div>
                    </div>
                    {{-- Circular Progress Bar (Calories) --}}
                    <div class="relative w-[70px] h-[70px]">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            {{-- Background Arc --}}
                            <circle cx="50" cy="50" r="35" fill="none" class="stroke-current opacity-20" stroke-width="8"></circle>
                            {{-- Progress Arc --}}
                            @php
                                $circumference = 2 * pi() * 35;
                                $offset = $circumference - (($caloriesPercent / 100) * $circumference);
                            @endphp
                            <circle cx="50" cy="50" r="35" fill="none" stroke="#feb47b" stroke-width="8" 
                                    stroke-dasharray="{{ $circumference }}" 
                                    stroke-dashoffset="{{ $offset }}" 
                                    stroke-linecap="round"></circle>
                        </svg>
                        <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-sm font-bold">{{ number_format($caloriesPercent, 0) }}%</span>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium {{ $remainingCalories >= 0 ? 'text-[#feb47b]' : 'text-red-400' }}">
                        {{ $remainingCalories >= 0 ? 'Remaining: ' . number_format($remainingCalories) : 'Over by: ' . number_format(abs($remainingCalories)) }} kcal
                    </p>
                    <a href="{{ route('meals.report', ['date' => now()->format('Y-m-d')]) }}" 
                       class="text-xs px-3 py-1.5 rounded-full bg-[#feb47b] bg-opacity-20 text-[#feb47b] hover:bg-opacity-30 transition font-semibold">
                        View Detail
                    </a>
                </div>

                {{-- Calories Linear Progress Bar --}}
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between text-xs">
                        <span class="opacity-70">Calories Progress</span>
                        <span class="font-bold">{{ number_format($consumedCalories) }} / {{ number_format($goalCalories) }} kcal</span>
                    </div>
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full bg-[#feb47b]" style="width: {{ $caloriesPercent }}%;"></div>
                    </div>
                </div>

                {{-- Protein Linear Progress Bar --}}
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between text-xs">
                        <span class="opacity-70">Protein Progress</span>
                        <span class="font-bold">{{ number_format($consumedProtein, 1) }} / {{ number_format($goalProtein, 1) }}g</span>
                    </div>
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full bg-[#feb47b]" style="width: {{ $proteinPercent }}%;"></div>
                    </div>
                </div>
            </div>
        </section>
        
        <hr class="border-white border-opacity-10 my-6">

        {{-- Recommended Workout Program Card --}}
        @if($workoutPlan)
            {{-- Show existing workout plan --}}
            <a href="{{ route('showGeneratedWorkout', ['planId' => $workoutPlan['id']]) }}" class="relative">
                <div class="p-5 rounded-md border-2 cursor-pointer transition-all hover:scale-[1.02]"
                     style="background: linear-gradient(135deg, rgba(255, 182, 123, 0.1), rgba(255, 182, 123, 0.05)); border-color: rgba(255, 182, 123, 0.4);">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="w-5 h-5" fill="currentColor" style="color: #feb47b;" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs font-semibold uppercase tracking-wider" style="color: #feb47b;">Personalized</span>
                            </div>
                            <h3 class="text-lg font-bold mb-1">My Workout Plan</h3>
                            <p class="text-sm opacity-80 mb-3">
                                {{ ucfirst($workoutPlan['type']) }} Split - {{ ucfirst(strtolower($workoutPlan['intensity'])) }} Intensity
                            </p>
                            <div class="flex items-center space-x-4 text-xs">
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="opacity-70">{{ $workoutPlan['total_days'] }} Days</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span class="opacity-70">{{ ucfirst(strtolower($workoutPlan['intensity'])) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: linear-gradient(135deg, rgba(255, 182, 123, 0.3), rgba(255, 182, 123, 0.1));">
                                <svg class="w-6 h-6" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @else
            {{-- Show generate workout option --}}
            <a href="{{ route('workoutTypePage') }}" class="relative">
                <div class="p-5 rounded-md border-2 cursor-pointer transition-all hover:scale-[1.02]"
                     style="background: linear-gradient(135deg, rgba(255, 182, 123, 0.1), rgba(255, 182, 123, 0.05)); border-color: rgba(255, 182, 123, 0.4);">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="w-5 h-5" fill="currentColor" style="color: #feb47b;" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs font-semibold uppercase tracking-wider" style="color: #feb47b;">Personalized</span>
                            </div>
                            <h3 class="text-lg font-bold mb-1">Recommended Workout</h3>
                            <p class="text-sm opacity-80 mb-3">Create your personalized workout plan</p>
                            <div class="flex items-center space-x-4 text-xs">
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="opacity-70">Get Started</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: linear-gradient(135deg, rgba(255, 182, 123, 0.3), rgba(255, 182, 123, 0.1));">
                                <svg class="w-6 h-6" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endif

        <hr class="border-white border-opacity-10 my-6">

        {{-- Nutrition Breakdown Carousel --}}
        <section class="space-y-4" id="nutritionCarouselSection">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold">Nutrition Breakdown</h2>
                <span class="text-xs opacity-70">Last 7 days</span>
            </div>
            
            {{-- Loading State --}}
            <div id="carouselLoading" class="relative overflow-hidden rounded-xl p-12 flex items-center justify-center" 
                 style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.2)); min-height: 300px;">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#feb47b] mb-4"></div>
                    <p class="text-sm opacity-70">Loading nutrition data...</p>
                </div>
            </div>
            
            {{-- Carousel Container (Hidden initially) --}}
            <div id="carouselContainer" class="hidden relative overflow-hidden rounded-xl" 
                 style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.2));">
                <div id="nutritionCarousel" class="flex transition-transform duration-300 ease-in-out" style="transform: translateX(0);">
                    <!-- Carousel slides will be inserted here via JavaScript -->
                </div>
                
                {{-- Navigation Arrows --}}
                <button id="prevBtn" onclick="slideCarousel(-1)" 
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black bg-opacity-30 hover:bg-opacity-50 flex items-center justify-center transition z-10">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button id="nextBtn" onclick="slideCarousel(1)" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black bg-opacity-30 hover:bg-opacity-50 flex items-center justify-center transition z-10">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                
                {{-- Pagination Dots --}}
                <div id="paginationDots" class="flex justify-center gap-2 pb-4 pt-2">
                    <!-- Dots will be inserted here via JavaScript -->
                </div>
            </div>
        </section>

        <hr class="border-white border-opacity-10 my-6">

        {{-- 3. Quick Actions Grid --}}
        <section class="space-y-4">
            <h2 class="text-xl font-bold">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                {{-- Workout Card --}}
                <a href="{{ route('muscleGroupsPage') }}" class="p-4 rounded-md border border-white border-opacity-20  bg-opacity-[0.10] flex flex-col items-center justify-center space-y-2 cursor-pointer hover:bg-opacity-15 transition">
                    <div class="p-3 rounded-full" style="background-color: rgba(254, 180, 123, 0.1);">
                        <svg class="w-8 h-8" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </div>
                    <p class="text-sm font-semibold">Workout</p>
                </a>
                {{-- Meal Logger Card --}}
                <a href="{{ route('meals.index') }}" class="p-4 rounded-md border border-white border-opacity-20  bg-opacity-[0.10] flex flex-col items-center justify-center space-y-2 cursor-pointer hover:bg-opacity-15 transition">
                    <div class="p-3 rounded-full" style="background-color: rgba(254, 180, 123, 0.1);">
                        <svg class="w-8 h-8" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 1.343 3 3v5H9v-5c0-1.657 1.343-3 3-3zM7 21h10a2 2 0 002-2v-5a2 2 0 00-2-2H7a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-semibold">Meal Logger</p>
                </a>
                {{-- AI Posture Card --}}
                <div class="p-4 rounded-md border border-white border-opacity-20  bg-opacity-[0.10] flex flex-col items-center justify-center space-y-2 cursor-pointer hover:bg-opacity-15 transition">
                    <div class="p-3 rounded-full" style="background-color: rgba(254, 180, 123, 0.1);">
                        <svg class="w-8 h-8" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.764v6.472a1 1 0 01-1.447.888L15 14m-5-4v2m4-2v2m-8 8h12a2 2 0 002-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-semibold">AI Posture</p>
                </div>
                {{-- Steps Card --}}
                <div class="p-4 rounded-md border border-white border-opacity-20  bg-opacity-[0.10] flex flex-col items-center justify-center space-y-2 cursor-pointer hover:bg-opacity-15 transition">
                    <div class="p-3 rounded-full" style="background-color: rgba(254, 180, 123, 0.1);">
                        <svg class="w-8 h-8" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <p class="text-sm font-semibold">Steps</p>
                </div>
            </div>
        </section>

        <hr class="border-white border-opacity-10 my-6">

        

        {{-- 5. AI Recommendations --}}
        <section class="space-y-4 pb-16">
            <div class="flex items-center space-x-2">
                {{-- <svg class="w-6 h-6" fill="currentColor" style="color: #feb47b;" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM6.57 6.57a1 1 0 011.414 0l1.58 1.58a1 1 0 001.414 0l1.58-1.58a1 1 0 111.414 1.414l-1.58 1.58a1 1 0 000 1.414l1.58 1.58a1 1 0 11-1.414 1.414l-1.58-1.58a1 1 0 00-1.414 0l-1.58 1.58a1 1 0 01-1.414-1.414l1.58-1.58a1 1 0 000-1.414l-1.58-1.58a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> --}}
                <h2 class="text-xl font-bold">AI Recommendations</h2>
            </div>

            {{-- Rec Card: Sleep --}}
            <div class="p-4 rounded-md border border-white border-opacity-20  bg-opacity-[0.10] flex items-start space-x-3">
                <div class="p-2 rounded-full" style="background-color: rgba(254, 180, 123, 0.1);">
                    <svg class="w-6 h-6" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold mb-1">Great sleep! Your body is recovered.</h3>
                    <p class="text-xs opacity-70">Based on 7 hours of sleep, you can do intense workout today.</p>
                </div>
            </div>

            {{-- Rec Card: Calories --}}
            <div class="p-4 rounded-md border border-white border-opacity-20  bg-opacity-[0.10] flex items-start space-x-3">
                <div class="p-2 rounded-full" style="background-color: rgba(254, 180, 123, 0.1);">
                    <svg class="w-6 h-6" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold mb-1">You need 750 more calories</h3>
                    <p class="text-xs opacity-70">Try having a protein-rich meal to meet your daily goal.</p>
                </div>
            </div>

            {{-- Rec Card: Water --}}
            <div class="p-4 rounded-md border border-white border-opacity-20  bg-opacity-[0.10] flex items-start space-x-3">
                <div class="p-2 rounded-full" style="background-color: rgba(254, 180, 123, 0.1);">
                    <svg class="w-6 h-6" fill="none" stroke="#feb47b" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold mb-1">Stay hydrated!</h3>
                    <p class="text-xs opacity-70">Drink 2 more glasses of water to reach your goal.</p>
                </div>
            </div>

        </section>

    </div>
</main>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const button = event.target.closest('button[onclick="toggleProfileDropdown()"]');
        
        if (!button && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Carousel functionality
    let currentSlide = 0;
    let totalSlides = 0;
    
    function slideCarousel(direction) {
        currentSlide += direction;
        if (currentSlide < 0) currentSlide = 0;
        if (currentSlide >= totalSlides) currentSlide = totalSlides - 1;
        
        updateCarousel();
    }
    
    function goToSlide(index) {
        currentSlide = index;
        updateCarousel();
    }
    
    function updateCarousel() {
        const carousel = document.getElementById('nutritionCarousel');
        if (!carousel) return;
        
        const translateX = -currentSlide * 100;
        carousel.style.transform = `translateX(${translateX}%)`;
        
        // Update dots
        document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
            if (index === currentSlide) {
                dot.classList.add('bg-[#feb47b]', 'w-6');
                dot.classList.remove('bg-white', 'bg-opacity-30', 'w-2');
            } else {
                dot.classList.remove('bg-[#feb47b]', 'w-6');
                dot.classList.add('bg-white', 'bg-opacity-30', 'w-2');
            }
        });
        
        // Update arrow visibility
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        if (prevBtn) prevBtn.style.opacity = currentSlide === 0 ? '0.5' : '1';
        if (nextBtn) nextBtn.style.opacity = currentSlide === totalSlides - 1 ? '0.5' : '1';
    }
    
    // Touch/swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left - next slide
                slideCarousel(1);
            } else {
                // Swipe right - previous slide
                slideCarousel(-1);
            }
        }
    }
    
    // Load carousel data asynchronously
    function loadCarouselData() {
        fetch('{{ route("dashboard.nutrition-carousel") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.carouselDays) {
                renderCarousel(data.carouselDays, data.today);
                // Hide loading, show carousel
                document.getElementById('carouselLoading').classList.add('hidden');
                document.getElementById('carouselContainer').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading carousel data:', error);
            document.getElementById('carouselLoading').innerHTML = 
                '<div class="text-center"><p class="text-sm opacity-70 text-red-400">Failed to load data. Please refresh.</p></div>';
        });
    }
    
    // Render carousel with data
    function renderCarousel(carouselDays, today) {
        const carousel = document.getElementById('nutritionCarousel');
        const paginationDots = document.getElementById('paginationDots');
        
        if (!carousel || !paginationDots) return;
        
        // Clear existing content
        carousel.innerHTML = '';
        paginationDots.innerHTML = '';
        
        // Create slides
        carouselDays.forEach((dayData, index) => {
            const isToday = dayData.date === today;
            const yesterdayDate = new Date(today);
            yesterdayDate.setDate(yesterdayDate.getDate() - 1);
            const isYesterday = dayData.date === yesterdayDate.toISOString().split('T')[0];
            
            let dayLabel = '';
            if (isToday) {
                dayLabel = 'Today';
            } else if (isYesterday) {
                dayLabel = 'Yesterday';
            } else {
                const date = new Date(dayData.date);
                dayLabel = date.toLocaleDateString('en-US', { weekday: 'short' });
            }
            
            const slide = document.createElement('div');
            slide.className = 'min-w-full px-5 py-6 carousel-slide';
            slide.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold">${dayData.dateFormatted}</h3>
                        <p class="text-xs opacity-70">${dayLabel}</p>
                    </div>
                    <a href="{{ route('meals.index') }}?date=${dayData.date}" 
                       class="w-8 h-8 rounded-full bg-[#feb47b] bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-[#feb47b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
                
                <div class="mb-4" style="height: 180px;">
                    <canvas id="nutritionChart${index}"></canvas>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <p class="text-xs opacity-70">Calories</p>
                        <p class="text-2xl font-bold">${parseFloat(dayData.stats.consumed.calories).toLocaleString()}</p>
                        <p class="text-xs opacity-70">of ${parseFloat(dayData.stats.goals.calories).toLocaleString()} kcal</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs opacity-70">Protein</p>
                        <p class="text-2xl font-bold">${parseFloat(dayData.stats.consumed.protein).toFixed(1)}g</p>
                        <p class="text-xs opacity-70">of ${parseFloat(dayData.stats.goals.protein).toFixed(1)}g</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs opacity-70">Carbs</p>
                        <p class="text-xl font-bold">${parseFloat(dayData.stats.consumed.carbs).toFixed(1)}g</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs opacity-70">Fats</p>
                        <p class="text-xl font-bold">${parseFloat(dayData.stats.consumed.fats).toFixed(1)}g</p>
                    </div>
                </div>
            `;
            carousel.appendChild(slide);
            
            // Create pagination dot
            const dot = document.createElement('button');
            dot.onclick = () => goToSlide(index);
            dot.className = `carousel-dot w-2 h-2 rounded-full transition-all ${index === carouselDays.length - 1 ? 'bg-[#feb47b] w-6' : 'bg-white bg-opacity-30'}`;
            dot.setAttribute('data-slide', index);
            paginationDots.appendChild(dot);
        });
        
        // Initialize carousel state
        currentSlide = carouselDays.length - 1; // Start at last slide (today)
        totalSlides = carouselDays.length;
        updateCarousel();
        
        // Setup touch events
        const carouselContainer = document.getElementById('carouselContainer');
        if (carouselContainer) {
            carouselContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            carouselContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }
        
        // Create charts for each day
        carouselDays.forEach((dayData, index) => {
            const ctx = document.getElementById(`nutritionChart${index}`);
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Calories', 'Protein'],
                    datasets: [{
                        label: 'Consumed',
                        data: [
                            dayData.stats.consumed.calories,
                            dayData.stats.consumed.protein * 4
                        ],
                        backgroundColor: 'rgba(254, 180, 123, 0.6)',
                        borderColor: '#feb47b',
                        borderWidth: 2,
                        borderRadius: 8
                    }, {
                        label: 'Goal',
                        data: [
                            dayData.stats.goals.calories,
                            dayData.stats.goals.protein * 4
                        ],
                        backgroundColor: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.3)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderDash: [5, 5]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        if (context.dataIndex === 0) {
                                            return context.parsed.y.toLocaleString() + ' kcal';
                                        } else {
                                            return (context.parsed.y / 4).toFixed(1) + 'g protein';
                                        }
                                    } else {
                                        if (context.dataIndex === 0) {
                                            return 'Goal: ' + context.parsed.y.toLocaleString() + ' kcal';
                                        } else {
                                            return 'Goal: ' + (context.parsed.y / 4).toFixed(1) + 'g protein';
                                        }
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)',
                                font: { size: 10 }
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)',
                                font: { size: 11 }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    }
    
    // Load carousel data when page loads
    if (typeof Chart !== 'undefined') {
        loadCarouselData();
    } else {
        // Wait for Chart.js to load
        window.addEventListener('load', () => {
            loadCarouselData();
        });
    }
</script>

@endsection