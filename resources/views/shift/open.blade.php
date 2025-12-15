<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('build/assets/brewly3.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Brewly') }} - Open Shift</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Card -->
            <div class="bg-white rounded-lg shadow-lg p-8 border-2 border-cafe-gold">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-cafe-gold mb-2">Brewly</h1>
                    <p class="text-gray-600">Open Your Shift</p>
                </div>

                <form id="openShiftForm">
                    @csrf

                    <!-- User Info -->
                    <div class="mb-6 p-4 bg-cafe-peach bg-opacity-20 rounded-lg">
                        <p class="text-sm text-gray-600">Cashier</p>
                        <p class="text-lg font-bold text-cafe-coffee">{{ auth()->user()->name }}</p>
                    </div>

                    <!-- Opening Balance -->
                    <div class="mb-6">
                        <label for="opening_balance" class="block text-sm font-semibold text-cafe-coffee mb-3">Opening Balance (Rp)</label>
                        <input type="number" id="opening_balance" name="opening_balance" 
                            placeholder="e.g. 500000"
                            step="1000"
                            required
                            class="w-full px-4 py-3 border-2 border-cafe-latte rounded-lg focus:outline-none focus:border-cafe-gold text-lg font-semibold">
                    </div>

                    <!-- Info -->
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>ℹ️ Tip:</strong> Enter the amount of cash you have at the beginning of your shift. This will be used to calculate differences at the end of your shift.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-cafe-gold text-white font-bold py-3 px-4 rounded-lg hover:bg-opacity-90 transition">
                        Start Shift
                    </button>
                </form>

                <!-- Help -->
                <div class="mt-6 text-center">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-sm text-gray-600 hover:text-cafe-coffee">
                        Not ready? Logout
                    </a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('openShiftForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            try {
                const response = await axios.post('{{ route("shift.open.store") }}', {
                    opening_balance: parseFloat(formData.get('opening_balance')),
                    _token: document.querySelector('meta[name="csrf-token"]').content
                });

                if (response.data.success) {
                    // Redirect to POS
                    window.location.href = '{{ route("pos.index") }}';
                }
            } catch (error) {
                const message = error.response?.data?.error || 'Failed to open shift';
                alert(message);
            }
        });
    </script>
</body>
</html>
