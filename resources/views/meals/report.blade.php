@extends('layout.main')
@section('content')

{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen pb-20">
    <div class="max-w-xl mx-auto px-5 py-8 text-white space-y-6">
        
        {{-- Header with Date Navigation --}}
        <header class="flex items-center justify-between p-2 rounded-2xl">
            <a href="{{ route('dashboard') }}" class="p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            
            <div class="flex items-center space-x-4 mr-5 flex-1 justify-center">
                <a href="{{ route('meals.report', ['date' => $prevDate]) }}" 
                   class="p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                
                <div class="text-center">
                    <h1 class="text-xl font-bold">Meal Report</h1>
                    <p class="text-sm opacity-70">
                        {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                        @if($date === $today)
                            <span class="text-[#feb47b]">(Today)</span>
                        @endif
                    </p>
                </div>
                
                <a href="{{ route('meals.report', ['date' => $nextDate]) }}" 
                   class="p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition {{ $nextDate > $today ? 'opacity-50 cursor-not-allowed' : '' }}"
                   @if($nextDate > $today) onclick="return false;" @endif>
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </header>

        {{-- Summary Statistics Card --}}
        <section class="p-5 rounded-xl shadow-2xl" 
                 style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.2));">
            <h2 class="text-lg font-bold mb-4">Daily Summary</h2>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                {{-- Calories --}}
                <div class="space-y-2">
                    <p class="text-xs opacity-70">Calories</p>
                    <p class="text-3xl font-bold">{{ number_format($dailyStats['consumed']['calories']) }}</p>
                    <p class="text-xs opacity-70">of {{ number_format($dailyStats['goals']['calories']) }} kcal</p>
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full bg-[#feb47b]" style="width: {{ $caloriesPercent }}%;"></div>
                    </div>
                </div>
                
                {{-- Protein --}}
                <div class="space-y-2">
                    <p class="text-xs opacity-70">Protein</p>
                    <p class="text-3xl font-bold">{{ number_format($dailyStats['consumed']['protein'], 1) }}g</p>
                    <p class="text-xs opacity-70">of {{ number_format($dailyStats['goals']['protein'], 1) }}g</p>
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full bg-[#feb47b]" style="width: {{ $proteinPercent }}%;"></div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white border-opacity-10">
                <div>
                    <p class="text-xs opacity-70">Carbs</p>
                    <p class="text-xl font-bold">{{ number_format($dailyStats['consumed']['carbs'], 1) }}g</p>
                </div>
                <div>
                    <p class="text-xs opacity-70">Fats</p>
                    <p class="text-xl font-bold">{{ number_format($dailyStats['consumed']['fats'], 1) }}g</p>
                </div>
            </div>
        </section>

        {{-- Charts Section --}}
        <section class="p-5 rounded-xl shadow-2xl" 
                 style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.2));">
            <h2 class="text-lg font-bold mb-4">Nutrition Breakdown</h2>
            
            {{-- Calories by Meal Type Chart --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold mb-3 opacity-80">Calories by Meal</h3>
                <canvas id="caloriesChart" height="200"></canvas>
            </div>
            
            {{-- Macronutrients Chart --}}
            <div>
                <h3 class="text-sm font-semibold mb-3 opacity-80">Macronutrients Distribution</h3>
                <canvas id="macrosChart" height="200"></canvas>
            </div>
        </section>

        {{-- Meal Breakdown Section --}}
        <section class="space-y-4">
            <h2 class="text-xl font-bold">Meal Breakdown</h2>
            
            @php
                $mealInfo = [
                    'breakfast' => ['label' => 'Breakfast', 'icon' => '🌅', 'color' => '#FFB74D'],
                    'lunch' => ['label' => 'Lunch', 'icon' => '☀️', 'color' => '#d58548'],
                    'dinner' => ['label' => 'Dinner', 'icon' => '🌙', 'color' => '#2196F3'],
                    'snacks' => ['label' => 'Snacks', 'icon' => '🍎', 'color' => '#E91E63']
                ];
            @endphp
            
            @foreach(['breakfast', 'lunch', 'dinner', 'snacks'] as $mealType)
                @php
                    $stats = $mealStats[$mealType];
                    $info = $mealInfo[$mealType];
                @endphp
                
                <div class="p-5 rounded-xl shadow-2xl border border-white border-opacity-10" 
                     style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.1));">
                    {{-- Meal Header --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            {{-- <span class="text-2xl">{{ $info['icon'] }}</span> --}}
                            <div>
                                <h3 class="text-lg font-bold">{{ $info['label'] }}</h3>
                                <p class="text-xs opacity-70">{{ $stats['food_count'] }} {{ $stats['food_count'] == 1 ? 'item' : 'items' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold" style="color: {{ $info['color'] }};">{{ number_format($stats['calories']) }}</p>
                            <p class="text-xs opacity-70">kcal</p>
                        </div>
                    </div>
                    
                    {{-- Meal Stats --}}
                    <div class="grid grid-cols-3 gap-3 mb-4 pb-4 border-b border-white border-opacity-10">
                        <div>
                            <p class="text-xs opacity-70">Protein</p>
                            <p class="text-sm font-semibold">{{ number_format($stats['protein'], 1) }}g</p>
                        </div>
                        <div>
                            <p class="text-xs opacity-70">Carbs</p>
                            <p class="text-sm font-semibold">{{ number_format($stats['carbs'], 1) }}g</p>
                        </div>
                        <div>
                            <p class="text-xs opacity-70">Fats</p>
                            <p class="text-sm font-semibold">{{ number_format($stats['fats'], 1) }}g</p>
                        </div>
                    </div>
                    
                    {{-- Food Items --}}
                    @if(count($stats['foods']) > 0)
                        <div class="space-y-2">
                            <p class="text-xs font-semibold opacity-80 mb-2">Food Items:</p>
                            @foreach($stats['foods'] as $food)
                                <div class="flex items-center justify-between p-2 rounded-lg  bg-opacity-5">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">{{ $food['name'] }}</p>
                                        <p class="text-xs opacity-70">{{ number_format($food['quantity'], 1) }} {{ $food['serving_size'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold">{{ number_format($food['calories']) }} kcal</p>
                                        <p class="text-xs opacity-70">{{ number_format($food['protein'], 1) }}g protein</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm opacity-50">No foods logged for this meal</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </section>

    </div>
</main>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Prepare data for charts
    const mealCalories = {
        breakfast: {{ $mealStats['breakfast']['calories'] }},
        lunch: {{ $mealStats['lunch']['calories'] }},
        dinner: {{ $mealStats['dinner']['calories'] }},
        snacks: {{ $mealStats['snacks']['calories'] }}
    };

    const macros = {
        protein: {{ $dailyStats['consumed']['protein'] }},
        carbs: {{ $dailyStats['consumed']['carbs'] }},
        fats: {{ $dailyStats['consumed']['fats'] }}
    };

    // Calories by Meal Chart
    const caloriesCtx = document.getElementById('caloriesChart').getContext('2d');
    new Chart(caloriesCtx, {
        type: 'bar',
        data: {
            labels: ['Breakfast', 'Lunch', 'Dinner', 'Snacks'],
            datasets: [{
                label: 'Calories',
                data: [
                    mealCalories.breakfast,
                    mealCalories.lunch,
                    mealCalories.dinner,
                    mealCalories.snacks
                ],
                backgroundColor: [
                    'rgba(255, 183, 77, 0.8)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(33, 150, 243, 0.8)',
                    'rgba(233, 30, 99, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 183, 77, 1)',
                    'rgba(76, 175, 80, 1)',
                    'rgba(33, 150, 243, 1)',
                    'rgba(233, 30, 99, 1)'
                ],
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toLocaleString() + ' kcal';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)',
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Macronutrients Pie Chart
    const macrosCtx = document.getElementById('macrosChart').getContext('2d');
    new Chart(macrosCtx, {
        type: 'doughnut',
        data: {
            labels: ['Protein', 'Carbs', 'Fats'],
            datasets: [{
                data: [macros.protein, macros.carbs, macros.fats],
                backgroundColor: [
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(33, 150, 243, 0.8)',
                    'rgba(255, 183, 77, 0.8)'
                ],
                borderColor: [
                    'rgba(76, 175, 80, 1)',
                    'rgba(33, 150, 243, 1)',
                    'rgba(255, 183, 77, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'rgba(255, 255, 255, 0.9)',
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = macros.protein + macros.carbs + macros.fats;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value.toFixed(1) + 'g (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>

@endsection

