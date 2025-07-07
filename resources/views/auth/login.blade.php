<x-guest-layout>
    <!-- Background with animated gradient - matching dashboard -->
    <div class="fixed inset-0 bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-700">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-violet-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute top-40 left-40 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <!-- Main content -->
    <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <a href="/" class="transform hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-28 w-auto drop-shadow-2xl">
            </a>
        </div>

        <!-- Login Card -->
        <div class="w-full sm:max-w-md px-8 py-8 bg-white/10 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/20">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
                <p class="text-white/70 text-sm">Sign in to your account to continue</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 p-4 bg-green-500/20 backdrop-blur-sm border border-green-500/30 rounded-lg text-green-100" :status="session('status')" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-white/90">
                        <i data-feather="mail" class="inline w-4 h-4 mr-2"></i>
                        Email Address
                    </label>
                    <div class="relative">
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent transition-all duration-300"
                               placeholder="Enter your email">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i data-feather="mail" class="h-5 w-5 text-white/50"></i>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300 text-sm" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-white/90">
                        <i data-feather="lock" class="inline w-4 h-4 mr-2"></i>
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent transition-all duration-300"
                               placeholder="Enter your password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i data-feather="lock" class="h-5 w-5 text-white/50"></i>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300 text-sm" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember"
                               class="rounded border-white/30 text-violet-600 shadow-sm focus:ring-violet-500 bg-white/10 backdrop-blur-sm">
                        <span class="ml-2 text-sm text-white/80">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-violet-300 hover:text-violet-200 transition-colors duration-300 underline" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-violet-400 focus:ring-offset-2 focus:ring-offset-transparent">
                    <i data-feather="log-in" class="inline w-5 h-5 mr-2"></i>
                    {{ __('Sign In') }}
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center">
                <div class="flex-1 border-t border-white/20"></div>
                <span class="px-4 text-white/50 text-sm">or</span>
                <div class="flex-1 border-t border-white/20"></div>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-white/70 text-sm">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-violet-300 hover:text-violet-200 font-medium transition-colors duration-300">
                        Create one here
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-white/50 text-sm">
                © {{ date('Y') }} {{ config('app.name', 'Procurement System') }}. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Custom CSS for animations -->
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        
        .animate-blob {
            animation: blob 7s infinite;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.5);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.7);
        }
    </style>

    <!-- Initialize Feather Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
</x-guest-layout>
