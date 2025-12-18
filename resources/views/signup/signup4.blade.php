@extends('layout.main')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0F2027] via-[#203A43] to-[#2C5364] flex items-center justify-center p-6">
    <div class="w-full max-w-md space-y-6">
        @if(session('error'))
        <div id="errorMsg" class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-xl backdrop-blur-sm flex items-center justify-between shadow-[0_4px_12px_rgba(239,68,68,0.3)]">
            <p class="text-sm font-medium">{{ session('error') }}</p>
            <button onclick="document.getElementById('errorMsg').classList.add('hidden')" 
                class="text-red-300 hover:text-red-200 transition-colors ml-4 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @endif

        @if(session('success'))
        <div id="successMsg" class="bg-green-500/20 border border-green-500/50 text-green-300 px-4 py-3 rounded-xl backdrop-blur-sm flex items-center justify-between shadow-[0_4px_12px_rgba(34,197,94,0.3)]">
            <p class="text-sm font-medium">{{ session('success') }}</p>
            <button onclick="document.getElementById('successMsg').classList.add('hidden')" 
                class="text-green-300 hover:text-green-200 transition-colors ml-4 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @endif

        <!-- Logo Section -->
        <div class="flex flex-col items-center">
            <!-- Logo Circle -->
            <div class="relative w-32 h-32 mb-6">
                <div class="absolute inset-0 bg-gradient-to-br from-[#FF6B6B] to-[#FF8E53] rounded-full shadow-[0_0_20px_rgba(255,107,107,0.4)] shadow-[#FF6B6B]">
                    <!-- Fitness Icon (Dumbbell) -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.57 14.86L22 13.43 20.57 12 17 15.57 8.43 7 12 3.43 11.29 2.72 11.28 2.71 9.86 1.29 8.71 2.43 2.43 8.71 1.29 9.86 2.72 11.29 2.71 11.28 3.43 12 7 8.43 15.57 17 12 20.57 12.71 21.28 12.72 21.29 14.14 22.71 15.29 21.57 21.57 15.29 22.71 14.14 21.29 12.72 21.28 12.71 20.57 14.86z"/>
                        </svg>
                    </div>
                    <!-- Pulse Dot -->
                    <div class="absolute top-4 right-5 w-3 h-3 bg-white rounded-full shadow-[0_0_8px_rgba(255,255,255,0.6)]"></div>
                </div>
            </div>
            
            <!-- App Title with Gradient -->
            <h1 class="text-5xl font-bold bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53] bg-clip-text text-transparent tracking-wider mb-2">
                PULSE X
            </h1>
            
            <!-- Subtitle -->
            <p class="text-white/70 text-base tracking-wide">
                What's Your Goal?
            </p>
        </div>

        <!-- Signup Card -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl border border-white/20 shadow-[0_8px_30px_rgba(0,0,0,0.3)] p-8 max-w-md mx-auto">
            <form action="{{route('signup4')}}" method="post" class="space-y-6">
                @csrf
                
                <!-- Welcome Heading -->
                <div class="text-center space-y-2">
                    <h2 class="text-3xl font-bold text-white">Choose Your Goal</h2>
                    <p class="text-white/70 text-sm">
                        Select what you want to achieve
                    </p>
                </div>

                <!-- Goal Radio Buttons -->
                <div class="space-y-4">
                    <label class="block text-white/90 text-sm font-medium mb-3">Fitness Goals</label>
                    
                    <!-- Weight Lose Option -->
                    <label class="flex items-center p-4 bg-white/5 border border-white/20 rounded-2xl cursor-pointer hover:bg-white/10 hover:border-[#FF8E53] transition-all group">
                        <input 
                            type="radio" 
                            name="goal" 
                            value="weight_lose"
                            {{ old('goal') == 'weight_lose' ? 'checked' : '' }}
                            class="w-5 h-5 text-[#FF8E53] bg-white/10 border-white/30 focus:ring-[#FF8E53] focus:ring-2"
                        />
                        <div class="ml-4 flex-1">
                            <div class="text-white font-semibold">Weight Lose</div>
                            <div class="text-white/60 text-sm">Reduce body weight</div>
                        </div>
                        <svg class="w-6 h-6 text-white/30 group-hover:text-[#FF8E53] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </label>

                    <!-- Weight Maintain Option -->
                    <label class="flex items-center p-4 bg-white/5 border border-white/20 rounded-2xl cursor-pointer hover:bg-white/10 hover:border-[#FF8E53] transition-all group">
                        <input 
                            type="radio" 
                            name="goal" 
                            value="weight_maintain"
                            {{ old('goal') == 'weight_maintain' ? 'checked' : '' }}
                            class="w-5 h-5 text-[#FF8E53] bg-white/10 border-white/30 focus:ring-[#FF8E53] focus:ring-2"
                        />
                        <div class="ml-4 flex-1">
                            <div class="text-white font-semibold">Weight Maintain</div>
                            <div class="text-white/60 text-sm">Keep current weight</div>
                        </div>
                        <svg class="w-6 h-6 text-white/30 group-hover:text-[#FF8E53] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </label>

                    <!-- Weight Gain Option -->
                    <label class="flex items-center p-4 bg-white/5 border border-white/20 rounded-2xl cursor-pointer hover:bg-white/10 hover:border-[#FF8E53] transition-all group">
                        <input 
                            type="radio" 
                            name="goal" 
                            value="weight_gain"
                            {{ old('goal') == 'weight_gain' ? 'checked' : '' }}
                            class="w-5 h-5 text-[#FF8E53] bg-white/10 border-white/30 focus:ring-[#FF8E53] focus:ring-2"
                        />
                        <div class="ml-4 flex-1">
                            <div class="text-white font-semibold">Weight Gain</div>
                            <div class="text-white/60 text-sm">Increase body weight</div>
                        </div>
                        <svg class="w-6 h-6 text-white/30 group-hover:text-[#FF8E53] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </label>

                    @if(isset($goesToGym) && $goesToGym)
                        <!-- Cut Option (only for gym goers) -->
                        <label class="flex items-center p-4 bg-white/5 border border-white/20 rounded-2xl cursor-pointer hover:bg-white/10 hover:border-[#FF8E53] transition-all group">
                            <input 
                                type="radio" 
                                name="goal" 
                                value="cut"
                                {{ old('goal') == 'cut' ? 'checked' : '' }}
                                class="w-5 h-5 text-[#FF8E53] bg-white/10 border-white/30 focus:ring-[#FF8E53] focus:ring-2"
                            />
                            <div class="ml-4 flex-1">
                                <div class="text-white font-semibold">Cut</div>
                                <div class="text-white/60 text-sm">Reduce body fat, maintain muscle</div>
                            </div>
                            <svg class="w-6 h-6 text-white/30 group-hover:text-[#FF8E53] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </label>

                        <!-- Bulk Option (only for gym goers) -->
                        <label class="flex items-center p-4 bg-white/5 border border-white/20 rounded-2xl cursor-pointer hover:bg-white/10 hover:border-[#FF8E53] transition-all group">
                            <input 
                                type="radio" 
                                name="goal" 
                                value="bulk"
                                {{ old('goal') == 'bulk' ? 'checked' : '' }}
                                class="w-5 h-5 text-[#FF8E53] bg-white/10 border-white/30 focus:ring-[#FF8E53] focus:ring-2"
                            />
                            <div class="ml-4 flex-1">
                                <div class="text-white font-semibold">Bulk</div>
                                <div class="text-white/60 text-sm">Build muscle mass</div>
                            </div>
                            <svg class="w-6 h-6 text-white/30 group-hover:text-[#FF8E53] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </label>
                    @endif
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full h-14 bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53] rounded-2xl text-white font-bold text-base tracking-wider shadow-[0_6px_12px_rgba(255,107,107,0.4)] hover:shadow-[0_8px_16px_rgba(255,107,107,0.5)] transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                >
                    CONTINUE
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

