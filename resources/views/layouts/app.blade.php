<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BTHC - Better Hope Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Nunito Sans', 'Inter', sans-serif;
        }
        
        /* Navigation Links Hover Effect */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            color: #374151;
            font-weight: 500;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #000, #666);
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: #000;
            transform: translateY(-2px);
        }

        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-white">
    <style>
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
    <!-- Top black bar -->
    <!-- Main Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-12">
                <!-- Logo -->
                <a href="/" class="flex flex-col items-start">
                    <img src="{{ asset('images/logo-bthc.png') }}" alt="BTHC Logo" class="h-12 w-auto">
                    <span class="text-xs text-gray-600 leading-tight mt-1">BETTER HOPE COLLECTION</span>
                </a>
                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-6">
                    <a href="/" class="nav-link">Home</a>
                    <a href="/shop" class="nav-link">Shop</a>
                    <a href="/custom" class="nav-link">Custom</a>
                    <a href="/about" class="nav-link">About Us</a>
                    <a href="/contact" class="nav-link">Contacts</a>
                </div>
            </div>
            <!-- Utility Icons -->
            <div class="flex items-center space-x-4">
                <a href="/cart" class="text-gray-700 hover:text-gray-900 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @php
                        $cartCount = 0;
                        if(auth()->check()) {
                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                        }
                    @endphp
                    <span class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $cartCount }}</span>
                </a>
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 font-medium">Sign In</a>
                @else
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                type="button"
                                class="flex items-center space-x-1 text-gray-700 hover:text-gray-900">
                            <span>{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-4 w-4 transition-transform" 
                                 :class="{ 'rotate-180': open }"
                                 fill="none" 
                                 viewBox="0 0 24 24" 
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Akun Saya</a>
                            <a href="{{ route('orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Panel</a>
                            @endif
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                               Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-black text-white mt-12">
        <div class="container mx-auto px-8 lg:px-16 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <!-- Column 1: Logo and Description -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo-bthc.png') }}" alt="BTHC Logo" class="h-10 w-auto">
                        <div class="flex flex-col">
                            <span class="text-2xl font-light">BTHC</span>
                            <span class="text-xs text-gray-400">BETTER HOPE COLLECTION</span>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        The customer is at the heart of our unique business model, which includes design.
                    </p>
                </div>

                <!-- Column 2: SHOPPING Links -->
                <div class="space-y-4">
                    <h3 class="font-bold text-white uppercase text-sm tracking-wide">SHOPPING</h3>
                    <ul class="space-y-2">
                        <li><a href="/shop" class="text-gray-400 hover:text-white text-sm transition-colors">Shop</a></li>
                        <li><a href="/custom" class="text-gray-400 hover:text-white text-sm transition-colors">Custom</a></li>
                    </ul>
                </div>

                <!-- Column 3: SHOPPING Links (Second) -->
                <div class="space-y-4">
                    <h3 class="font-bold text-white uppercase text-sm tracking-wide">SHOPPING</h3>
                    <ul class="space-y-2">
                        <li><a href="/contact" class="text-gray-400 hover:text-white text-sm transition-colors">Contact Us</a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-white text-sm transition-colors">About Us</a></li>
                    </ul>
                </div>

                <!-- Column 4: NEWSLETTER -->
                <div class="space-y-4">
                    <h3 class="font-bold text-white uppercase text-sm tracking-wide">NEWSLETTER</h3>
                    <p class="text-gray-400 text-sm">
                        Be the first to know about new arrivals, sales & promos!
                    </p>
                    <form class="flex items-end space-x-2 border-b border-gray-600 pb-2">
                        <input type="email" placeholder="Your email" class="bg-transparent border-none outline-none text-white placeholder-gray-500 flex-1 text-sm">
                        <button type="submit" class="text-white hover:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Separator -->
            <div class="border-t border-gray-700 my-6"></div>

            <!-- Copyright -->
            <div class="text-center text-gray-400 text-sm mb-4">
                © 2025 by Better Hope Collection | All rights reserved
            </div>

            <!-- Separator -->
            <div class="border-t border-gray-700 my-6"></div>

        </div>
    </footer>
</body>
</html>