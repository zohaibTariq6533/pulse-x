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
            <h1 class="text-xl font-bold tracking-wide text-center">Push Pull Legs</h1>
        </header>

        {{-- Workout Days Cards --}}
        <div class="space-y-4 pb-6">
            
            {{-- Day 1 - Push --}}
            <a href="{{ route('workoutsPage', ['day' => 'push']) }}">
                <div class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 cursor-pointer transition-all hover:scale-[1.02] hover:shadow-xl"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-5 flex items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold mb-1 tracking-tight">Day 1 - Push</h2>
                            <p class="text-sm opacity-70 mb-3 font-medium">7 workouts</p>
                            <p class="text-sm opacity-90 leading-relaxed font-medium">Chest + Tricep + Front Delt</p>
                        </div>
                        <svg class="w-6 h-6 opacity-60 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Day 2 - Pull --}}
            <a href="{{ route('workoutsPage', ['day' => 'pull']) }}">
                <div class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 cursor-pointer transition-all hover:scale-[1.02] hover:shadow-xl"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-5 flex items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold mb-1 tracking-tight">Day 2 - Pull</h2>
                            <p class="text-sm opacity-70 mb-3 font-medium">7 workouts</p>
                            <p class="text-sm opacity-90 leading-relaxed font-medium">Back + Bicep + Rear Delt</p>
                        </div>
                        <svg class="w-6 h-6 opacity-60 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Day 3 - Legs --}}
            <a href="{{ route('workoutsPage', ['day' => 'legs']) }}">
                <div class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 cursor-pointer transition-all hover:scale-[1.02] hover:shadow-xl"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-5 flex items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold mb-1 tracking-tight">Day 3 - Legs</h2>
                            <p class="text-sm opacity-70 mb-3 font-medium">7 workouts</p>
                            <p class="text-sm opacity-90 leading-relaxed font-medium">Quad + Hamstring + Glute + Calf</p>
                        </div>
                        <svg class="w-6 h-6 opacity-60 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Day 4 - Push --}}
            <a href="{{ route('workoutsPage', ['day' => 'push']) }}">
                <div class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 cursor-pointer transition-all hover:scale-[1.02] hover:shadow-xl"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-5 flex items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold mb-1 tracking-tight">Day 4 - Push</h2>
                            <p class="text-sm opacity-70 mb-3 font-medium">7 workouts</p>
                            <p class="text-sm opacity-90 leading-relaxed font-medium">Chest + Tricep + Front Delt</p>
                        </div>
                        <svg class="w-6 h-6 opacity-60 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Day 5 - Pull --}}
            <a href="{{ route('workoutsPage', ['day' => 'pull']) }}">
                <div class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 cursor-pointer transition-all hover:scale-[1.02] hover:shadow-xl"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-5 flex items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold mb-1 tracking-tight">Day 5 - Pull</h2>
                            <p class="text-sm opacity-70 mb-3 font-medium">7 workouts</p>
                            <p class="text-sm opacity-90 leading-relaxed font-medium">Back + Bicep + Rear Delt</p>
                        </div>
                        <svg class="w-6 h-6 opacity-60 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Day 6 - Legs --}}
            <a href="{{ route('workoutsPage', ['day' => 'legs']) }}">
                <div class="relative overflow-hidden rounded-2xl border border-white border-opacity-20 cursor-pointer transition-all hover:scale-[1.02] hover:shadow-xl"
                     style="background: linear-gradient(135deg, rgba(229, 229, 229, 0.18), rgba(229, 229, 229, 0.08)); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <div class="absolute left-0 top-0 bottom-0 w-20 opacity-10"
                         style="background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent); background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 50%), radial-gradient(circle at 50% 80%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);"></div>
                    <div class="relative p-5 flex items-start">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold mb-1 tracking-tight">Day 6 - Legs</h2>
                            <p class="text-sm opacity-70 mb-3 font-medium">7 workouts</p>
                            <p class="text-sm opacity-90 leading-relaxed font-medium">Quad + Hamstring + Glute + Calf</p>
                        </div>
                        <svg class="w-6 h-6 opacity-60 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

        </div>

    </div>
</main>
@endsection
