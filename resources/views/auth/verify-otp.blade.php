<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Please enter the 6-digit code sent to your email to verify your account.
    </div>

    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('otp.verify.perform') }}">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="code" :value="__('Verification Code')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" maxlength="6" required />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-primary-button>
                Verify Email
            </x-primary-button>

            <form method="POST" action="{{ route('otp.verify.resend') }}" class="inline">
                @csrf
                <input type="hidden" name="email" value="{{ old('email') }}" />
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Resend Code
                </button>
            </form>
        </div>
    </form>
</x-guest-layout>