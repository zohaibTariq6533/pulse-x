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
            <div class="grid grid-cols-2 gap-4">
                {{-- Chest Card --}}
                <a href="{{ route('singleMusclePage', 'chest') }}" class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-6 flex flex-col items-center justify-center min-h-[120px]">
                        <h3 class="text-lg font-bold tracking-tight text-center">Chest</h3>
                    </div>
                </a>

                {{-- Bicep & Tricep Card --}}
                <a href="{{ route('singleMusclePage', 'upper-arms') }}" class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-6 flex flex-col items-center justify-center min-h-[120px]">
                        <h3 class="text-lg font-bold tracking-tight text-center">Bicep & Tricep</h3>
                    </div>
                </a>

                {{-- Shoulder Card --}}
                <a href="{{ route('singleMusclePage', 'shoulders') }}" class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-6 flex flex-col items-center justify-center min-h-[120px]">
                        <h3 class="text-lg font-bold tracking-tight text-center">Shoulder</h3>
                    </div>
                </a>

                {{-- Back Card --}}
                <a href="{{ route('singleMusclePage', 'back') }}" class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-6 flex flex-col items-center justify-center min-h-[120px]">
                        <h3 class="text-lg font-bold tracking-tight text-center">Back</h3>
                    </div>
                </a>

                {{-- Leg Card --}}
                <a href="{{ route('singleMusclePage', 'upper-legs') }}" class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-6 flex flex-col items-center justify-center min-h-[120px]">
                        <h3 class="text-lg font-bold tracking-tight text-center">Leg</h3>
                    </div>
                </a>

                {{-- Abs Card --}}
                <a href="{{ route('singleMusclePage', 'waist') }}" class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-6 flex flex-col items-center justify-center min-h-[120px]">
                        <h3 class="text-lg font-bold tracking-tight text-center">Abs</h3>
                    </div>
                </a>

                {{-- Forearms Card --}}
                <a href="{{ route('singleMusclePage', 'lower-arms') }}" class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-6 flex flex-col items-center justify-center min-h-[120px]">
                        <h3 class="text-lg font-bold tracking-tight text-center">Forearms</h3>
                    </div>
                </a>

                {{-- Calves Card --}}
                <a href="{{ route('singleMusclePage', 'lower-legs') }}" class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-6 flex flex-col items-center justify-center min-h-[120px]">
                        <h3 class="text-lg font-bold tracking-tight text-center">Calves</h3>
                    </div>
                </a>
            </div>
        </section>

    </div>
</main>

{{-- Bottom Navigation Bar --}}
<footer class="fixed bottom-0 left-0 right-0 max-w-xl mx-auto z-10 p-2"
        style="background-color: transparent;">
    <nav class="flex justify-around py-3 rounded-t-2xl border border-opacity-10 shadow-2xl"
         style="background-color: #1a1a2e; border-color: rgba(255, 255, 255, 0.1);">
        
        {{-- Home --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-center transition" style="color: rgba(255, 255, 255, 0.5);">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
            <span class="text-xs mt-1">Home</span>
        </a>
        {{-- Workout --}}
        <a href="{{ route('muscleGroupsPage') }}" class="flex flex-col items-center text-center transition" style="color: #feb47b;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span class="text-xs mt-1 font-semibold">Workout</span>
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
