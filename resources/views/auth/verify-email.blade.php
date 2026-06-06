<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 text-center">
            <h2 class="text-xl font-semibold mb-3">
                Verify Email
            </h2>

            <p class="text-xs text-gray-500 mb-4">
                Check your email and click the verification link.
            </p>

            @if (session('status') == 'verification-link-sent')
                <p class="text-green-600 text-xs mb-4">
                    Verification link sent again!
                </p>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button class="w-full py-2 text-sm bg-indigo-600 text-white rounded-md mb-3">
                    Resend Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="text-xs text-gray-500 hover:text-black underline">
                    Logout
                </button>
            </form>
        </div>
    </div>

    @if (session('warning'))
        <div class="fixed top-4 right-4 bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg shadow-md">
            {{ session('warning') }}
        </div>    
    @endif
</x-guest-layout>