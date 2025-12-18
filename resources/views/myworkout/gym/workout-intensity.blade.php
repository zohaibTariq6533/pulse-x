@extends('layout.main')

@section('content')
{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen flex flex-col">
    <div class="max-w-xl mx-auto px-5 py-8 text-white w-full flex flex-col flex-1">
        
        {{-- Header Section --}}
        <header class="flex items-center justify-center p-2 rounded-2xl bg-opacity-5 mb-6 relative">
            <a href="{{ route('workoutTypePage') }}" class="absolute left-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold tracking-wide text-center">Choose workout intensity</h1>
        </header>

        {{-- Step Indicator --}}
        <div class="flex items-center justify-center space-x-2 mb-8">
            <div class="h-1.5 w-16 rounded-full" style="background-color: #feb47b;"></div>
            <div class="h-1.5 w-16 rounded-full" style="background-color: #feb47b;"></div>
            <div class="h-1.5 w-16 rounded-full bg-white bg-opacity-20"></div>
        </div>

        {{-- Workout Intensity Cards --}}
        <div class="space-y-4 flex flex-col items-center justify-center flex-1">
            
            {{-- Low Intensity Card --}}
            <a href="{{ route('createWorkoutPlanPage', ['intensity' => 'low']) }}">
            <div class="p-6 rounded-xl border-2 cursor-pointer transition-all hover:scale-[1.02] min-h-[140px] flex flex-col justify-between w-full"
                 style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.15), rgba(229, 229, 229, 0.05)); border-color: rgba(229, 229, 229, 0.3);">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-xl font-bold">Low</h3>
                            @if(session('reccomended_intensity') == 'low')
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: rgba(255, 182, 123, 0.3); color: #feb47b;">Recommended</span>
                            @endif
                        </div>
                        <p class="text-sm opacity-80 leading-relaxed">Perfect for beginners or active recovery days. Focus on form and building a foundation.</p>
                    </div>
                    <div class="ml-4 shrink-0">
                        <svg class="w-6 h-6 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </div>
            </a>
            {{-- Medium Intensity Card --}}
            <a href="{{ route('createWorkoutPlanPage', ['intensity' => 'Medium']) }}">
            <div class="p-6 rounded-xl border-2 cursor-pointer transition-all hover:scale-[1.02] min-h-[140px] flex flex-col justify-between w-full"
                 style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.15), rgba(229, 229, 229, 0.05)); border-color: rgba(229, 229, 229, 0.3);">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-xl font-bold">Medium</h3>
                            @if(session('reccomended_intensity') == 'Medium')
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: rgba(255, 182, 123, 0.3); color: #feb47b;">Recommended</span>
                            @endif
                        </div>
                        <p class="text-sm opacity-80 leading-relaxed">Ideal for intermediate trainees. Balanced challenge with adequate recovery.</p>
                    </div>
                    <div class="ml-4 shrink-0">
                        <svg class="w-6 h-6 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </div>
            </a>
            {{-- High Intensity Card --}}
            <a href="{{ route('createWorkoutPlanPage', ['intensity' => 'High']) }}">
            <div class="p-6 rounded-xl border-2 cursor-pointer transition-all hover:scale-[1.02] min-h-[140px] flex flex-col justify-between w-full"
                 style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.15), rgba(229, 229, 229, 0.05)); border-color: rgba(229, 229, 229, 0.3);">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-2">High</h3>
                        <p class="text-sm opacity-80 leading-relaxed">For advanced athletes. Maximum intensity and volume for serious gains.</p>
                    </div>
                    <div class="ml-4 shrink-0">
                        <svg class="w-6 h-6 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </div>
            </a>
        </div>

    </div>
</main>
@endsection

