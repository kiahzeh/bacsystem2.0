<x-guest-layout>
    <!-- Background with animated gradient - matching dashboard -->
    <div class="fixed inset-0 bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-600 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-700 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
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

        <!-- Registration Card -->
        <div class="w-full sm:max-w-md px-8 py-8 bg-white/10 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/20">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Create Account</h1>
                <p class="text-white/70 text-sm">Join our procurement system today</p>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-white/90">
                        <i data-feather="user" class="inline w-4 h-4 mr-2"></i>
                        Full Name
                    </label>
                    <div class="relative">
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-300"
                               placeholder="Enter your full name">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i data-feather="user" class="h-5 w-5 text-white/50"></i>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300 text-sm" />
                </div>

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
                               autocomplete="username"
                               class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-300"
                               placeholder="Enter your email address">
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
                               autocomplete="new-password"
                               class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-300"
                               placeholder="Create a strong password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i data-feather="lock" class="h-5 w-5 text-white/50"></i>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300 text-sm" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-white/90">
                        <i data-feather="shield" class="inline w-4 h-4 mr-2"></i>
                        Confirm Password
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-300"
                               placeholder="Confirm your password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i data-feather="shield" class="h-5 w-5 text-white/50"></i>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300 text-sm" />
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" 
                               type="checkbox" 
                               required
                               class="rounded border-white/30 text-blue-600 shadow-sm focus:ring-blue-500 bg-white/10 backdrop-blur-sm">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-white/80">
                            I agree to the 
                            <a href="#" class="text-blue-300 hover:text-blue-200 underline">Terms of Service</a> 
                            and 
                            <a href="#" class="text-blue-300 hover:text-blue-200 underline">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-transparent">
                    <i data-feather="user-plus" class="inline w-5 h-5 mr-2"></i>
                    {{ __('Create Account') }}
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center">
                <div class="flex-1 border-t border-white/20"></div>
                <span class="px-4 text-white/50 text-sm">or</span>
                <div class="flex-1 border-t border-white/20"></div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-white/70 text-sm">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-blue-300 hover:text-blue-200 font-medium transition-colors duration-300">
                        Sign in here
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
