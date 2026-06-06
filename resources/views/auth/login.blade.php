<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 px-6">
        
        <div class="w-full max-w-5xl grid md:grid-cols-2 bg-white rounded-2xl shadow-xl overflow-hidden">
            {{-- 🔹 LEFT SIDE (BRANDING) --}}
            <div class="hidden md:flex flex-col justify-center items-center bg-gray-300 text-black p-10 relative">
                {{-- LOGO --}}
                <img src="{{ asset('images/banscanlogo.png') }}" class="h-16 mb-6 animate-pulse">

                <h2 class="text-3xl font-bold mb-4 text-center">
                    Welcome to BanScan
                </h2>

                <p class="text-black/80 text-center text-sm">
                    Smart Inventory & Sales Management System with Barcode Technology
                </p>

                {{-- DECORATIVE --}}
                <div class="absolute bottom-6 text-xs text-black/50">
                    © {{ date('Y') }} BanScan
                </div>
            </div>

            {{-- 🔹 RIGHT SIDE (FORM) --}}
            <div class="p-10">
                <h2 class="text-2xl font-semibold mb-6">
                    Login to your account
                </h2>

                {{-- SESSION STATUS --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />
                
                @if(session('verify_email'))
                    <div class=" text-yellow-800">
                        {{ session('verify_email') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- EMAIL --}}
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email</label>

                        <input type="email"
                            name="email"
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                            required autofocus
                            class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- PASSWORD --}}
                    <div class="relative">
                        <label class="text-sm font-medium text-gray-600">Password</label>

                        <input id="password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                            class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none pr-10">

                        {{-- 👁 SHOW / HIDE --}}
                        <button type="button" 
                            onclick="togglePassword(this)"
                            class="absolute right-3 top-9 text-gray-500 hover:text-indigo-600">

                            <x-heroicon-o-eye class="w-5 h-5" id="eye-open" />
                            <x-heroicon-o-eye-slash class="w-5 h-5 hidden" id="eye-close" />
                        </button>

                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- REMEMBER + FORGOT --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="rounded">
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- BUTTON --}}
                    <button type="submit"
                        class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:scale-105 transition">
                        Log In
                    </button>

                    {{-- REGISTER --}}
                    <p class="text-sm text-center text-gray-500">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">
                            Register
                        </a>
                    </p>

                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
    function togglePassword(btn) {
        const input = document.getElementById('password');
        const open = btn.querySelector('#eye-open');
        const close = btn.querySelector('#eye-close');

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