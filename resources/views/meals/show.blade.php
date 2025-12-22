@extends('layout.main')
@section('content')

{{-- Main Page Structure --}}
<main style="background-image: linear-gradient(to bottom, #0F2027, #203A43, #2C5364);" class="min-h-screen pb-20">
    <div class="max-w-xl mx-auto px-5 py-8 text-white space-y-6">
        {{-- Header --}}
        <header class="relative  p-2 rounded-2xl">
            <a href="{{ route('meals.index', ['date' => $date]) }}" class="absolute left-2 top-1/2 transform -translate-y-1/2 p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="text-center py-2">
                <h1 class="text-2xl font-bold tracking-wide">{{ $mealInfo['label'] }}</h1>
                {{-- <p class="text-sm tracking-wider opacity-70">{{ date('M j, Y', strtotime($date)) }}</p> --}}
            </div>
            {{-- Date Selector --}}
            {{-- <input type="date"
                   id="dateSelector"
                   value="{{ $date }}"
                   class="px-3 py-2 rounded-lg  bg-opacity-10 border border-white border-opacity-20 text-white focus:outline-none focus:ring-2 focus:ring-[#d58548]"
                   onchange="window.location.href='{{ route('meals.show', ['mealType' => $mealType]) }}?date=' + this.value"> --}}
        </header>

        {{-- Nutrition Summary Card --}}
        @if(!$mealLogs->isEmpty())
        <section class="p-5 rounded-xl shadow-2xl"
                 style="background-image: linear-gradient(to bottom right, transparent, rgba(229, 229, 229, 0.2));">
            <div class="space-y-4">
                <h2 class="text-lg font-bold mb-4">{{ $mealInfo['label'] }} Summary</h2>

                @php
                    $mealCalories = $mealLogs->sum('total_calories');
                    $mealProtein = $mealLogs->sum('total_protein');
                    $mealCarbs = $mealLogs->sum('total_carbs');
                    $mealFats = $mealLogs->sum('total_fats');
                @endphp

                <div class="grid grid-cols-2 gap-4 mb-4">
                    {{-- Calories --}}
                    <div class="space-y-2">
                        <p class="text-xs opacity-70">Calories</p>
                        <p class="text-3xl font-bold">{{ number_format($mealCalories) }}</p>
                        <p class="text-xs opacity-70">kcal</p>
                    </div>
                    
                    {{-- Protein --}}
                    <div class="space-y-2">
                        <p class="text-xs opacity-70">Protein</p>
                        <p class="text-3xl font-bold">{{ number_format($mealProtein, 1) }}g</p>
                        <p class="text-xs opacity-70">protein</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white border-opacity-10">
                    <div>
                        <p class="text-xs opacity-70">Carbs</p>
                        <p class="text-xl font-bold">{{ number_format($mealCarbs, 1) }}g</p>
                    </div>
                    <div>
                        <p class="text-xs opacity-70">Fats</p>
                        <p class="text-xl font-bold">{{ number_format($mealFats, 1) }}g</p>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- Add Food Button --}}
        <div class="flex justify-center">
            <button onclick="openFoodModal('{{ $mealType }}')"
                    class="px-6 py-3 rounded-full bg-[#d58548] hover:bg-[#b8733a] text-white text-lg font-semibold transition flex items-center gap-2 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Food
            </button>
        </div>

        {{-- Meal Items --}}
        <section class="space-y-3">
            @if($mealLogs->isEmpty())
                <div class="p-8 rounded-xl border border-white border-opacity-20 bg-opacity-[0.10] text-center">
                    {{-- <p class="text-lg opacity-70 mb-2">{{ $mealInfo['icon'] }}</p> --}}
                    <p class="text-sm opacity-70">No foods logged yet</p>
                    <p class="text-xs opacity-50 mt-2">Click "Add Food" to get started</p>
                </div>
            @else
                @foreach($mealLogs as $log)
                    <div class="p-4 rounded-md border border-[#feb47b] border-opacity-20 bg-opacity-[0.10] flex justify-between items-center">
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-semibold">{{ $log->food_name }}</h3>
                                <button onclick="deleteMealLog({{ $log->id }})"
                                        class="text-red-400 hover:text-red-300 ml-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs opacity-70">{{ $log->quantity }}x {{ $log->serving_size }}</p>
                            <p class="text-sm font-bold text-[#feb47b] mt-1">{{ number_format($log->total_calories) }} kcal</p>
                        </div>
                    </div>
                @endforeach

                {{-- Meal Total --}}
                {{-- <div class="p-6 rounded-xl bg-linear-to-r from-[#feb47b] to-[#cd7e41] border border-white border-opacity-20 shadow-lg">
                    <div class="text-center">
                        <h3 class="text-lg font-bold mb-2">{{ $mealInfo['label'] }} Total</h3>
                        <p class="text-2xl font-bold">{{ number_format($mealTotal) }} kcal</p>
                        <p class="text-sm opacity-90 mt-1">{{ $mealLogs->count() }} food{{ $mealLogs->count() > 1 ? 's' : '' }}</p>
                    </div>
                </div> --}}
            @endif
        </section>

    </div>
</main>

{{-- Food Search Modal --}}
@include('meals.partials.food-search-modal')


<script>
    let currentMealType = '{{ $mealType }}';
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
