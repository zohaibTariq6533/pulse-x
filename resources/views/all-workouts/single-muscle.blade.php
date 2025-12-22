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
        <section class="space-y-4 pb-20" id="exercises-list">
            @forelse($exercises as $exercise)
                <a href="{{ route('exerciseDetailPage', $exercise['id']) }}" class="block relative overflow-hidden rounded-lg border-2 transition-all hover:scale-[1.01] hover:shadow-xl cursor-pointer group"
                   style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.1), rgba(229, 229, 229, 0.05)); border-color: rgba(254, 180, 123, 0.3); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    
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
                                  style="background: rgba(254, 180, 123, 0.2); color: #feb47b; border: 1px solid rgba(254, 180, 123, 0.3);">
                                {{ $exercise['target'] }}
                            </span>
                            
                            {{-- Equipment --}}
                            <span class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                  style="background: rgba(254, 180, 123, 0.2); color: #feb47b; border: 1px solid rgba(254, 180, 123, 0.3);">
                                {{ $exercise['equipment'] }}
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

                        {{-- Instructions and Arrow --}}
                        <div class="flex items-center justify-end pt-2 gap-3">
                            @if(!empty($exercise['instructions']))
                                <details>
                                    <summary class="text-sm font-medium cursor-pointer hover:opacity-80 transition inline-flex items-center gap-2 px-4 py-2 rounded-lg"
                                             style="background: rgba(254, 180, 123, 0.2); color: #feb47b; border: 1px solid rgba(254, 180, 123, 0.4);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        View Instructions
                                    </summary>
                                    <ol class="mt-3 space-y-2 text-sm opacity-90 pl-4 absolute z-10 p-4 rounded-lg border"
                                        style="background: rgba(26, 26, 46, 0.95); border-color: rgba(254, 180, 123, 0.3); backdrop-filter: blur(10px);">
                                        @foreach($exercise['instructions'] as $index => $instruction)
                                            <li class="flex gap-2">
                                                <span class="text-xs font-bold opacity-50">{{ $index + 1 }}.</span>
                                                <span>{{ $instruction }}</span>
                                            </li>
                                        @endforeach
                                    </ol>
                                </details>
                            @endif
                            
                            {{-- Arrow Indicator --}}
                            <div class="flex items-center group-hover:translate-x-1 transition-transform">
                                <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
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


{{-- Loading Skeleton Styles --}}
<style>
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    
    .skeleton {
        background: linear-gradient(90deg, 
            rgba(255, 255, 255, 0.05) 0%, 
            rgba(255, 255, 255, 0.1) 50%, 
            rgba(255, 255, 255, 0.05) 100%);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }
</style>

<script>
    // Show loading skeleton while page is loading (if needed for future client-side fetching)
    document.addEventListener('DOMContentLoaded', function() {
        // If exercises are already loaded, hide any loading states
        const exercisesList = document.getElementById('exercises-list');
        if (exercisesList && exercisesList.children.length > 0) {
            // Content is already loaded, no need for skeleton
        }
    });
</script>
@endsection
