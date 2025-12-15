<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('build/assets/brewly3.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Brewly') }} - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-cafe-peach">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full sm:max-w-md">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <div class="text-4xl font-bold text-cafe-gold">
                    Brewly
                </div>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-lg shadow-md p-8 border-2 border-cafe-latte">
                <h2 class="text-2xl font-bold text-cafe-coffee text-center mb-6">Create Account</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-cafe-coffee mb-2">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-cafe-coffee mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
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

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-cafe-coffee mb-2">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-cafe-coffee text-white font-semibold py-2 px-4 rounded-lg hover:bg-opacity-90 transition">
                        Register
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-cafe-coffee">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-cafe-gold font-semibold hover:underline">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
