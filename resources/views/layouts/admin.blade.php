<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
                <link rel="stylesheet" href="{{ asset('css/page-slide.css') }}">
            <link rel="stylesheet" href="{{ asset('css/button-hover.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('build/assets/brewly3.png') }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#317EFB"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="/build/assets/brewly3.png">

    <title>Brewly - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-cafe-coffee text-white p-6 shadow-lg transition-transform duration-300 z-40">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('build/assets/brewly2.png') }}" alt="Brewly Logo" class="h-8 w-8 object-contain">
                    <span class="text-2xl font-bold text-white hover:scale-110 transition duration-300">Brewly</span>
                </div>
                <button id="closeSidebar" class="lg:hidden p-2 hover:bg-cafe-latte rounded transition duration-300 hover:scale-110 hover:rotate-90">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- User Info -->
            <div class="mb-8 pb-8 border-b border-cafe-latte">
                <p class="text-sm text-cafe-peach">{{ auth()->user()->name }}</p>
                <p class="text-xs text-cafe-sky">Admin</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-cafe-latte transition duration-300 hover:scale-105 hover:translate-x-1 {{ request()->routeIs('admin.dashboard') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Products Menu -->
                <details class="group">
                    <summary class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-cafe-latte cursor-pointer transition duration-300 hover:translate-x-1">
                        <i data-feather="coffee" class="w-5 h-5 group-open:rotate-12 transition duration-300"></i>
                        <span>Products</span>
                        <i data-feather="chevron-down" class="w-4 h-4 ml-auto group-open:rotate-180 transition duration-300"></i>
                    </summary>
                    <div class="pl-8 space-y-2 mt-2">
                        <a href="{{ route('products.index') }}" class="block px-4 py-2 rounded-lg hover:bg-cafe-latte transition duration-300 text-sm hover:translate-x-1 hover:font-semibold {{ request()->routeIs('products.index') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">List Products</a>
                        <a href="{{ route('products.create') }}" class="block px-4 py-2 rounded-lg hover:bg-cafe-latte transition duration-300 text-sm hover:translate-x-1 hover:font-semibold {{ request()->routeIs('products.create') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">Add Product</a>
                    </div>
                </details>

                <!-- Reports Menu -->
                <details class="group">
                    <summary class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-cafe-latte cursor-pointer transition duration-300 hover:translate-x-1">
                        <i data-feather="bar-chart-2" class="w-5 h-5 group-open:-rotate-12 transition duration-300"></i>
                        <span>Reports</span>
                        <i data-feather="chevron-down" class="w-4 h-4 ml-auto group-open:rotate-180 transition duration-300"></i>
                    </summary>
                    <div class="pl-8 space-y-2 mt-2">
                        <a href="{{ route('reports.daily') }}" class="block px-4 py-2 rounded-lg hover:bg-cafe-latte transition duration-300 text-sm hover:translate-x-1 hover:font-semibold {{ request()->routeIs('reports.daily') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">Daily Sales</a>
                        <a href="{{ route('reports.monthly') }}" class="block px-4 py-2 rounded-lg hover:bg-cafe-latte transition duration-300 text-sm hover:translate-x-1 hover:font-semibold {{ request()->routeIs('reports.monthly') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">Monthly Report</a>
                        <a href="{{ route('reports.analytics') }}" class="block px-4 py-2 rounded-lg hover:bg-cafe-latte transition duration-300 text-sm hover:translate-x-1 hover:font-semibold {{ request()->routeIs('reports.analytics') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">Menu Analytics</a>
                    </div>
                </details>

                <!-- Users Menu -->
                <details class="group">
                    <summary class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-cafe-latte cursor-pointer transition duration-300 hover:translate-x-1">
                        <i data-feather="users" class="w-5 h-5 group-open:scale-110 transition duration-300"></i>
                        <span>Users</span>
                        <i data-feather="chevron-down" class="w-4 h-4 ml-auto group-open:rotate-180 transition duration-300"></i>
                    </summary>
                    <div class="pl-8 space-y-2 mt-2">
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded-lg hover:bg-cafe-latte transition duration-300 text-sm hover:translate-x-1 hover:font-semibold {{ request()->routeIs('users.index') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">Manage Users</a>
                        <a href="{{ route('users.activity') }}" class="block px-4 py-2 rounded-lg hover:bg-cafe-latte transition duration-300 text-sm hover:translate-x-1 hover:font-semibold {{ request()->routeIs('users.activity') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">Activity Log</a>
                    </div>
                </details>

                <!-- Settings -->
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-cafe-latte transition duration-300 hover:translate-x-1 {{ request()->routeIs('settings.index') ? 'bg-cafe-coffee text-white font-bold border-l-4 border-cafe-gold shadow-lg' : '' }}">
                    <i data-feather="settings" class="w-5 h-5 hover:rotate-90 transition duration-500"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="mt-auto pt-8 border-t border-cafe-latte">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 bg-cafe-rust rounded-lg text-white hover:bg-opacity-90 transition duration-300 font-semibold hover:scale-105 hover:-translate-x-1">
                        <i data-feather="log-out" class="w-5 h-5 hover:translate-x-1 transition duration-300"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div id="mainContent" class="flex-1 lg:ml-64 transition-all duration-300 page-slide">
            <!-- Top Navigation -->
            <div class="bg-white shadow sticky top-0 z-30">
                <div class="flex items-center justify-between px-6 py-4">
                    <button id="openSidebar" class="lg:hidden p-2 hover:bg-gray-100 rounded transition duration-300 hover:scale-110 hover:bg-cafe-gold hover:bg-opacity-20">
                        <i data-feather="menu" class="w-6 h-6 text-cafe-coffee group-hover:text-cafe-gold transition duration-300"></i>
                    </button>
                    <h2 class="text-2xl font-bold text-cafe-coffee flex-1">@yield('title', 'Dashboard')</h2>
                    <div class="text-right hover:scale-105 transition duration-300">
                        <p class="text-sm text-cafe-coffee font-semibold">{{ now('Asia/Jakarta')->format('d M Y H:i') }} WIB</p>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <!-- Overlay for mobile -->
        <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>

        feather.replace();

        // Register service worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('Service Worker registered:', reg))
                    .catch(err => console.log('Service Worker registration failed:', err));
            });
        }

        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }

        openBtn?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);

        // Add Tailwind mobile classes
        sidebar.classList.add('lg:translate-x-0', '-translate-x-full');
    </script>
</body>
</html>
