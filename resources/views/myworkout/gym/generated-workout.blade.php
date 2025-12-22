@extends('layout.main')

@section('content')
{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen flex flex-col">
    <div class="max-w-xl mx-auto px-5 py-8 text-white w-full flex flex-col flex-1">
        
        {{-- Header Section --}}
        <header class="flex items-center justify-center p-2 rounded-2xl bg-opacity-5 mb-6 relative">
            <a href="{{ route('dashboard') }}" class="absolute left-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold tracking-wide text-center">Your Workout Plan</h1>
        </header>

        {{-- Workout Summary --}}
        <div class="mb-6 p-4 rounded-xl border-2"
             style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.15), rgba(229, 229, 229, 0.05)); border-color: rgba(229, 229, 229, 0.3);">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="opacity-70">Type:</span>
                    <span class="font-semibold ml-2">{{ ucfirst($workoutPlan->type) }}</span>
                </div>
                <div>
                    <span class="opacity-70">Intensity:</span>
                    <span class="font-semibold ml-2">{{ ucfirst(strtolower($workoutPlan->intensity)) }}</span>
                </div>
                <div>
                    <span class="opacity-70">Total Days:</span>
                    <span class="font-semibold ml-2">{{ $plan['total_workouts'] ?? count($plan['days'] ?? []) }}</span>
                </div>
                <div>
                    <span class="opacity-70">Generated:</span>
                    <span class="font-semibold ml-2">{{ \Carbon\Carbon::parse($workoutPlan->created_at)->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Workout Days --}}
        @if(isset($plan['days']) && count($plan['days']) > 0)
            <div class="space-y-6 pb-6">
                @foreach($plan['days'] as $day)
                    <div class="rounded-2xl border-2 overflow-hidden"
                         style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); border-color: rgba(229, 229, 229, 0.3);">
                        
                        {{-- Day Header --}}
                        <div class="p-4 border-b border-white border-opacity-10"
                             style="background: linear-gradient(135deg, rgba(254, 180, 123, 0.2), rgba(255, 126, 95, 0.1));">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-bold">{{ $day['day_name'] }}</h2>
                                    <p class="text-sm opacity-70 mt-1">{{ count($day['exercises'] ?? []) }} exercises</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm opacity-70">Duration</p>
                                    <p class="text-lg font-semibold">{{ $day['estimated_duration_minutes'] ?? 60 }} min</p>
                                </div>
                            </div>
                        </div>

                        {{-- Exercises List --}}
                        <div class="p-4 space-y-3">
                            @foreach($day['exercises'] ?? [] as $index => $exercise)
                                @if(isset($exercise['exercise_id']) && $exercise['exercise_id'])
                                    {{-- Clickable exercise card with link --}}
                                    <a href="{{ route('exerciseDetailPage', ['exerciseId' => $exercise['exercise_id']]) }}" class="block">
                                        <div class="p-4 rounded-xl border border-white border-opacity-10 cursor-pointer transition-all hover:scale-[1.02] hover:border-opacity-30"
                                             style="background: rgba(229, 229, 229, 0.05);">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-sm font-semibold opacity-70">#{{ $index + 1 }}</span>
                                                        <h3 class="text-lg font-bold">{{ $exercise['name'] }}</h3>
                                                        <svg class="w-4 h-4 opacity-50 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                        </svg>
                                                    </div>
                                                    @if(isset($exercise['target']) && $exercise['target'])
                                                        <p class="text-xs opacity-60 mb-2">Target: {{ ucfirst($exercise['target']) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-4 text-sm">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                    <span class="font-semibold">{{ $exercise['sets'] }} Sets</span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                    <span class="font-semibold">{{ $exercise['reps'] }} Reps</span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="font-semibold">{{ $exercise['rest_seconds'] }}s Rest</span>
                                                </div>
                                            </div>

                                            @if(isset($exercise['equipment']) && $exercise['equipment'])
                                                <div class="mt-2">
                                                    <span class="text-xs opacity-60">Equipment: {{ ucfirst($exercise['equipment']) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @else
                                    {{-- Non-clickable exercise card (no exercise_id) --}}
                                    <div class="p-4 rounded-xl border border-white border-opacity-10"
                                         style="background: rgba(229, 229, 229, 0.05);">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-sm font-semibold opacity-70">#{{ $index + 1 }}</span>
                                                    <h3 class="text-lg font-bold">{{ $exercise['name'] }}</h3>
                                                </div>
                                                @if(isset($exercise['target']) && $exercise['target'])
                                                    <p class="text-xs opacity-60 mb-2">Target: {{ ucfirst($exercise['target']) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-4 text-sm">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                <span class="font-semibold">{{ $exercise['sets'] }} Sets</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                <span class="font-semibold">{{ $exercise['reps'] }} Reps</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-semibold">{{ $exercise['rest_seconds'] }}s Rest</span>
                                            </div>
                                        </div>

                                        @if(isset($exercise['equipment']) && $exercise['equipment'])
                                            <div class="mt-2">
                                                <span class="text-xs opacity-60">Equipment: {{ ucfirst($exercise['equipment']) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col space-y-3 w-full pb-6">
                <a href="{{ route('workoutTypePage') }}" 
                   class="w-full py-3 px-6 rounded-xl font-semibold text-white transition-all hover:scale-[1.02] text-center"
                   style="background: linear-gradient(135deg, #feb47b, #ff7e5f);">
                    Create New Plan
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="w-full py-3 px-6 rounded-xl font-semibold text-white transition-all hover:scale-[1.02] border-2 text-center"
                   style="border-color: rgba(229, 229, 229, 0.3); background: rgba(229, 229, 229, 0.1);">
                    Back to Dashboard
                </a>
            </div>
        @else
            {{-- No workout data --}}
            <div class="text-center py-12">
                <p class="text-lg opacity-70 mb-4">No workout plan found.</p>
                <a href="{{ route('workoutTypePage') }}" class="inline-block px-6 py-3 rounded-lg bg-white bg-opacity-10 hover:bg-opacity-20 transition-all">
                    Create New Plan
                </a>
            </div>
        @endif

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 p-4 rounded-xl bg-green-500 bg-opacity-90 border border-green-400 text-white text-sm z-50">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 p-4 rounded-xl bg-red-500 bg-opacity-90 border border-red-400 text-white text-sm z-50">
                {{ session('error') }}
            </div>
        @endif

    </div>
</main>

<script>
    // Auto-hide success/error messages after 5 seconds
    setTimeout(function() {
        const messages = document.querySelectorAll('.fixed');
        messages.forEach(msg => {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        });
    }, 5000);
</script>
@endsection

