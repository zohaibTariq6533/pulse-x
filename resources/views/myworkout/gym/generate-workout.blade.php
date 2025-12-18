@extends('layout.main')

@section('content')
{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen flex flex-col">
    <div class="max-w-xl mx-auto px-5 py-8 text-white w-full flex flex-col flex-1">
        
        {{-- Header Section --}}
        <header class="flex items-center justify-center p-2 rounded-2xl bg-opacity-5 mb-6 relative">
            <a href="{{ route('workoutIntensityPage', ['type' => session('workout_type')]) }}" class="absolute left-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold tracking-wide text-center">Review details</h1>
        </header>

        {{-- Step Indicator --}}
        <div class="flex items-center justify-center space-x-2 mb-8">
            <div class="h-1.5 w-16 rounded-full" style="background-color: #feb47b;"></div>
            <div class="h-1.5 w-16 rounded-full" style="background-color: #feb47b;"></div>
            <div class="h-1.5 w-16 rounded-full" style="background-color: #feb47b;"></div>
        </div>

        {{-- Review Details Card --}}
        <div class="flex flex-col items-center justify-center flex-1">
            <div class="p-6 rounded-xl border-2 w-full mb-6"
                 style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.15), rgba(229, 229, 229, 0.05)); border-color: rgba(229, 229, 229, 0.3);">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm opacity-80">Workout Type:</span>
                        <span class="text-base font-semibold">{{ $userDetail->goes_to_gym=='1'? 'Gym' : 'Home' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm opacity-80">Workout Split:</span>
                        <span class="text-base font-semibold">
                            @if($workoutData->type == 'ppl')
                                Push Pull Legs
                            @elseif($workoutData->type == 'bro')
                                Bro Split
                            @else
                                {{ ucfirst($workoutData->type) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm opacity-80">Intensity:</span>
                        <span class="text-base font-semibold">{{ ucfirst(strtolower($workoutData->intensity)) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm opacity-80">Goal:</span>
                        <span class="text-base font-semibold">{{$userDetail->goal}}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm opacity-80">Activity Level:</span>
                        <span class="text-base font-semibold">{{ ucfirst($userDetail->activity_level) }}</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col space-y-3 w-full">
                <button class="w-full py-3 px-6 rounded-xl font-semibold text-white transition-all hover:scale-[1.02]"
                        style="background: linear-gradient(135deg, #feb47b, #ff7e5f);">
                    Generate Workout
                </button>
                <button class="w-full py-3 px-6 rounded-xl font-semibold text-white transition-all hover:scale-[1.02] border-2"
                        style="border-color: rgba(229, 229, 229, 0.3); background: rgba(229, 229, 229, 0.1);">
                    Cancel
                </button>
            </div>
        </div>

    </div>
</main>
@endsection
