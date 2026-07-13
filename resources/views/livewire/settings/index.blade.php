<div class="max-w-7xl mx-auto px-4 sm:px-0">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold">Settings</h1>
            <p class="text-gray-500">Manage your account and preferences</p>
        </div>
    </div>

    {{-- PROFILE + PASSWORD --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">

        {{-- PROFILE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- HEADER --}}
             <div class="border-b bg-gray-50 px-6 py-4 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Profile Information</h2>
                <x-heroicon-o-user class="w-6 h-6 text-green-600" />
            </div>

            <div class="p-5 sm:p-6">
                {{-- USER --}}
                <div class="flex items-center gap-4 pb-5 border-b mb-5">
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold shadow">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>

                    <div>
                        <p class="font-semibold text-lg text-gray-800">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>

                {{-- FORM --}}
                <div class="space-y-5">
                    {{-- NAME --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input
                            type="text"
                            wire:model="name"
                            class="w-full border rounded px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                        @error('name')
                            <p class="text-sm text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input
                            type="email"
                            wire:model="email"
                            class="w-full border rounded px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                        @error('email')
                            <p class="text-sm text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- ROLE --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Role
                        </label>

                        <div class="flex items-center justify-between border rounded px-4 py-3 bg-gray-50">
                            <span class="text-gray-700 capitalize font-medium">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <button
                        wire:click="save"
                        class="w-full bg-gray-800 hover:bg-gray-900 text-white py-3 rounded transition">
                        Save Changes
                    </button>
                    <div
                        wire:loading.flex
                        wire:target="save"
                        class="fixed inset-0 z-[99999] items-center justify-center bg-black/10 backdrop-blur-sm">
                        <img
                            src="{{ asset('images/loading.png') }}"
                            class="h-8 w-auto animate-pulse">
                    </div>
                </div>
            </div>
        </div>

        {{-- PASSWORD CARD --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            {{-- HEADER --}}
            <div class="border-b bg-gray-50 px-6 py-4 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Password & Security</h2>
                <x-heroicon-o-shield-check class="w-6 h-6 text-green-600" />
            </div>

            <div class="p-5 sm:p-6">
                {{-- PASSWORD FORM --}}
                <div class="space-y-5">
                    {{-- CURRENT --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Current Password
                        </label>

                        <input
                            type="password"
                            wire:model="current_password"
                            class="w-full border rounded px-4 py-3 pr-10">

                        <button
                            type="button"
                            onclick="togglePassword(this)"
                            class="absolute right-3 top-[42px] text-gray-400">
                            <x-heroicon-o-eye class="w-5 h-5" />
                            <x-heroicon-o-eye-slash class="w-5 h-5 hidden" />
                        </button>
                    </div>

                    @error('current_password')
                        <p class="text-sm text-red-500 -mt-3">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- NEW --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            New Password
                        </label>

                        <input
                            type="password"
                            wire:model="new_password"
                            class="w-full border rounded px-4 py-3 pr-10">

                        <button
                            type="button"
                            onclick="togglePassword(this)"
                            class="absolute right-3 top-[42px] text-gray-400">

                            <x-heroicon-o-eye class="w-5 h-5" />
                            <x-heroicon-o-eye-slash class="w-5 h-5 hidden" />
                        </button>
                    </div>

                    @error('new_password')
                        <p class="text-sm text-red-500 -mt-3">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- CONFIRM --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm Password
                        </label>

                        <input
                            type="password"
                            wire:model="new_password_confirmation"
                            class="w-full border rounded px-4 py-3 pr-10">

                        <button
                            type="button"
                            onclick="togglePassword(this)"
                            class="absolute right-3 top-[42px] text-gray-400">

                            <x-heroicon-o-eye class="w-5 h-5" />
                            <x-heroicon-o-eye-slash class="w-5 h-5 hidden" />
                        </button>
                    </div>

                    @error('new_password_confirmation')
                        <p class="text-sm text-red-500 -mt-3">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- BUTTON --}}
                    <button
                        wire:click="updatePassword"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded transition">
                        Update Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Two-column layout for Notifications & Danger Zone --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        {{-- Notifications Card --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b bg-gray-50 px-6 py-4 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Notifications</h2>
                <x-heroicon-o-bell class="w-6 h-6 text-green-600" />
            </div>
            <div class="divide-y">
                @php
                    $toggles = [
                        ['label' => 'Low Stock Alerts', 'desc' => 'Get notified when product stock runs low', 'model' => 'notify_low_stock'],
                        ['label' => 'Sales Updates', 'desc' => 'Daily summary of sales activity', 'model' => 'notify_sales'],
                        ['label' => 'New Items', 'desc' => 'When new products are added to inventory', 'model' => 'notify_new_items'],
                    ];
                @endphp
                @foreach($toggles as $toggle)
                    <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-800">{{ $toggle['label'] }}</p>
                            <p class="text-sm text-gray-500">{{ $toggle['desc'] }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="{{ $toggle['model'] }}" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-indigo-600 transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-5"></div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Danger Zone Card --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-red-100">
            <div class="border-b bg-red-50 px-6 py-4 flex items-center justify-between">
                <h2 class="font-semibold text-red-600">Danger Zone</h2>
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600" />
            </div>
            <div class="p-6 space-y-5">
                {{-- Logout --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="font-medium text-gray-800">Sign out</p>
                        <p class="text-sm text-gray-500">End your current session</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="confirmSignOut(event)">
                        @csrf
                        <button type="submit" class="border border-gray-300 text-gray-700 px-5 py-2 rounded hover:bg-gray-50 transition w-full sm:w-auto">
                            Sign Out
                        </button>
                    </form>
                </div>
                {{-- Delete Account --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                    <div>
                        <p class="font-medium text-gray-800">Delete account</p>
                        <p class="text-sm text-gray-500">Permanently remove your account and all data</p>
                    </div>
                    <button wire:click="deleteAccount"
                        class="bg-red-600 text-white px-5 py-2 rounded hover:bg-red-700 transition w-full sm:w-auto">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert & togglePassword --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
        // Custom event for email change success    
        Livewire.on('emailChanged', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Email Changed',
                    html: `
                        A verification link has been sent to your new email address.<br><br>
                        You must verify your email before logging in again.
                    `,
                    confirmButtonText: 'Login Again',
                    allowOutsideClick: false,
                    allowEscapeKey: false

                }).then(() => {

                    window.location.href = '/email-change-logout';

                });

            });
            // Success alert
            Livewire.on('saved', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'Changes updated successfully',
                    showConfirmButton: false,
                    timer: 1500,
                    background: '#fff',
                    backdrop: 'rgba(0,0,0,0.2)'
                });
            });

            // No changes alert
            Livewire.on('noChanges', () => {
                Swal.fire({
                    icon: 'info',
                    title: 'No Changes',
                    text: 'Please make changes before saving.',
                    showConfirmButton: false,
                    timer: 1800
                });
            });

            // Delete account confirmation
            Livewire.on('confirmDeleteAccount', () => {
                Swal.fire({
                    title: 'Delete Account?',
                    text: 'This action is permanent and cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('proceedDeleteAccount');
                    }
                });
            });
        });

        // Sign out confirmation
        function confirmSignOut(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Sign out?',
                text: 'You will be redirected to the login page.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, sign me out',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }

        // Password toggle (unchanged)
        function togglePassword(button) {
            const container = button.closest('.relative');
            const input = container.querySelector('input');
            const icons = button.querySelectorAll('svg');
            if (!input || !icons.length) return;
            if (input.type === 'password') {
                input.type = 'text';
                icons[0]?.classList.add('hidden');
                icons[1]?.classList.remove('hidden');
            } else {
                input.type = 'password';
                icons[0]?.classList.remove('hidden');
                icons[1]?.classList.add('hidden');
            }
        }
    </script>
</div>