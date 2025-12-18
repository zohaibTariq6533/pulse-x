@extends('layout.main')

@section('content')
{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen">
    <div class="max-w-xl mx-auto px-5 py-8 text-white w-full">
        
        {{-- Header Section --}}
        <header class="flex items-center justify-center p-2 rounded-2xl bg-opacity-5 mb-6 relative">
            <a href="{{ route('pplWorkoutPage') }}" class="absolute left-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold tracking-wide text-center">
                @if($dayName)
                    {{ $dayName }} Day Workout
                @else
                    My Workouts
                @endif
            </h1>
        </header>

        @if($workoutData && $dayName)
            {{-- Workout Exercises List --}}
            <div class="space-y-4 pb-6">
                @foreach($workoutData as $exercise)
                    <div class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 transition-all hover:scale-[1.02] hover:shadow-xl"
                         style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                             style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                        <div class="relative p-5">
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-lg font-bold tracking-tight">{{ $exercise['id'] }}. {{ $exercise['exercise'] }}</h2>
                            </div>
                            <div class="flex items-center gap-4 text-sm opacity-90">
                                <span class="font-medium">{{ $exercise['reps'] }} Reps</span>
                                <span class="font-medium">{{ $exercise['sets'] }} Sets</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- No workout selected --}}
            <div class="text-center py-12">
                <p class="text-lg opacity-70">Please select a workout day from the PPL page.</p>
                <a href="{{ route('pplWorkoutPage') }}" class="mt-4 inline-block px-6 py-3 rounded-lg bg-white bg-opacity-10 hover:bg-opacity-20 transition-all">
                    Go to PPL Workouts
                </a>
            </div>
        @endif

    </div>
</main>
@endsection