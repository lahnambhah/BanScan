<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6">

            <h2 class="text-xl font-semibold mb-4 text-center">
                Forgot Password
            </h2>

            <p class="text-xs text-gray-500 mb-4 text-center">
                Enter your email and we’ll send you a reset link.
            </p>

            @if (session('status'))

                <div class="mb-4 p-4 text-green-700">
                    <p class="font-medium">
                        Password reset link sent successfully.
                    </p>

                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('password.request') }}"
                            class="flex-1 text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                            Resend Link
                        </a>

                        <a href="{{ route('login') }}"
                            class="flex-1 text-center bg-gray-800 text-white px-4 py-2 rounded hover:bg-black text-sm">
                            Go to Login
                        </a>
                    </div>
                </div>

            @else

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-xs font-medium text-gray-600">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full mt-1 px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-indigo-500">

                        @error('email')
                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full py-2 text-sm bg-indigo-600 text-white rounded-md hover:scale-[1.02] transition">
                        Send Reset Link
                    </button>
                </form>

            @endif

        </div>
    </div>
</x-guest-layout>