<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BanScan</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- 🔹 NAVBAR -->
    <header class="flex justify-between items-center px-8 py-4 bg-white shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/banscanlogo.png') }}" class="h-10">
        </div>

        <div class="flex gap-4">
            <a href="/login" class="px-4 py-2 text-sm font-medium hover:text-black hover:scale-105 transition border border-gray-300 rounded-lg">
                Login
            </a>
            <a href="/register" class="px-4 py-2 bg-black text-white rounded-lg text-sm hover:scale-105 transition">
                Register
            </a>
        </div>
    </header>

    <!-- 🔹 HERO SECTION -->
    <section class="relative text-center py-20 px-6 bg-cover bg-center bg-no-repeat"
    style="background-image: url('/images/image.jpg'); min-height: 500px;">

    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10 text-white">

        <h1 class="text-5xl font-bold mb-6">
            Smart Inventory Management
        </h1>

        <p class="text-gray-200 text-lg max-w-2xl mx-auto mb-8">
            Manage your stock, sales, and returns efficiently with barcode scanning and real-time tracking.
        </p>

        <div class="flex justify-center gap-4">
            <a href="/login" class="px-6 py-3 bg-white text-black rounded-lg hover:scale-105 transition">
                Get Started
            </a>

            <a href="#features" class="px-6 py-3 border border-white text-white rounded-lg hover:bg-white/10 transition">
                Learn More
            </a>
        </div>

    </div>
</section>

    <!-- 🔹 FEATURES -->
    <section id="features" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6">

            <h2 class="text-3xl font-semibold text-center mb-12">
                Features
            </h2>

            <div class="grid md:grid-cols-4 gap-6">

                <!-- Inventory -->
                <div class="p-6 border rounded-xl text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
                    
                    <div class="p-3 bg-indigo-100 text-indigo-600 rounded-lg inline-block mb-3">
                        <x-heroicon-o-cube class="w-6 h-6" />
                    </div>

                    <h3 class="font-semibold mb-2">Inventory</h3>
                    <p class="text-sm text-gray-600">
                        Manage items, categories, and suppliers.
                    </p>
                </div>

                <!-- Sales -->
                <div class="p-6 border rounded-xl text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
                    
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg inline-block mb-3">
                        <x-heroicon-o-banknotes class="w-6 h-6" />
                    </div>

                    <h3 class="font-semibold mb-2">Sales</h3>
                    <p class="text-sm text-gray-600">
                        Fast billing with barcode scanning.
                    </p>
                </div>

                <!-- Returns -->
                <div class="p-6 border rounded-xl text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
                    
                    <div class="p-3 bg-amber-100 text-amber-600 rounded-lg inline-block mb-3">
                        <x-heroicon-o-arrow-uturn-left class="w-6 h-6" />
                    </div>

                    <h3 class="font-semibold mb-2">Returns</h3>
                    <p class="text-sm text-gray-600">
                        Easy return processing and tracking.
                    </p>
                </div>

                <!-- Reports -->
                <div class="p-6 border rounded-xl text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
                    
                    <div class="p-3 bg-gray-200 text-gray-700 rounded-lg inline-block mb-3">
                        <x-heroicon-o-chart-bar class="w-6 h-6" />
                    </div>

                    <h3 class="font-semibold mb-2">Reports</h3>
                    <p class="text-sm text-gray-600">
                        Sales and stock insights.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- 🔹 HOW IT WORKS -->
    <section class="py-16">
        <div class="max-w-5xl mx-auto px-6 text-center">

            <h2 class="text-3xl font-semibold mb-10">
                How It Works
            </h2>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Scan -->
                <div class="hover:scale-105 transition duration-300 text-center">
                    
                    <div class="p-3 bg-indigo-100 text-indigo-600 rounded-lg inline-block mb-3">
                        <x-heroicon-o-qr-code class="w-6 h-6" />
                    </div>

                    <h3 class="font-semibold mb-2">1. Scan</h3>
                    <p class="text-sm text-gray-600">Scan item barcode</p>
                </div>

                <!-- Add -->
                <div class="hover:scale-105 transition duration-300 text-center">
                    
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg inline-block mb-3">
                        <x-heroicon-o-plus class="w-6 h-6" />
                    </div>

                    <h3 class="font-semibold mb-2">2. Add</h3>
                    <p class="text-sm text-gray-600">Add items to cart</p>
                </div>

                <!-- Checkout -->
                <div class="hover:scale-105 transition duration-300 text-center">
                    
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg inline-block mb-3">
                        <x-heroicon-o-receipt-percent class="w-6 h-6" />
                    </div>

                    <h3 class="font-semibold mb-2">3. Checkout</h3>
                    <p class="text-sm text-gray-600">Complete the sale</p>
                </div>
            </div>
            
        </div>
    </section>

    <!-- 🔹 CTA -->
    <section class="py-16 bg-black text-white text-center">
        <h2 class="text-3xl font-semibold mb-6">
            Start Managing Your Inventory Today
        </h2>

        <a href="/login" class="px-6 py-3 bg-white text-black rounded-lg hover:scale-105 transition duration-300 inline-block">
            Login Now
        </a>
    </section>

    <!-- 🔹 FOOTER -->
    <footer class="text-center text-sm text-gray-500 py-6">
        © {{ date('Y') }} BanScan — Inventory Management System
    </footer>

</body>
</html>