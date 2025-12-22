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
                Transform Your Body
            </p>
        </div>

        <!-- Signup Card -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl border border-white/20 shadow-[0_8px_30px_rgba(0,0,0,0.3)] p-8 max-w-md mx-auto">
            <form action="{{route('signup')}}" method="post" class="space-y-6">
                @csrf
                
                <!-- Welcome Heading -->
                <div class="text-center space-y-2">
                    <h2 class="text-3xl font-bold text-white">Create Account</h2>
                    <p class="text-white/70 text-sm">
                        Start your fitness journey today
                    </p>
                </div>

                <!-- First Name Field -->
                <div class="space-y-2">
                    <label class="block">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="first_name" 
                                placeholder="First Name"
                                required
                                value="{{ old('first_name') }}"
                                class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-md text-white placeholder-white/50 focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all"
                            />
                        </div>
                    </label>
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="block">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                placeholder="Email"
                                required
                                value="{{ old('email') }}"
                                class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-md text-white placeholder-white/50 focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all"
                            />
                        </div>
                    </label>
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <label class="block">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                placeholder="Password"
                                required
                                class="w-full pl-12 pr-12 py-3 bg-white/10 border border-white/20 rounded-md text-white placeholder-white/50 focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all"
                            />
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center"
                            >
                                <svg id="eye-icon" class="w-5 h-5 text-white/70 hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-off-icon" class="w-5 h-5 text-white/70 hover:text-white transition-colors hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </label>
                </div>

                <!-- Signup Button -->
                <button 
                    type="submit"
                    class="w-full h-14 bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53] rounded-2xl text-white font-bold text-base tracking-wider shadow-[0_6px_12px_rgba(255,107,107,0.4)] hover:shadow-[0_8px_16px_rgba(255,107,107,0.5)] transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                >
                    SIGN UP
                </button>
            </form>
        </div>

        <!-- Login Link -->
        <div class="flex items-center justify-center space-x-2">
            <p class="text-white/70 text-sm">Already have an account?</p>
            <a href="{{route('login-Page')}}" class="text-[#FF8E53] font-bold text-sm hover:underline">
                Log In
            </a>
        </div>
    </div>
</div>
@endsection
