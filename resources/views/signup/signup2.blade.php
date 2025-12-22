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
                Complete Your Profile
            </p>
        </div>

        <!-- Signup Card -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl border border-white/20 shadow-[0_8px_30px_rgba(0,0,0,0.3)] p-8 max-w-md mx-auto">
            <form action="{{route('signup2')}}" method="post" class="space-y-6">
                @csrf
                
                <!-- Welcome Heading -->
                <div class="text-center space-y-2">
                    <h2 class="text-3xl font-bold text-white">Personal Details</h2>
                    <p class="text-white/70 text-sm">
                        Tell us about yourself
                    </p>
                </div>

                <!-- Gender Field -->
                <div class="space-y-2">
                    <label class="block text-white/90 text-sm font-medium mb-2">Gender</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <select 
                            name="gender" 
                            class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-md text-white focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all appearance-none"
                        >
                            <option value="" class="bg-[#2C5364] text-white">Select Gender</option>
                            <option value="male" class="bg-[#2C5364] text-white" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" class="bg-[#2C5364] text-white" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Age Field -->
                <div class="space-y-2">
                    <label class="block text-white/90 text-sm font-medium mb-2">Age</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input 
                            type="number" 
                            name="age" 
                            placeholder="Enter your age"
                            min="1"
                            max="120"
                            value="{{ old('age') }}"
                            class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-md text-white placeholder-white/50 focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all"
                        />
                    </div>
                </div>

                <!-- Height Field -->
                <div class="space-y-2">
                    <label class="block text-white/90 text-sm font-medium mb-2">Height (cm)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                            </svg>
                        </div>
                        <input 
                            type="number" 
                            name="height" 
                            placeholder="Enter your height in cm"
                            step="0.1"
                            min="50"
                            max="250"
                            value="{{ old('height') }}"
                            class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-md text-white placeholder-white/50 focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all"
                        />
                    </div>
                </div>

                <!-- Weight Field -->
                <div class="space-y-2">
                    <label class="block text-white/90 text-sm font-medium mb-2">Weight (kg)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <input 
                            type="number" 
                            name="weight" 
                            placeholder="Enter your weight in kg"
                            step="0.1"
                            min="20"
                            max="300"
                            value="{{ old('weight') }}"
                            class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-md text-white placeholder-white/50 focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all"
                        />
                    </div>
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
