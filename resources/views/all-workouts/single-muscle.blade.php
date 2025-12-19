@extends('layout.main')
@section('content')

{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen">
    <div class="max-w-xl mx-auto px-5 py-8 text-white w-full">
        
        {{-- Header Section --}}
        <header class="flex items-center justify-center p-2 rounded-2xl bg-opacity-5 mb-6 relative">
            <a href="{{ route('muscleGroupsPage') }}" class="absolute left-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold tracking-wide text-center">{{ $displayName }} Exercises</h1>
        </header>

        {{-- Exercises Count --}}
        @if(count($exercises) > 0)
            <p class="text-sm opacity-70 mb-4">{{ count($exercises) }} exercises found</p>
        @endif

        {{-- Exercises List --}}
        <section class="space-y-4 pb-20">
            @forelse($exercises as $exercise)
                <a href="{{ route('exerciseDetailPage', $exercise['id']) }}" class="block relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.01] hover:shadow-xl cursor-pointer"
                   style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.15), rgba(229, 229, 229, 0.05)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    {{-- Exercise GIF --}}
                    {{-- <div class="w-full h-48 bg-black bg-opacity-30 flex items-center justify-center overflow-hidden">
                        <img src="{{ $exercise['gifUrl'] }}" 
                             alt="{{ $exercise['name'] }}" 
                             class="w-full h-full object-contain"
                             loading="lazy">
                    </div> --}}
                    
                    {{-- Exercise Details --}}
                    <div class="p-4 space-y-3">
                        {{-- Exercise Name --}}
                        <h3 class="text-lg font-bold tracking-tight capitalize">{{ $exercise['name'] }}</h3>
                        
                        {{-- Tags Row --}}
                        <div class="flex flex-wrap gap-2">
                            {{-- Target Muscle --}}
                            <span class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                  style="background: rgba(255, 107, 107, 0.2); color: #FF6B6B;">
                                {{ $exercise['target'] }}
                            </span>
                            
                            {{-- Equipment --}}
                            <span class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                  style="background: rgba(108, 99, 255, 0.2); color: #6C63FF;">
                                {{ $exercise['equipment'] }}
                            </span>
                            
                            {{-- Body Part --}}
                            <span class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                  style="background: rgba(76, 175, 80, 0.2); color: #4CAF50;">
                                {{ $exercise['bodyPart'] }}
                            </span>
                        </div>

                        {{-- Secondary Muscles --}}
                        @if(!empty($exercise['secondaryMuscles']))
                            <div class="pt-2">
                                <p class="text-xs opacity-70 mb-2">Secondary Muscles:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($exercise['secondaryMuscles'] as $muscle)
                                        <span class="px-2 py-0.5 rounded-full text-xs capitalize"
                                              style="background: rgba(255, 255, 255, 0.1);">
                                            {{ $muscle }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Instructions (Collapsible) --}}
                        @if(!empty($exercise['instructions']))
                            <details class="pt-2">
                                <summary class="text-sm font-medium cursor-pointer hover:opacity-80 transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    View Instructions
                                </summary>
                                <ol class="mt-3 space-y-2 text-sm opacity-90 pl-4">
                                    @foreach($exercise['instructions'] as $index => $instruction)
                                        <li class="flex gap-2">
                                            <span class="text-xs font-bold opacity-50">{{ $index + 1 }}.</span>
                                            <span>{{ $instruction }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            </details>
                        @endif
                    </div>
                </a>
            @empty
                {{-- No exercises found --}}
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto opacity-50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg opacity-70">No exercises found</p>
                    <p class="text-sm opacity-50 mt-2">Try selecting a different muscle group</p>
                    <a href="{{ route('muscleGroupsPage') }}" class="inline-block mt-4 px-6 py-3 rounded-lg bg-white bg-opacity-10 hover:bg-opacity-20 transition-all">
                        Go Back
                    </a>
                </div>
            @endforelse
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
