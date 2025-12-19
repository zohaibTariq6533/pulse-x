@extends('layout.main')
@section('content')

{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen">
    <div class="max-w-xl mx-auto px-5 py-8 text-white w-full">
        
        @if($exercise)
            {{-- Header Section --}}
            <header class="flex items-center justify-center p-2 rounded-2xl bg-opacity-5 mb-6 relative">
                @if($bodyPartSlug)
                    <a href="{{ route('singleMusclePage', $bodyPartSlug) }}" class="absolute left-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif
                <h1 class="text-xl font-bold tracking-wide text-center">Exercise Details</h1>
            </header>

            {{-- Exercise GIF --}}
            @if($imageUrl)
                <div class="mb-6 rounded-2xl overflow-hidden border border-white border-opacity-20">
                    <div class="w-full h-64 bg-black bg-opacity-30 flex items-center justify-center relative">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $exercise['name'] }}" 
                             class="w-full h-full object-contain"
                             loading="lazy"
                             style="max-width: 100%; max-height: 100%;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="hidden absolute inset-0 items-center justify-center text-white opacity-50">
                            <p class="text-sm">Image not available</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Exercise Name --}}
            <div class="mb-6">
                <h2 class="text-2xl font-bold tracking-tight capitalize mb-2">{{ $exercise['name'] }}</h2>
            </div>

            {{-- Tags Section --}}
            <div class="mb-6 space-y-4">
                <div class="flex flex-wrap gap-2">
                    {{-- Target Muscle --}}
                    <span class="px-4 py-2 rounded-full text-sm font-medium capitalize"
                          style="background: rgba(255, 107, 107, 0.2); color: #FF6B6B; border: 1px solid rgba(255, 107, 107, 0.3);">
                        <span class="opacity-70">Target: </span>{{ $exercise['target'] }}
                    </span>
                    
                    {{-- Equipment --}}
                    <span class="px-4 py-2 rounded-full text-sm font-medium capitalize"
                          style="background: rgba(108, 99, 255, 0.2); color: #6C63FF; border: 1px solid rgba(108, 99, 255, 0.3);">
                        <span class="opacity-70">Equipment: </span>{{ $exercise['equipment'] }}
                    </span>
                    
                    {{-- Body Part --}}
                    <span class="px-4 py-2 rounded-full text-sm font-medium capitalize"
                          style="background: rgba(76, 175, 80, 0.2); color: #4CAF50; border: 1px solid rgba(76, 175, 80, 0.3);">
                        <span class="opacity-70">Body Part: </span>{{ $exercise['bodyPart'] }}
                    </span>
                </div>
            </div>

            {{-- Secondary Muscles --}}
            @if(!empty($exercise['secondaryMuscles']))
                <div class="mb-6 p-4 rounded-2xl border border-white border-opacity-20"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.15), rgba(229, 229, 229, 0.05));">
                    <h3 class="text-lg font-bold mb-3">Secondary Muscles</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($exercise['secondaryMuscles'] as $muscle)
                            <span class="px-3 py-1.5 rounded-full text-sm capitalize font-medium"
                                  style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.2);">
                                {{ $muscle }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Instructions Section --}}
            @if(!empty($exercise['instructions']))
                <div class="mb-6 p-5 rounded-2xl border border-white border-opacity-20"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.15), rgba(229, 229, 229, 0.05));">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-bold">Instructions</h3>
                    </div>
                    <ol class="space-y-3">
                        @foreach($exercise['instructions'] as $index => $instruction)
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                                      style="background: rgba(255, 182, 123, 0.2); color: #feb47b;">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-sm opacity-90 leading-relaxed pt-1">{{ $instruction }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            {{-- Additional Info Section --}}
            <div class="mb-20 grid grid-cols-2 gap-4">
                {{-- Body Part Card --}}
                <div class="p-4 rounded-2xl border border-white border-opacity-20"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.1), rgba(229, 229, 229, 0.05));">
                    <p class="text-xs opacity-70 mb-1">Body Part</p>
                    <p class="text-base font-bold capitalize">{{ $exercise['bodyPart'] }}</p>
                </div>

                {{-- Equipment Card --}}
                <div class="p-4 rounded-2xl border border-white border-opacity-20"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.1), rgba(229, 229, 229, 0.05));">
                    <p class="text-xs opacity-70 mb-1">Equipment</p>
                    <p class="text-base font-bold capitalize">{{ $exercise['equipment'] }}</p>
                </div>
            </div>

        @else
            {{-- Exercise Not Found --}}
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto opacity-50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-lg opacity-70 mb-2">Exercise not found</p>
                <p class="text-sm opacity-50 mb-4">The exercise details could not be loaded</p>
                <a href="{{ route('muscleGroupsPage') }}" class="inline-block px-6 py-3 rounded-lg bg-white bg-opacity-10 hover:bg-opacity-20 transition-all">
                    Go Back to Muscle Groups
                </a>
            </div>
        @endif

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
