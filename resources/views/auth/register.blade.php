<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 px-4">
        <div class="w-full max-w-4xl grid md:grid-cols-2 bg-white rounded-xl shadow-lg overflow-hidden">

            {{-- 🔹 LEFT SIDE (COMPACT BRANDING) --}}
            <div class="hidden md:flex flex-col justify-center items-center bg-gray-300 text-black p-6 relative">
                <img src="{{ asset('images/banscanlogo.png') }}" class="h-12 mb-4">
                <h2 class="text-2xl font-bold mb-2 text-center">
                    Join BanScan
                </h2>

                <p class="text-black/80 text-center text-xs leading-relaxed">
                    Smart inventory & sales management with barcode system
                </p>

                <div class="absolute bottom-4 text-[10px] text-black/50">
                    © {{ date('Y') }} BanScan
                </div>
            </div>

            {{-- 🔹 RIGHT SIDE (FORM - COMPACT) --}}
            <div class="p-6 md:p-8">
                <h2 class="text-xl font-semibold mb-4">
                    Create Account
                </h2>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- NAME --}}
                    <div>
                        <label class="text-xs font-medium text-gray-600">Name</label>

                        <input type="text"
                            name="name"
                            placeholder="Enter your name"
                            value="{{ old('name') }}"
                            required autofocus
                            class="w-full mt-1 px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="text-xs font-medium text-gray-600">Email</label>

                        <input type="email"
                            name="email"
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                            required
                            class="w-full mt-1 px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="relative">
                        <label class="text-xs font-medium text-gray-600">Password</label>

                        <input id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="Enter your password"
                            class="w-full mt-1 px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none pr-9">

                        <button type="button"
                            onclick="togglePassword('password', this)"
                            class="absolute right-2 top-8 text-gray-500 hover:text-indigo-600">

                            <x-heroicon-o-eye class="w-4 h-4 eye-open" />
                            <x-heroicon-o-eye-slash class="w-4 h-4 hidden eye-close" />
                        </button>

                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="relative">
                        <label class="text-xs font-medium text-gray-600">Confirm Password</label>

                        <input id="confirm_password"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="Confirm your password"
                            class="w-full mt-1 px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none pr-9">

                        <button type="button"
                            onclick="togglePassword('confirm_password', this)"
                            class="absolute right-2 top-8 text-gray-500 hover:text-indigo-600">

                            <x-heroicon-o-eye class="w-4 h-4 eye-open" />
                            <x-heroicon-o-eye-slash class="w-4 h-4 hidden eye-close" />
                        </button>

                        @error('password_confirmation')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ROLE --}}
                    <div>
                        <label class="text-xs font-medium text-gray-600">Role</label>

                        <select name="role"
                            class="w-full mt-1 px-3 py-2 text-sm border rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                            <option value="cashier">Cashier</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    {{-- BUTTON --}}
                    <button type="submit"
                        class="w-full py-2 text-sm bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md font-medium hover:scale-[1.02] transition">
                        Register
                    </button>

                    {{-- LOGIN --}}
                    <p class="text-xs text-center text-gray-500">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">
                            Login
                        </a>
                    </p>

                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const open = btn.querySelector('.eye-open');
        const close = btn.querySelector('.eye-close');

        if (input.type === "password") {
            input.type = "text";
            open.classList.add("hidden");
            close.classList.remove("hidden");
        } else {
            input.type = "password";
            open.classList.remove("hidden");
            close.classList.add("hidden");
        }
    }
    </script>

</x-guest-layout>