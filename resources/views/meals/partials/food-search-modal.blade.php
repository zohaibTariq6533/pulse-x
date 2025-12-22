{{-- Food Search Modal --}}
<div id="foodModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="backdrop-blur-md bg-white/20 rounded-2xl max-w-md w-full max-h-[80vh] overflow-hidden border border-white/20 shadow-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Search Food</h2>
                <button onclick="closeFoodModal()" class="text-white opacity-70 hover:opacity-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <div class="flex gap-2">
                    <input type="text" 
                           id="foodSearchInput" 
                           placeholder="Search for food..."
                           onkeypress="if(event.key === 'Enter') searchFoods()"
                           class="flex-1 px-4 py-3 rounded-lg backdrop-blur-md bg-white/10 border border-white/20 text-white placeholder-white placeholder-opacity-50 focus:outline-none focus:ring-2 focus:ring-[#d58548] transition">
                    <button onclick="searchFoods()" 
                            class="px-4 py-3 rounded-lg bg-[#d58548] hover:bg-[#b8733a] text-white transition focus:outline-none focus:ring-2 focus:ring-[#d58548] shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div id="searchLoading" class="hidden text-center py-4">
                <p class="text-white opacity-70">Searching...</p>
            </div>

            <div id="searchResults" class="max-h-96 overflow-y-auto">
                <p class="text-white opacity-50 text-center py-4">Enter a food name and click search</p>
            </div>
        </div>
    </div>
</div>

{{-- Quantity Modal --}}
<div id="quantityModal" class="hidden fixed inset-0 bg-black/30 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="backdrop-blur-md bg-white/20 rounded-2xl max-w-md w-full border border-white/20 shadow-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Add Food</h2>
                <button onclick="closeQuantityModal()" class="text-white opacity-70 hover:opacity-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-white font-semibold mb-2" id="quantityFoodName"></p>
                <p class="text-white opacity-70 text-sm">Base serving: <span id="quantityServingSize"></span></p>
            </div>

            {{-- Input Type Toggle --}}
            <div class="mb-4">
                <label class="block text-white text-sm font-semibold mb-2">Input Type</label>
                <div class="flex gap-2 mb-3">
                    <button id="inputTypeGrams" 
                            onclick="switchInputType('grams')"
                            class="flex-1 py-2 px-4 rounded-lg bg-[#d58548] hover:bg-[#b8733a] text-white font-semibold transition shadow-lg">
                        Grams
                    </button>
                    <button id="inputTypeQuantity" 
                            onclick="switchInputType('quantity')"
                            class="flex-1 py-2 px-4 rounded-lg backdrop-blur-md bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold transition">
                        Quantity
                    </button>
                </div>
            </div>

            {{-- Grams Input (Default) --}}
            <div id="gramsInputSection" class="mb-4">
                <label class="block text-white text-sm font-semibold mb-2">Weight (grams)</label>
                <input type="number" 
                       id="quantityInput" 
                       step="1" 
                       min="1"
                       placeholder="Enter weight in grams"
                       oninput="updateNutritionDisplay()"
                       style="color: white;"
                       class="w-full px-4 py-3 rounded-lg backdrop-blur-md bg-white/10 border border-white/20 text-white placeholder-white placeholder-opacity-50 focus:outline-none focus:ring-2 focus:ring-[#d58548] transition">
            </div>

            {{-- Quantity Input (Hidden by default) --}}
            <div id="quantityInputSection" class="mb-4 hidden">
                <label class="block text-white text-sm font-semibold mb-2">Quantity</label>
                <div class="flex gap-2">
                    <select id="portionSelect" 
                            onchange="updateNutritionDisplay()"
                            style="color: white;"
                            class="flex-1 px-4 py-3 rounded-lg backdrop-blur-md bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-[#d58548] transition">
                        <option value="" style="background-color: rgba(0, 0, 0, 0.3); color: white;">Select portion size</option>
                    </select>
                    <input type="number" 
                           id="quantityAmount" 
                           step="0.1" 
                           min="0.1"
                           value="1"
                           placeholder="Amount"
                           oninput="updateNutritionDisplay()"
                           style="color: white;"
                           class="w-24 px-4 py-3 rounded-lg backdrop-blur-md bg-white/10 border border-white/20 text-white placeholder-white placeholder-opacity-50 focus:outline-none focus:ring-2 focus:ring-[#d58548] transition">
                </div>
            </div>

            <input type="hidden" id="quantityFoodId">
            <input type="hidden" id="quantityFdcId">
            <input type="hidden" id="quantityServingWeightGrams">
            <input type="hidden" id="currentInputType" value="grams">

            {{-- Nutrition Display --}}
            <div id="nutritionDisplay" class="mb-4 p-4 rounded-lg backdrop-blur-md bg-white/10 border border-white/20 shadow-lg">
                <h3 class="text-white font-semibold mb-3 text-sm">Nutrition Information</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-white opacity-70">Calories</p>
                        <p class="text-lg font-bold text-[#d58548]" id="displayCalories">0</p>
                    </div>
                    <div>
                        <p class="text-xs text-white opacity-70">Protein</p>
                        <p class="text-lg font-bold text-white" id="displayProtein">0g</p>
                    </div>
                    <div>
                        <p class="text-xs text-white opacity-70">Carbs</p>
                        <p class="text-lg font-bold text-white" id="displayCarbs">0g</p>
                    </div>
                    <div>
                        <p class="text-xs text-white opacity-70">Fats</p>
                        <p class="text-lg font-bold text-white" id="displayFats">0g</p>
                    </div>
                </div>
            </div>

            <button onclick="logFood()" 
                    class="w-full py-3 rounded-lg bg-[#d58548] hover:bg-[#b8733a] text-white font-semibold transition shadow-lg">
                Add to Meal
            </button>
        </div>
    </div>
</div>

