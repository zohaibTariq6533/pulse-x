@extends('layout.main')
@section('content')

{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen">
    <div class="max-w-xl mx-auto px-5 py-8 text-white space-y-6">

        {{-- 1. Header Section --}}
        <header class="flex justify-between items-center p-2 rounded-2xl  bg-opacity-5">
            <div class="flex items-center">
                {{-- Profile Icon --}}
                <div class="p-2.5 rounded-full shadow-lg" 
                     style="background-image: linear-gradient(to top left, #3498db, #2980b9);">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm tracking-wider" style="color: rgba(255, 255, 255, 0.7);">Welcome Back,</p>
                    <h1 class="text-2xl font-bold tracking-wide">Muhammad</h1>
                </div>
            </div>
            {{-- Notification Button
            <button class="w-11 h-11 rounded-full shadow-lg flex items-center justify-center transition duration-150 hover:opacity-90"
                    style="background-image: linear-gradient(to top left, #FF6B6B, #FF8E53);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button> --}}
        </header>

        {{-- 2. Daily Goal Card --}}
        <section class="p-5 rounded-[8px] shadow-2xl" 
                 style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.2));">
            <div class="space-y-5">
                {{-- Calories Section --}}
                <h2 class="text-lg font-bold">Daily Calories</h2>
                
                <div class="flex justify-between items-center">
                    <div class="flex space-x-6">
                        {{-- Consumed --}}
                        <div>
                            <p class="text-xs font-light opacity-70">Consumed</p>
                            <p class="text-4xl font-bold">1450</p>
                        </div>
                        {{-- Burned --}}
                        <div>
                            <p class="text-2xs font-light opacity-70">Total Calories</p>
                            <p class="text-2xl font-bold">450</p>
                        </div>
                    </div>
                    {{-- Circular Progress Bar (Calories) --}}
                    <div class="relative w-[70px] h-[70px]">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            {{-- Background Arc --}}
                            <circle cx="50" cy="50" r="35" fill="none" class="stroke-current opacity-20" stroke-width="8"></circle>
                            {{-- Progress Arc (66% progress) --}}
                            <circle cx="50" cy="50" r="35" fill="none" stroke="white" stroke-width="8" 
                                    stroke-dasharray="219.911" 
                                    stroke-dashoffset="74.77" 
                                    stroke-linecap="round"></circle>
                        </svg>
                        <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-lg font-bold">66%</span>
                    </div>
                </div>

                <p class="text-sm font-medium">Remaining: 750 kcal</p>

                {{-- Calories Linear Progress Bar --}}
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between text-xs">
                        <span class="opacity-70">Progress</span>
                        <span class="font-bold">1450 / 2200 kcal</span>
                    </div>
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full bg-white" style="width: 66%;"></div>
                    </div>
                </div>
            </div>
        </section>
        
        <hr class="border-white border-opacity-10 my-6">

        {{-- Recommended Workout Program Card --}}
        <a href="{{ route('workoutTypePage') }}" class="relative">
            <div class="p-5 rounded-xl border-2 cursor-pointer transition-all hover:scale-[1.02]"
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
                        <p class="text-sm opacity-80 mb-3">Upper Body Strength Training</p>
                        <div class="flex items-center space-x-4 text-xs">
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="opacity-70">45 min</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="opacity-70">Moderate</span>
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

        <hr class="border-white border-opacity-10 my-6">

        {{-- 3. Quick Actions Grid --}}
        <section class="space-y-4">
            <h2 class="text-xl font-bold">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                {{-- Workout Card --}}
                <div class="p-4 rounded-2xl border border-white border-opacity-20  bg-opacity-[0.10] flex flex-col items-center justify-center space-y-2 cursor-pointer hover:bg-opacity-15 transition">
                    <div class="p-3 rounded-full" style="background-color: #6C63FF1A;">
                        <svg class="w-8 h-8" fill="none" stroke="#6C63FF" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </div>
                    <p class="text-sm font-semibold">Workout</p>
                </div>
                {{-- Meal Logger Card --}}
                <div class="p-4 rounded-2xl border border-white border-opacity-20  bg-opacity-[0.10] flex flex-col items-center justify-center space-y-2 cursor-pointer hover:bg-opacity-15 transition">
                    <div class="p-3 rounded-full" style="background-color: #4CAF501A;">
                        <svg class="w-8 h-8" fill="none" stroke="#4CAF50" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 1.343 3 3v5H9v-5c0-1.657 1.343-3 3-3zM7 21h10a2 2 0 002-2v-5a2 2 0 00-2-2H7a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-semibold">Meal Logger</p>
                </div>
                {{-- AI Posture Card --}}
                <div class="p-4 rounded-2xl border border-white border-opacity-20  bg-opacity-[0.10] flex flex-col items-center justify-center space-y-2 cursor-pointer hover:bg-opacity-15 transition">
                    <div class="p-3 rounded-full" style="background-color: #FF98001A;">
                        <svg class="w-8 h-8" fill="none" stroke="#FF9800" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.764v6.472a1 1 0 01-1.447.888L15 14m-5-4v2m4-2v2m-8 8h12a2 2 0 002-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-semibold">AI Posture</p>
                </div>
                {{-- AI Chatbot Card --}}
                <div class="p-4 rounded-2xl border border-white border-opacity-20  bg-opacity-[0.10] flex flex-col items-center justify-center space-y-2 cursor-pointer hover:bg-opacity-15 transition">
                    <div class="p-3 rounded-full" style="background-color: #E91E631A;">
                        <svg class="w-8 h-8" fill="none" stroke="#E91E63" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z"></path></svg>
                    </div>
                    <p class="text-sm font-semibold">AI Chatbot</p>
                </div>
            </div>
        </section>

        <hr class="border-white border-opacity-10 my-6">

        {{-- 4. Today's Overview --}}
        <section class="space-y-4">
            <h2 class="text-xl font-bold">Today's Overview</h2>
            <div class="p-5 rounded-2xl border border-white border-opacity-20  bg-opacity-[0.10] space-y-4">
                
                {{-- Calories Consumed Row --}}
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Calories Consumed</span>
                        <span class="text-sm font-bold">1450 / 2200 kcal</span>
                    </div>
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full" style="background-color: #4CAF50; width: 66%;"></div>
                    </div>
                </div>

                {{-- Calories Burned Row --}}
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Calories Burned</span>
                        <span class="text-sm font-bold">450 kcal</span>
                    </div>
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full" style="background-color: #FF6B6B; width: 45%;"></div>
                    </div>
                </div>

                {{-- Daily Steps Row --}}
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Daily Steps</span>
                        <span class="text-sm font-bold">5,420 / 10,000</span>
                    </div>
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full" style="background-color: #6C63FF; width: 54%;"></div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="border-white border-opacity-10 my-6">

        {{-- 5. AI Recommendations --}}
        <section class="space-y-4 pb-16">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="currentColor" style="color: #FFD700;" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM6.57 6.57a1 1 0 011.414 0l1.58 1.58a1 1 0 001.414 0l1.58-1.58a1 1 0 111.414 1.414l-1.58 1.58a1 1 0 000 1.414l1.58 1.58a1 1 0 11-1.414 1.414l-1.58-1.58a1 1 0 00-1.414 0l-1.58 1.58a1 1 0 01-1.414-1.414l1.58-1.58a1 1 0 000-1.414l-1.58-1.58a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <h2 class="text-xl font-bold">AI Recommendations</h2>
            </div>

            {{-- Rec Card: Sleep --}}
            <div class="p-4 rounded-2xl border border-white border-opacity-20  bg-opacity-[0.10] flex items-start space-x-3">
                <div class="p-2 rounded-full" style="background-color: #4CAF501A;">
                    <svg class="w-6 h-6" fill="none" stroke="#4CAF50" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold mb-1">Great sleep! Your body is recovered.</h3>
                    <p class="text-xs opacity-70">Based on 7 hours of sleep, you can do intense workout today.</p>
                </div>
            </div>

            {{-- Rec Card: Calories --}}
            <div class="p-4 rounded-2xl border border-white border-opacity-20  bg-opacity-[0.10] flex items-start space-x-3">
                <div class="p-2 rounded-full" style="background-color: #FF98001A;">
                    <svg class="w-6 h-6" fill="none" stroke="#FF9800" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold mb-1">You need 750 more calories</h3>
                    <p class="text-xs opacity-70">Try having a protein-rich meal to meet your daily goal.</p>
                </div>
            </div>

            {{-- Rec Card: Water --}}
            <div class="p-4 rounded-2xl border border-white border-opacity-20  bg-opacity-[0.10] flex items-start space-x-3">
                <div class="p-2 rounded-full" style="background-color: #2196F31A;">
                    <svg class="w-6 h-6" fill="none" stroke="#2196F3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold mb-1">Stay hydrated!</h3>
                    <p class="text-xs opacity-70">Drink 2 more glasses of water to reach your goal.</p>
                </div>
            </div>

        </section>

    </div>
</main>

{{-- 6. Bottom Navigation Bar --}}
<footer class="fixed bottom-0 left-0 right-0 max-w-xl mx-auto z-10 p-2"
        style="background-color: transparent;">
    <nav class="flex justify-around py-3 rounded-t-2xl border border-opacity-10 shadow-2xl"
         style="background-color: #1a1a2e; border-color: rgba(255, 255, 255, 0.1);">
        
        {{-- Home --}}
        <a href="#" class="flex flex-col items-center text-center transition" style="color: #feb47b;">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
            <span class="text-xs mt-1 font-semibold">Home</span>
        </a>
        {{-- Workout --}}
        <a href="#" class="flex flex-col items-center text-center transition" style="color: rgba(255, 255, 255, 0.5);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span class="text-xs mt-1">Workout</span>
        </a>
        {{-- Meals --}}
        <a href="#" class="flex flex-col items-center text-center transition" style="color: rgba(255, 255, 255, 0.5);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-xs mt-1">Meals</span>
        </a>
        {{-- Profile --}}
        <a href="#" class="flex flex-col items-center text-center transition" style="color: rgba(255, 255, 255, 0.5);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-xs mt-1">Profile</span>
        </a>
    </nav>
</footer>
@endsection