@extends('layout.main')
@section('content')

{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen pb-20">
    <div class="max-w-xl mx-auto px-5 py-8 text-white space-y-6">
        {{-- Header --}}
        <header class="flex justify-center  items-center p-2 rounded-2xl">
            <div class=" flex justify-between w-full">
                <div class="">
                    <a href="{{ route('dashboard') }}" class="p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                </div>
                <div class=" flex flex-col justify-center items-center w-full text-center">
                    <h1 class="text-2xl font-bold tracking-wide">Meal Logger</h1>
                    <p class="text-sm tracking-wider opacity-70">Track your daily nutrition</p>
                </div>
            </div>
            {{-- Date Selector --}}
            {{-- <input type="date" 
                   id="dateSelector" 
                   value="{{ $date }}" 
                   class="px-3 py-2 rounded-lg  bg-opacity-10 border border-white border-opacity-20 text-white focus:outline-none focus:ring-2 focus:ring-[#d58548]"
                   onchange="window.location.href='?date=' + this.value"> --}}
        </header>

        {{-- Daily Summary Card --}}
        <section class="p-5 rounded-xl shadow-2xl" 
                 style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.2));">
            <div class="space-y-4">
                <h2 class="text-lg font-bold">Daily Nutrition</h2>
                
                {{-- Calories --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm opacity-70">Calories</span>
                        <span class="text-sm font-bold">{{ number_format($dailyStats['consumed']['calories']) }} / {{ number_format($dailyStats['goals']['calories']) }} kcal</span>
                    </div>
                    @php
                        $caloriesPercent = $dailyStats['goals']['calories'] > 0 ? min(100, ($dailyStats['consumed']['calories'] / $dailyStats['goals']['calories']) * 100) : 0;
                        $caloriesRemaining = $dailyStats['remaining']['calories'];
                    @endphp
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full bg-[#feb47b]" style="width: {{ $caloriesPercent }}%;"></div>
                    </div>
                    <p class="text-xs mt-1 {{ $caloriesRemaining >= 0 ? 'text-[#feb47b]' : 'text-red-400' }}">
                        {{ $caloriesRemaining >= 0 ? 'Remaining: ' . number_format($caloriesRemaining) : 'Over by: ' . number_format(abs($caloriesRemaining)) }} kcal
                    </p>
                </div>

                {{-- Protein --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm opacity-70">Protein</span>
                        <span class="text-sm font-bold">{{ number_format($dailyStats['consumed']['protein'], 1) }}g / {{ number_format($dailyStats['goals']['protein'], 1) }}g</span>
                    </div>
                    @php
                        $proteinPercent = $dailyStats['goals']['protein'] > 0 ? min(100, ($dailyStats['consumed']['protein'] / $dailyStats['goals']['protein']) * 100) : 0;
                    @endphp
                    <div class="relative h-2 rounded-full bg-white bg-opacity-20">
                        <div class="absolute top-0 left-0 h-2 rounded-full bg-[#feb47b] " style="width: {{ $proteinPercent }}%;"></div>
                    </div>
                </div>

                {{-- Carbs, Fats --}}
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div>
                        <p class="text-xs opacity-70">Carbs</p>
                        <p class="text-lg font-bold">{{ number_format($dailyStats['consumed']['carbs'], 1) }}g</p>
                    </div>
                    <div>
                        <p class="text-xs opacity-70">Fats</p>
                        <p class="text-lg font-bold">{{ number_format($dailyStats['consumed']['fats'], 1) }}g</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Meal Cards Grid --}}
        <section class="mt-8">
            <h2 class="text-xl font-bold mb-4">Meals</h2>

            @php
                $mealTypes = [
                    'breakfast' => ['label' => 'Breakfast'],
                    'lunch' => ['label' => 'Lunch'],
                    'dinner' => ['label' => 'Dinner'],
                    'snacks' => ['label' => 'Snacks']
                ];
            @endphp

            <div class="grid grid-cols-2 gap-4">
                @foreach($mealTypes as $mealType => $mealInfo)
                    @php
                        $mealLogsForType = $mealLogs[$mealType] ?? collect();
                        $mealTotal = $mealLogsForType->sum('total_calories');
                        $foodCount = $mealLogsForType->count();
                    @endphp

                    <div class="relative">
                        {{-- Clickable Card --}}
                        <a href="{{ route('meals.show', ['mealType' => $mealType, 'date' => $date]) }}"
                           class="block p-4 rounded-md border border-[#feb47b] border-opacity-20 bg-opacity-[0.10] hover:bg-opacity-20 transition-all duration-200 cursor-pointer group">
                            <div class="text-center space-y-2">
                                <h3 class="font-semibold text-lg">{{ $mealInfo['label'] }}</h3>

                                @if($foodCount > 0)
                                    <div class="space-y-1">
                                        <p class="text-sm opacity-70">{{ $foodCount }} food{{ $foodCount > 1 ? 's' : '' }}</p>
                                        <p class="text-lg font-bold text-[#feb47b]">{{ number_format($mealTotal) }} kcal</p>
                                    </div>
                                @else
                                    <div class="space-y-1">
                                        <p class="text-sm opacity-50">No foods yet</p>
                                        <p class="text-sm opacity-30">0 kcal</p>
                                    </div>
                                @endif
                            </div>
                        </a>

                        {{-- Add Food Button --}}
                        <button onclick="openFoodModal('{{ $mealType }}')"
                                class="absolute -bottom-2 -right-2 w-10 h-10 rounded-full bg-[#d58548] hover:bg-[#b8733a] text-white font-bold text-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center group-hover:scale-110">
                            +
                        </button>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
</main>

{{-- Food Search Modal --}}
@include('meals.partials.food-search-modal')


<script>
    let currentMealType = 'breakfast';
    let currentFoodNutrition = null;
    let currentFoodPortions = [];
    let currentInputType = 'grams';

    function openFoodModal(mealType) {
        currentMealType = mealType;
        document.getElementById('foodModal').classList.remove('hidden');
        document.getElementById('foodSearchInput').focus();
    }

    function closeFoodModal() {
        document.getElementById('foodModal').classList.add('hidden');
        document.getElementById('foodSearchInput').value = '';
        document.getElementById('searchResults').innerHTML = '<p class="text-white opacity-50 text-center py-4">Enter a food name and click search</p>';
        document.getElementById('searchLoading').classList.add('hidden');
    }

    function searchFoods() {
        const query = document.getElementById('foodSearchInput').value.trim();
        
        if (query.length < 2) {
            document.getElementById('searchResults').innerHTML = '<p class="text-white opacity-50 text-center py-4">Please enter at least 2 characters</p>';
            return;
        }

        document.getElementById('searchLoading').classList.remove('hidden');
        
        fetch('{{ route("meals.search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ q: query })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('searchLoading').classList.add('hidden');
            const resultsDiv = document.getElementById('searchResults');
            
            if (data.foods && data.foods.length > 0) {
                resultsDiv.innerHTML = data.foods.map(food => `
                    <div onclick="selectFood(${food.fdc_id}, '${food.name.replace(/'/g, "\\'")}')" 
                         class="p-3 rounded-lg border border-white border-opacity-20 bg-opacity-[0.10] cursor-pointer hover:bg-opacity-20 transition mb-2">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-white">${food.name}</h3>
                                        <p class="text-sm text-[#feb47b] mt-1">${food.calories} kcal</p>
                                    </div>
                                    ${food.cached ? '<span class="text-xs text-white opacity-50">Cached</span>' : ''}
                                </div>
                    </div>
                `).join('');
            } else {
                resultsDiv.innerHTML = '<p class="text-white text-sm opacity-70 text-center py-4">No foods found</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('searchLoading').classList.add('hidden');
            document.getElementById('searchResults').innerHTML = '<p class="text-white text-sm opacity-70 text-center py-4 text-red-400">Error searching for foods. Please try again.</p>';
        });
    }

    function selectFood(fdcId, foodName) {
        // Get food details
        fetch(`{{ url('meals/food') }}/${fdcId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.food) {
                    showQuantityModal(data.food, foodName);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading food details');
            });
    }

    function showQuantityModal(food, foodName) {
        const quantityModal = document.getElementById('quantityModal');
        const servingWeightGrams = parseFloat(food.serving_weight_grams || 100);
        
        document.getElementById('quantityFoodName').textContent = foodName;
        document.getElementById('quantityFoodId').value = food.id || food.fdc_id;
        document.getElementById('quantityFdcId').value = food.fdc_id;
        document.getElementById('quantityServingWeightGrams').value = servingWeightGrams;
        document.getElementById('quantityInput').value = servingWeightGrams; // Default to one serving weight
        document.getElementById('quantityServingSize').textContent = food.serving_size || '100g';
        
        // Store portions data
        currentFoodPortions = food.portions || [];
        
        // Debug log to see what we're getting
        console.log('Food data received:', food);
        console.log('Portions extracted:', currentFoodPortions);
        
        // Populate portion select dropdown
        populatePortionSelect();
        
        // Store nutrition data for calculation (per gram)
        currentFoodNutrition = {
            serving_weight_grams: servingWeightGrams,
            calories_per_gram: parseFloat(food.calories_per_serving || 0) / servingWeightGrams,
            protein_per_gram: parseFloat(food.protein_per_serving || 0) / servingWeightGrams,
            carbs_per_gram: parseFloat(food.carbs_per_serving || 0) / servingWeightGrams,
            fats_per_gram: parseFloat(food.fats_per_serving || 0) / servingWeightGrams
        };
        
        // Reset to grams input type
        switchInputType('grams');
        updateNutritionDisplay();
        
        document.getElementById('foodModal').classList.add('hidden');
        quantityModal.classList.remove('hidden');
    }

    function populatePortionSelect() {
        const select = document.getElementById('portionSelect');
        select.innerHTML = '<option value="" style="background-color: #1a1a2e; color: white;">Select portion size</option>';
        
        console.log('Current food portions:', currentFoodPortions); // Debug log
        
        if (currentFoodPortions && Array.isArray(currentFoodPortions) && currentFoodPortions.length > 0) {
            currentFoodPortions.forEach((portion, index) => {
                const option = document.createElement('option');
                const label = portion.portionDescription || 
                             (portion.modifier ? `${portion.modifier} ${portion.amount || ''}`.trim() : 'Portion ' + (index + 1));
                option.value = index;
                const gramWeight = portion.gramWeight || 0;
                option.textContent = label + (gramWeight > 0 ? ` (${gramWeight}g)` : '');
                option.style.backgroundColor = '#1a1a2e';
                option.style.color = 'white';
                select.appendChild(option);
            });
        } else {
            // If no portions available, show message
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No portion sizes available';
            option.disabled = true;
            option.style.backgroundColor = '#1a1a2e';
            option.style.color = '#888';
            select.appendChild(option);
        }
    }

    function switchInputType(type) {
        currentInputType = type;
        document.getElementById('currentInputType').value = type;
        
        const gramsSection = document.getElementById('gramsInputSection');
        const quantitySection = document.getElementById('quantityInputSection');
        const gramsBtn = document.getElementById('inputTypeGrams');
        const quantityBtn = document.getElementById('inputTypeQuantity');
        
        if (type === 'grams') {
            gramsSection.classList.remove('hidden');
            quantitySection.classList.add('hidden');
            gramsBtn.classList.remove('bg-white/10', 'hover:bg-white/20');
            gramsBtn.classList.add('bg-[#d58548]');
            quantityBtn.classList.remove('bg-[#d58548]');
            quantityBtn.classList.add('bg-white/10', 'hover:bg-white/20');
        } else {
            gramsSection.classList.add('hidden');
            quantitySection.classList.remove('hidden');
            quantityBtn.classList.remove('bg-white/10', 'hover:bg-white/20');
            quantityBtn.classList.add('bg-[#d58548]');
            gramsBtn.classList.remove('bg-[#d58548]');
            gramsBtn.classList.add('bg-white/10', 'hover:bg-white/20');
        }
        
        updateNutritionDisplay();
    }

    function closeQuantityModal() {
        document.getElementById('quantityModal').classList.add('hidden');
        currentFoodNutrition = null;
        currentFoodPortions = [];
        currentInputType = 'grams';
    }

    function updateNutritionDisplay() {
        if (!currentFoodNutrition) {
            return;
        }

        let grams = 0;

        if (currentInputType === 'grams') {
            grams = parseFloat(document.getElementById('quantityInput').value) || 0;
        } else {
            const portionIndex = document.getElementById('portionSelect').value;
            const quantity = parseFloat(document.getElementById('quantityAmount').value) || 0;
            
            if (portionIndex !== '' && currentFoodPortions[portionIndex]) {
                const portion = currentFoodPortions[portionIndex];
                grams = (portion.gramWeight || 0) * quantity;
            }
        }
        
        // Calculate totals based on grams
        const calories = currentFoodNutrition.calories_per_gram * grams;
        const protein = currentFoodNutrition.protein_per_gram * grams;
        const carbs = currentFoodNutrition.carbs_per_gram * grams;
        const fats = currentFoodNutrition.fats_per_gram * grams;

        // Update display
        document.getElementById('displayCalories').textContent = Math.round(calories);
        document.getElementById('displayProtein').textContent = protein.toFixed(1) + 'g';
        document.getElementById('displayCarbs').textContent = carbs.toFixed(1) + 'g';
        document.getElementById('displayFats').textContent = fats.toFixed(1) + 'g';
    }

    function logFood() {
        const foodId = document.getElementById('quantityFoodId').value;
        const fdcId = document.getElementById('quantityFdcId').value;
        const servingWeightGrams = parseFloat(document.getElementById('quantityServingWeightGrams').value || 100);
        const date = '{{ $date }}';
        const inputType = document.getElementById('currentInputType').value;

        let grams = 0;
        let servingDescription = '';

        if (inputType === 'grams') {
            grams = parseFloat(document.getElementById('quantityInput').value);
            servingDescription = grams + 'g';
        } else {
            const portionIndex = document.getElementById('portionSelect').value;
            const quantity = parseFloat(document.getElementById('quantityAmount').value) || 0;
            
            if (portionIndex === '') {
                alert('Please select a portion size');
                return;
            }
            
            const portion = currentFoodPortions[portionIndex];
            grams = (portion.gramWeight || 0) * quantity;
            const portionLabel = portion.portionDescription || portion.modifier || 'Portion';
            servingDescription = `${quantity} ${portionLabel}`;
        }

        if (!grams || grams <= 0) {
            alert('Please enter a valid quantity');
            return;
        }

        // Convert grams to servings ratio for backend compatibility
        const quantity = grams / servingWeightGrams;

        fetch('{{ route("meals.log") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                fdc_id: fdcId ? parseInt(fdcId) : null,
                food_id: foodId ? parseInt(foodId) : null,
                meal_type: currentMealType,
                date: date,
                quantity: quantity,
                serving_size: servingDescription
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeQuantityModal();
                window.location.reload();
            } else {
                alert(data.message || 'Error logging food');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error logging food');
        });
    }

    function deleteMealLog(id) {
        if (!confirm('Are you sure you want to remove this food?')) {
            return;
        }

        fetch(`{{ url('meals/log') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Error deleting meal log');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting meal log');
        });
    }

    // Close modals when clicking outside
    document.getElementById('foodModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeFoodModal();
        }
    });

    document.getElementById('quantityModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeQuantityModal();
        }
    });
</script>

@endsection

