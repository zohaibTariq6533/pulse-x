@extends('layout.main')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0F2027] via-[#203A43] to-[#2C5364] flex items-center justify-center p-6">
    <div class="w-full max-w-md space-y-6">
        @if(session('error'))
        <div id="errorMsg" class="bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-xl backdrop-blur-sm flex items-center justify-between shadow-[0_4px_12px_rgba(239,68,68,0.3)]">
            <p class="text-sm font-medium">{{ session('error') }}</p>
            <button onclick="document.getElementById('errorMsg').classList.add('hidden')" 
                class="text-red-300 hover:text-red-200 transition-colors ml-4 flex-shrink-0">
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

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl border border-white/20 shadow-[0_8px_30px_rgba(0,0,0,0.3)] p-8 max-w-md mx-auto">
            <form action="{{route('login-user')}}" method="post" class="space-y-6">
                @csrf
                
                <!-- Welcome Heading -->
                <div class="text-center space-y-2">
                    <h2 class="text-3xl font-bold text-white">Welcome Back</h2>
                    <p class="text-white/70 text-sm">
                        Log in to continue your fitness journey
                    </p>
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
                                class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all"
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
                                class="w-full pl-12 pr-12 py-3 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-white/50 focus:outline-none focus:border-[#FF8E53] focus:ring-2 focus:ring-[#FF8E53] transition-all"
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

                <!-- Forgot Password -->
                <div class="flex justify-end">
                    <a href="#" class="text-[#FF8E53] font-semibold text-sm hover:underline">
                        Forgot Password?
                    </a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit"
                    class="w-full h-14 bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53] rounded-2xl text-white font-bold text-base tracking-wider shadow-[0_6px_12px_rgba(255,107,107,0.4)] hover:shadow-[0_8px_16px_rgba(255,107,107,0.5)] transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                >
                    LOGIN
                </button>

                <!-- Divider -->
                <div class="flex items-center space-x-4">
                    <div class="flex-1 h-px bg-white/30"></div>
                    <span class="text-white/60 font-semibold text-sm">OR</span>
                    <div class="flex-1 h-px bg-white/30"></div>
                </div>

                <!-- Social Login Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Google Button -->
                    <button 
                        type="button"
                        class="h-12 bg-white/10 border border-white/20 rounded-xl text-white font-semibold flex items-center justify-center space-x-2 hover:bg-white/20 transition-all"
                    >
                        <svg class="w-6 h-6" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Google</span>
                    </button>

                    <!-- Apple Button -->
                    <button 
                        type="button"
                        class="h-12 bg-white/10 border border-white/20 rounded-xl text-white font-semibold flex items-center justify-center space-x-2 hover:bg-white/20 transition-all"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                        </svg>
                        <span>Apple</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Sign Up Link -->
        <div class="flex items-center justify-center space-x-2">
            <p class="text-white/70 text-sm">Don't have an account?</p>
            <a href="{{route('signup-page1')}}" class="text-[#FF8E53] font-bold text-sm hover:underline">
                Sign Up
            </a>
        </div>
    </div>
</div>
@endsection