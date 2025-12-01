<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Check your account’s email verification and admin approval status.
    </div>

    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('approval.status') }}" class="space-y-4">
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ $email }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button>
            Check Status
        </x-primary-button>
    </form>

    @if($user)
        <div class="mt-6 p-4 border rounded-lg">
            <div class="text-sm text-gray-700 mb-2">Account: <strong>{{ $user->name }}</strong> ({{ $user->email }})</div>
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status['email_verified'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $status['email_verified'] ? 'Email Verified' : 'Email Not Verified' }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status['is_approved'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $status['is_approved'] ? 'Admin Approved' : 'Pending Admin Approval' }}
                </span>
            </div>
            @unless($status['is_approved'])
                <p class="mt-3 text-sm text-gray-600">Once approved by an administrator, you can sign in.</p>
            @endunless
        </div>
    @elseif($email)
        <div class="mt-6 text-sm text-red-600">No account found for that email.</div>
    @endif

    <div class="mt-8 text-sm">
        <a href="{{ route('verify-otp') }}" class="underline text-gray-600 hover:text-gray-900">Verify Email with OTP</a>
        <span class="mx-2">•</span>
        <a href="{{ route('login') }}" class="underline text-gray-600 hover:text-gray-900">Go to Sign in</a>
    </div>
</x-guest-layout>