<section>
    <header>
        <h2 class="text-lg font-medium text-white glass-heading">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-white/70 glass-text">
            {{ __("Update your account's profile information, username, and profile picture.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Picture Section -->
        <div class="space-y-4">
            <x-input-label for="profile_picture" :value="__('Profile Picture')" class="text-white" />
            
            <!-- Current Profile Picture -->
            @if($user->profile_picture && Storage::disk('public')->exists($user->profile_picture))
                <div class="flex items-center space-x-4">
                    <img src="{{ Storage::url($user->profile_picture) }}" 
                         alt="Current Profile Picture" 
                         class="w-20 h-20 rounded-full object-cover border-2 border-white/20"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <div>
                        <p class="text-sm text-white/70">Current profile picture</p>
                    </div>
                </div>
            @endif
            
            <!-- Upload New Picture -->
            <div class="flex items-center space-x-4">
                <input type="file" 
                       id="profile_picture" 
                       name="profile_picture" 
                       accept="image/*"
                       class="block w-full text-sm text-white/70 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-500 file:text-white hover:file:bg-violet-600 file:cursor-pointer cursor-pointer">
            </div>
            <p class="text-xs text-white/50">Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</p>
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-white" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full glassmorphism-input" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" class="text-white" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full glassmorphism-input" :value="old('username', $user->username)" autocomplete="username" placeholder="Enter your username (optional)" />
            <p class="text-xs text-white/50 mt-1">Username can contain letters, numbers, dashes, and underscores</p>
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-white" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full glassmorphism-input" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-white/70">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-violet-300 hover:text-violet-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="glassmorphism-button">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
