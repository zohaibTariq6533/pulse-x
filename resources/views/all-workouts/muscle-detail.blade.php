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
                <div class="mb-6 rounded-xl overflow-hidden border-2"
                     style="border-color: rgba(254, 180, 123, 0.3);">
                    <div class="w-full h-64 bg-black bg-opacity-30 flex items-center justify-center relative">
                        {{-- Loading Skeleton --}}
                        <div id="image-skeleton" class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 border-4 border-white border-opacity-20 border-t-white rounded-full animate-spin"></div>
                        </div>
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $exercise['name'] }}" 
                             class="w-full h-full object-contain opacity-0 transition-opacity duration-300"
                             style="max-width: 100%; max-height: 100%;"
                             onload="this.classList.remove('opacity-0'); this.classList.add('opacity-100'); document.getElementById('image-skeleton').style.display='none';"
                             onerror="this.style.display='none'; document.getElementById('image-skeleton').style.display='none'; document.getElementById('image-error').style.display='flex';">
                        <div id="image-error" class="hidden absolute inset-0 items-center justify-center text-white opacity-50">
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
                          style="background: rgba(254, 180, 123, 0.2); color: #feb47b; border: 1px solid rgba(254, 180, 123, 0.3);">
                        {{ $exercise['target'] }}
                    </span>

                    {{-- Equipment --}}
                    <span class="px-4 py-2 rounded-full text-sm font-medium capitalize"
                          style="background: rgba(254, 180, 123, 0.2); color: #feb47b; border: 1px solid rgba(254, 180, 123, 0.3);">
                        {{ $exercise['equipment'] }}
                    </span>
                </div>
            </div>

            {{-- Secondary Muscles --}}
            @if(!empty($exercise['secondaryMuscles']))
                <div class="mb-6 p-4 rounded-md border-2"
                     style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.1), rgba(229, 229, 229, 0.05)); border-color: rgba(254, 180, 123, 0.3);">
                    <h3 class="text-lg font-bold mb-3">Secondary Muscles</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($exercise['secondaryMuscles'] as $muscle)
                            <span class="px-3 py-1.5 rounded-full text-sm capitalize font-medium"
                                  style="background: rgba(254, 180, 123, 0.2); color: #feb47b; border: 1px solid rgba(254, 180, 123, 0.3);">
                                {{ $muscle }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Instructions Section --}}
            @if(!empty($exercise['instructions']))
                <div class="mb-6 p-5 rounded-md border-2"
                     style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.1), rgba(229, 229, 229, 0.05)); border-color: rgba(254, 180, 123, 0.3);">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6" style="color: #feb47b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-bold" style="color: #feb47b;">Instructions</h3>
                    </div>
                    <ol class="space-y-3">
                        @foreach($exercise['instructions'] as $index => $instruction)
                            <li class="flex gap-3 items-start">
                                <span class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                                      style="background: rgba(254, 180, 123, 0.2); color: #feb47b; border: 1px solid rgba(254, 180, 123, 0.3);">
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
                {{-- Target Muscle Card --}}
                <div class="p-4 rounded-md border-2"
                     style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.1), rgba(229, 229, 229, 0.05)); border-color: rgba(254, 180, 123, 0.3);">
                    <p class="text-xs opacity-70 mb-1" style="color: #feb47b;">Target Muscle</p>
                    <p class="text-base font-bold capitalize">{{ $exercise['target'] }}</p>
                </div>

                {{-- Equipment Card --}}
                <div class="p-4 rounded-md border-2"
                     style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.1), rgba(229, 229, 229, 0.05)); border-color: rgba(254, 180, 123, 0.3);">
                    <p class="text-xs opacity-70 mb-1" style="color: #feb47b;">Equipment</p>
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
                <a href="{{ route('muscleGroupsPage') }}" class="inline-block px-6 py-3 rounded-lg  bg-opacity-10 hover:bg-opacity-20 transition-all">
                    Go Back to Muscle Groups
                </a>
            </div>
        @endif

    </div>
</main>


{{-- Loading Animation Styles --}}
<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
@endsection
