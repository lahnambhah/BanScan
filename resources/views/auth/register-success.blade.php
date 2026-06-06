<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="bg-white rounded-lg shadow-md p-8 max-w-md w-full text-center">
            {{-- Simple checkmark icon --}}
            <div class="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-xl font-semibold text-gray-800 mb-2">
                Registration Successful
            </h2>

            <p class="text-gray-500 text-sm mb-6">
                Please check your email for a verification link to activate your account.
            </p>

            <a href="{{ route('login') }}" 
               class="inline-block w-full sm:w-auto px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition text-sm font-medium">
                Go to Login
            </a>
        </div>
    </div>
</x-guest-layout>