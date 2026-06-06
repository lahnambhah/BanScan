<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-center">
                Confirm Password
            </h2>

            <p class="text-xs text-gray-500 mb-4 text-center">
                This is a secure area. Please confirm your password.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <div class="relative">
                    <label class="text-xs font-medium text-gray-600">Password</label>

                    <input id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full mt-1 px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-indigo-500 pr-9">

                    <button type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute right-2 top-8 text-gray-500">

                        <x-heroicon-o-eye class="w-4 h-4 eye-open"/>
                        <x-heroicon-o-eye-slash class="w-4 h-4 hidden eye-close"/>
                    </button>

                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-2 text-sm bg-indigo-600 text-white rounded-md hover:scale-[1.02] transition">
                    Confirm
                </button>
            </form>
        </div>
    </div>
    @include('auth.partials.toggle-script')
</x-guest-layout>