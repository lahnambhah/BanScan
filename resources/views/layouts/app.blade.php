<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BanScan</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    @livewireStyles
</head>

<body class="bg-gray-100 font-sans antialiased overflow-x-hidden">

<div class="flex min-h-screen relative">

    {{-- MOBILE OVERLAY --}}
    <div id="overlay"
        class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"
        onclick="toggleSidebar()">
    </div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="
            w-60 bg-white shadow-lg flex flex-col h-screen
            fixed lg:static
            z-40
            transition-transform duration-300
            -translate-x-full lg:translate-x-0
        ">

        {{-- LOGO --}}
        <div class="flex items-center gap-3 px-6 py-6 border-b">
            <img src="{{ asset('images/banscanlogo.png') }}"
                class="h-12 w-auto">
        </div>

        {{-- MENU --}}
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

            {{-- DASHBOARD --}}
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded
                {{ request()->is('dashboard') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }}">
                <x-heroicon-o-home class="w-5 h-5" />
                Dashboard
            </a>

            {{-- ADMIN --}}
            @if(auth()->user()?->isAdmin())

            <a href="/categories"
                class="{{ request()->is('categories*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                <x-heroicon-o-tag class="w-5 h-5" />
                Categories
            </a>

            <a href="/items"
                class="{{ request()->is('items*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                <x-heroicon-o-cube class="w-5 h-5" />
                Items
            </a>

            <a href="/suppliers"
                class="{{ request()->is('suppliers*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                <x-heroicon-o-truck class="w-5 h-5" />
                Suppliers
            </a>

            <a href="/barcodes"
                class="{{ request()->is('barcodes*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                <x-heroicon-o-qr-code class="w-5 h-5" />
                Barcodes
            </a>

            <a href="/reports"
                class="{{ request()->is('reports*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                <x-heroicon-o-chart-bar class="w-5 h-5" />
                Reports
            </a>

            <a href="/settings"
                class="{{ request()->is('settings*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                <x-heroicon-o-cog class="w-5 h-5" />
                Settings
            </a>

            @endif

            {{-- CASHIER --}}
            @if(auth()->user()?->isCashier())

                <a href="/scan"
                    class="{{ request()->is('scan*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                    <x-heroicon-o-camera class="w-5 h-5" />
                    Scan Item
                </a>

                <a href="/returns"
                    class="{{ request()->is('returns*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                    <x-heroicon-o-arrow-uturn-left class="w-5 h-5" />
                    Returns
                </a>

                <a href="/sales-history"
                    class="{{ request()->is('sales-history*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                    <x-heroicon-o-clock class="w-5 h-5" />
                    Sales History
                </a>

                <a href="/settings"
                    class="{{ request()->is('settings*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-100' }} flex items-center gap-2 px-4 py-2 rounded">
                    <x-heroicon-o-cog class="w-5 h-5" />
                    Settings
                </a>
            @endif
        </nav>

        {{-- USER PANEL --}}
        <div class="p-4 border-t">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>

                <div>
                    <p class="text-sm font-semibold">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ auth()->user()->email }}
                    </p>

                    <p class="text-xs text-gray-400 capitalize">
                        {{ auth()->user()->role }}
                    </p>
                </div>
            </div>

            <form id="sidebarLogoutForm" method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="button"
                    onclick="confirmSidebarLogout()"
                    class="w-full border rounded-lg py-2 text-sm hover:bg-gray-100 flex items-center justify-center gap-2">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4"/>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col lg:ml-0">

        {{-- MOBILE TOPBAR --}}
        <div class="lg:hidden flex items-center justify-between bg-white border-b px-4 py-3">

            <button
                onclick="toggleSidebar()"
                class="p-2 rounded-lg border">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="font-bold text-lg">BanScan</h1>
        </div>

        <!-- PAGE CONTENT -->
        <main class="p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>

    </div>
</div>

@livewireScripts

{{-- SIDEBAR TOGGLE SCRIPT --}}
<script>
function toggleSidebar() {

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    
}
</script>

{{-- PAGE LOADER SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const loader = document.getElementById('pageLoader');

    document.querySelectorAll('a').forEach(link => {

        link.addEventListener('click', function () {

            if (
                this.href &&
                !this.href.includes('#') &&
                !this.target
            ) {
                setTimeout(() => {
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                }, 300);
            }

        });

    });

});
</script>

{{-- LOGOUT CONFIRMATION SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmSidebarLogout()
{
    Swal.fire({
        title: 'Sign Out?',
        text: 'Are you sure you want to sign out?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Sign Out',
        cancelButtonText: 'Cancel',
    }).then((result) => {

        if (result.isConfirmed) {
            document.getElementById('sidebarLogoutForm').submit();
        }

    });
}
</script>
<script src="https://unpkg.com/html5-qrcode"></script>

{{-- PAGE LOADER --}}
<div id="pageLoader"
    class="fixed inset-0 z-[99999] hidden items-center justify-center
           bg-black/10 backdrop-blur-sm">
    <div class="flex flex-col items-center gap-3">
        <img
            src="{{ asset('images/loading.png') }}"
            class="h-8 w-auto animate-pulse">
    </div>
</div>

{{-- TOM SELECT --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
</body>
</html>