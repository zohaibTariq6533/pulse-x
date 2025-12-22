@extends('layout.main')
@section('content')

{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen">
    <div class="max-w-xl mx-auto px-5 py-8 text-white w-full">
        
        {{-- Header Section --}}
        <header class="flex items-center justify-center p-2 rounded-2xl bg-opacity-5 mb-6 relative">
            <a href="{{ route('dashboard') }}" class="absolute left-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold tracking-wide text-center">All Workouts</h1>
        </header>

        {{-- Muscle Groups Grid --}}
        <section class="space-y-4 pb-20">
            <h2 class="text-lg font-bold">Select Muscle Group</h2>
            <div class="space-y-4">
                {{-- Chest Card --}}
                <a href="{{ route('singleMusclePage', 'chest') }}" class="block group">
                    <div class="relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:border-opacity-60 hover:shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.15), rgba(229, 229, 229, 0.1)); border-color: rgba(254, 180, 123, 0.4); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-10"
                             style="background: radial-gradient(circle, rgba(254, 180, 123, 0.5) 0%, transparent 70%);"></div>
                        <div class="relative p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="p-3 rounded-lg" style="background: rgba(254, 180, 123, 0.2);">
                                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Chest</h3>
                                        <p class="text-sm opacity-70">Upper body strength</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="px-3 py-1 rounded-full bg-opacity-20 border" style="background-color: rgba(254, 180, 123, 0.1); border-color: rgba(254, 180, 123, 0.3);">
                                        Push exercises
                                    </span>
                                    <span class="opacity-60">Build & Define</span>
                                </div>
                            </div>
                            <svg class="w-6 h-6 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Bicep & Tricep Card --}}
                <a href="{{ route('singleMusclePage', 'upper-arms') }}" class="block group">
                    <div class="relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:border-opacity-60 hover:shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.15), rgba(229, 229, 229, 0.1)); border-color: rgba(254, 180, 123, 0.4); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-10"
                             style="background: radial-gradient(circle, rgba(254, 180, 123, 0.5) 0%, transparent 70%);"></div>
                        <div class="relative p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="p-3 rounded-lg" style="background: rgba(254, 180, 123, 0.2);">
                                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Bicep & Tricep</h3>
                                        <p class="text-sm opacity-70">Arm definition</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="px-3 py-1 rounded-full bg-opacity-20 border" style="background-color: rgba(254, 180, 123, 0.1); border-color: rgba(254, 180, 123, 0.3);">
                                        Flex & Extend
                                    </span>
                                    <span class="opacity-60">Toned arms</span>
                                </div>
                            </div>
                            <svg class="w-6 h-6 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Shoulder Card --}}
                <a href="{{ route('singleMusclePage', 'shoulders') }}" class="block group">
                    <div class="relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:border-opacity-60 hover:shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.15), rgba(229, 229, 229, 0.1)); border-color: rgba(254, 180, 123, 0.4); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-10"
                             style="background: radial-gradient(circle, rgba(254, 180, 123, 0.5) 0%, transparent 70%);"></div>
                        <div class="relative p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="p-3 rounded-lg" style="background: rgba(254, 180, 123, 0.2);">
                                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Shoulder</h3>
                                        <p class="text-sm opacity-70">Broad & strong</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="px-3 py-1 rounded-full bg-opacity-20 border" style="background-color: rgba(254, 180, 123, 0.1); border-color: rgba(254, 180, 123, 0.3);">
                                        Overhead press
                                    </span>
                                    <span class="opacity-60">Power & Stability</span>
                                </div>
                            </div>
                            <svg class="w-6 h-6 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Back Card --}}
                <a href="{{ route('singleMusclePage', 'back') }}" class="block group">
                    <div class="relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:border-opacity-60 hover:shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.15), rgba(229, 229, 229, 0.1)); border-color: rgba(254, 180, 123, 0.4); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-10"
                             style="background: radial-gradient(circle, rgba(254, 180, 123, 0.5) 0%, transparent 70%);"></div>
                        <div class="relative p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="p-3 rounded-lg" style="background: rgba(254, 180, 123, 0.2);">
                                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Back</h3>
                                        <p class="text-sm opacity-70">Posture & strength</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="px-3 py-1 rounded-full bg-opacity-20 border" style="background-color: rgba(254, 180, 123, 0.1); border-color: rgba(254, 180, 123, 0.3);">
                                        Pull exercises
                                    </span>
                                    <span class="opacity-60">V-taper shape</span>
                                </div>
                            </div>
                            <svg class="w-6 h-6 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Leg Card --}}
                <a href="{{ route('singleMusclePage', 'upper-legs') }}" class="block group">
                    <div class="relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:border-opacity-60 hover:shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.15), rgba(229, 229, 229, 0.1)); border-color: rgba(254, 180, 123, 0.4); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-10"
                             style="background: radial-gradient(circle, rgba(254, 180, 123, 0.5) 0%, transparent 70%);"></div>
                        <div class="relative p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="p-3 rounded-lg" style="background: rgba(254, 180, 123, 0.2);">
                                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Leg</h3>
                                        <p class="text-sm opacity-70">Lower body power</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="px-3 py-1 rounded-full bg-opacity-20 border" style="background-color: rgba(254, 180, 123, 0.1); border-color: rgba(254, 180, 123, 0.3);">
                                        Squat & Lunge
                                    </span>
                                    <span class="opacity-60">Strength & Power</span>
                                </div>
                            </div>
                            <svg class="w-6 h-6 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Abs Card --}}
                <a href="{{ route('singleMusclePage', 'waist') }}" class="block group">
                    <div class="relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:border-opacity-60 hover:shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.15), rgba(229, 229, 229, 0.1)); border-color: rgba(254, 180, 123, 0.4); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-10"
                             style="background: radial-gradient(circle, rgba(254, 180, 123, 0.5) 0%, transparent 70%);"></div>
                        <div class="relative p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="p-3 rounded-lg" style="background: rgba(254, 180, 123, 0.2);">
                                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Abs</h3>
                                        <p class="text-sm opacity-70">Core stability</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="px-3 py-1 rounded-full bg-opacity-20 border" style="background-color: rgba(254, 180, 123, 0.1); border-color: rgba(254, 180, 123, 0.3);">
                                        Core strength
                                    </span>
                                    <span class="opacity-60">Six-pack goal</span>
                                </div>
                            </div>
                            <svg class="w-6 h-6 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Forearms Card --}}
                <a href="{{ route('singleMusclePage', 'lower-arms') }}" class="block group">
                    <div class="relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:border-opacity-60 hover:shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.15), rgba(229, 229, 229, 0.1)); border-color: rgba(254, 180, 123, 0.4); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-10"
                             style="background: radial-gradient(circle, rgba(254, 180, 123, 0.5) 0%, transparent 70%);"></div>
                        <div class="relative p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="p-3 rounded-lg" style="background: rgba(254, 180, 123, 0.2);">
                                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Forearms</h3>
                                        <p class="text-sm opacity-70">Grip strength</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="px-3 py-1 rounded-full bg-opacity-20 border" style="background-color: rgba(254, 180, 123, 0.1); border-color: rgba(254, 180, 123, 0.3);">
                                        Wrist & Grip
                                    </span>
                                    <span class="opacity-60">Functional strength</span>
                                </div>
                            </div>
                            <svg class="w-6 h-6 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Calves Card --}}
                <a href="{{ route('singleMusclePage', 'lower-legs') }}" class="block group">
                    <div class="relative overflow-hidden rounded-lg border-2 transition-all duration-300 hover:border-opacity-60 hover:shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.15), rgba(229, 229, 229, 0.1)); border-color: rgba(254, 180, 123, 0.4); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-10"
                             style="background: radial-gradient(circle, rgba(254, 180, 123, 0.5) 0%, transparent 70%);"></div>
                        <div class="relative p-6 flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="p-3 rounded-lg" style="background: rgba(254, 180, 123, 0.2);">
                                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-1">Calves</h3>
                                        <p class="text-sm opacity-70">Lower leg definition</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="px-3 py-1 rounded-full bg-opacity-20 border" style="background-color: rgba(254, 180, 123, 0.1); border-color: rgba(254, 180, 123, 0.3);">
                                        Calf raises
                                    </span>
                                    <span class="opacity-60">Balance & Stability</span>
                                </div>
                            </div>
                            <svg class="w-6 h-6 opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </section>

    </div>
</main>

@endsection
