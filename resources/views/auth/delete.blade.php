<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img src="logo/auto-logo.png" class="logo-img" alt="" style="max-height:100px;">
            </a>
        </x-slot>

        <!-- Session Status -->
        <!--<x-auth-session-status class="mb-4" :status="session('status')" />-->

        <!-- Validation Errors -->
        <!--<x-auth-validation-errors class="mb-4" :errors="$errors" />-->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/delete/account">
            @csrf
            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autofocus />
                @error('email')
                    <span class="text-red-500 text-sm mt-1 text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3">
                    {{ __('Delete Account') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
