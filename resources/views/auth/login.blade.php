<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('build/assets/brewly3.png') }}">
                        <style>
                            body.login-bg {
                                background: var(--color-cafe-coffee) url('{{ asset('build/assets/coffee.jpg') }}') center center / cover no-repeat fixed !important;
                                background-blend-mode: multiply;
                            }
                        </style>
                <link rel="stylesheet" href="{{ asset('css/page-animate.css') }}">
            <link rel="stylesheet" href="{{ asset('css/button-hover.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('build/assets/brewly3.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Brewly - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased login-bg">
    <div class="min-h-screen flex items-center justify-center page-animate" style="position: relative;">
        <!-- Background handled by body CSS -->
        <div class="w-full sm:max-w-md">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('build/assets/brewly3.png') }}" alt="Brewly Logo" class="h-12 w-12 object-contain">
                    <span class="text-4xl font-bold text-white">Brewly</span>
    
                </div>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-lg shadow-md p-8 border-2 border-cafe-latte">
                <h2 class="text-2xl font-bold text-cafe-coffee text-center mb-6">Welcome Back</h2>

                <!-- Session Status -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-cafe-coffee mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-cafe-coffee mb-2">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-6 flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="rounded border-cafe-latte text-cafe-gold focus:ring-cafe-gold">
                        <label for="remember" class="ml-2 text-sm text-cafe-coffee">Remember me</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-cafe-coffee text-white font-semibold py-2 px-4 rounded-lg hover:bg-opacity-90 transition">
                        Sign In
                    </button>
                </form>

                <!-- Demo Credentials -->
                <div class="mt-6 p-4 bg-cafe-peach rounded-lg text-sm text-cafe-coffee">
                    <p class="font-semibold mb-2">Demo Credentials:</p>
                    <p><strong>Admin:</strong> admin@brewly.com / password</p>
                    <p><strong>Cashier:</strong> cashier@brewly.com / password</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
