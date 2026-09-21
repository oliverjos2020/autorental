<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Delete Your Account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                This action cannot be undone. Please proceed with caution.
            </p>
        </div>

        @if($showConfirmation)
            <!-- Success Confirmation -->
            <div class="rounded-md bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">
                            Account Successfully Deleted
                        </h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>The account has been permanently removed from our system.</p>
                        </div>
                        <div class="mt-4">
                            <button wire:click="closeConfirmation" type="button" class="text-sm font-medium text-green-800 hover:text-green-600">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Delete Account Form -->
            <div class="mt-8 bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10" style="max-width:700px; margin: 0px auto">
                <form wire:submit.prevent="deleteAccount" class="space-y-6">
                    <!-- Warning Message -->
                    <div class="rounded-md bg-yellow-50 p-4 border border-yellow-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Warning: Permanent Action
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Deleting your account will permanently remove all your data, including bookings, vehicles, and personal information.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <div class="mt-1">
                            <input 
                                wire:model.defer="email" 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                class="appearance-none block w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter your email address"
                            >
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Security Challenge -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Security Verification
                        </label>
                        
                        <!-- Challenge Code Display -->
                        <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-4 mb-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Type this code exactly:</p>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-2xl font-bold tracking-widest text-gray-900 font-mono select-none" style="letter-spacing: 0.3em;">
                                            {{ $challengeCode }}
                                        </span>
                                    </div>
                                </div>
                                <button 
                                    wire:click="refreshCode" 
                                    type="button"
                                    class="ml-4 p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-md transition-colors"
                                    title="Generate new code"
                                >
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Challenge Input -->
                        <div class="mt-1">
                            <input 
                                wire:model.defer="challengeInput" 
                                type="text" 
                                class="appearance-none block w-full px-3 py-2 border @error('challengeInput') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono tracking-widest"
                                placeholder="Enter the code above"
                                maxlength="6"
                                style="text-transform: uppercase; letter-spacing: 0.3em;"
                            >
                        </div>
                        @error('challengeInput')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            For security, you must type the code shown above. Click the refresh icon to generate a new code.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-red bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Delete My Account Permanently</span>
                            <span wire:loading>
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Back Link -->
                    <div class="text-center">
                        <p class="mt-2">
                        <a href="{{ url('/login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Cancel and return to homepage
                        </a>
                        </p>
                    </div>
                </form>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        window.addEventListener('code-refreshed', event => {
            // Optional: Add toast notification
            console.log(event.detail.message);
        });

        window.addEventListener('account-deleted', event => {
            // Optional: Add toast notification
            console.log(event.detail.message);
        });
    </script>
    @endpush
</div>