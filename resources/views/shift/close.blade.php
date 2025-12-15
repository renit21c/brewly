<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('build/assets/brewly3.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Brewly') }} - Close Shift</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <!-- Card -->
            <div class="bg-white rounded-lg shadow-lg p-8 border-2 border-cafe-rust">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-cafe-rust mb-2">Brewly</h1>
                    <p class="text-gray-600">Close Your Shift</p>
                </div>

                <!-- Shift Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="p-4 bg-cafe-peach bg-opacity-20 rounded-lg">
                        <p class="text-sm text-gray-600">Opened At</p>
                        <p class="text-lg font-bold text-cafe-coffee">{{ $shift->opened_at->format('H:i:s') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $shift->opened_at->format('d M Y') }}</p>
                    </div>

                    <div class="p-4 bg-cafe-gold bg-opacity-20 rounded-lg">
                        <p class="text-sm text-gray-600">Opening Balance</p>
                        <p class="text-lg font-bold text-cafe-coffee">Rp {{ number_format($shift->opening_balance, 0, ',', '.') }}</p>
                    </div>

                    <div class="p-4 bg-cafe-sky bg-opacity-20 rounded-lg">
                        <p class="text-sm text-gray-600">Transactions</p>
                        <p class="text-lg font-bold text-cafe-coffee">{{ $shift->transactions->count() }}</p>
                    </div>

                    <div class="p-4 bg-cafe-latte bg-opacity-20 rounded-lg">
                        <p class="text-sm text-gray-600">Expected Total</p>
                        <p class="text-lg font-bold text-cafe-coffee">Rp {{ number_format($expectedTotal, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="mb-8">
                    <h3 class="font-bold text-cafe-coffee mb-4">Recent Transactions</h3>
                    <div class="max-h-48 overflow-y-auto space-y-2">
                        @forelse ($shift->transactions as $transaction)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg text-sm">
                                <span class="font-medium text-cafe-coffee">{{ $transaction->invoice_code }}</span>
                                <span class="text-cafe-gold font-bold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No transactions yet</p>
                        @endforelse
                    </div>
                </div>

                <!-- Close Shift Form -->
                <form id="closeShiftForm" class="space-y-6">
                    @csrf

                    <!-- Closing Balance -->
                    <div>
                        <label for="closing_balance" class="block text-sm font-semibold text-cafe-coffee mb-3">Closing Balance (Rp)</label>
                        <input type="number" id="closing_balance" name="closing_balance" 
                            placeholder="Enter amount of cash you have now"
                            step="1000"
                            required
                            class="w-full px-4 py-3 border-2 border-cafe-latte rounded-lg focus:outline-none focus:border-cafe-gold text-lg font-semibold">
                        <p class="text-xs text-gray-500 mt-2">Expected: Rp {{ number_format($expectedTotal + $shift->opening_balance, 0, ',', '.') }}</p>
                    </div>

                    <!-- Difference Alert -->
                    <div id="differenceAlert" class="p-4 rounded-lg hidden">
                        <p class="text-sm"><strong>Difference:</strong> <span id="differenceAmount"></span></p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-cafe-coffee mb-3">Notes (optional)</label>
                        <textarea id="notes" name="notes" 
                            placeholder="e.g. Missing cash, extra payment, etc."
                            rows="3"
                            class="w-full px-4 py-3 border-2 border-cafe-latte rounded-lg focus:outline-none focus:border-cafe-gold"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <a href="{{ route('pos.index') }}" class="flex-1 px-4 py-3 border-2 border-cafe-latte text-cafe-coffee rounded-lg hover:bg-gray-50 transition font-semibold text-center">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 bg-cafe-rust text-white font-bold py-3 px-4 rounded-lg hover:bg-opacity-90 transition">
                            Close Shift
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const closingInput = document.getElementById('closing_balance');
        const differenceAlert = document.getElementById('differenceAlert');
        const differenceAmount = document.getElementById('differenceAmount');
        const expectedTotal = {{ $expectedTotal }};
        const openingBalance = {{ $shift->opening_balance }};

        closingInput.addEventListener('input', function() {
            const closing = parseFloat(this.value) || 0;
            const expected = openingBalance + expectedTotal;
            const difference = closing - expected;

            if (difference !== 0) {
                differenceAlert.classList.remove('hidden');
                differenceAmount.textContent = (difference >= 0 ? '+' : '') + 'Rp ' + 
                    new Intl.NumberFormat('id-ID').format(difference);
                differenceAlert.className = difference >= 0 ? 
                    'p-4 rounded-lg bg-green-100 border border-green-400' : 
                    'p-4 rounded-lg bg-red-100 border border-red-400';
            } else {
                differenceAlert.classList.add('hidden');
            }
        });

        document.getElementById('closeShiftForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            try {
                const response = await axios.post('{{ route("shift.close.store") }}', {
                    closing_balance: parseFloat(formData.get('closing_balance')),
                    notes: formData.get('notes'),
                    _token: document.querySelector('meta[name="csrf-token"]').content
                });

                if (response.data.success) {
                    alert('Shift closed successfully!');
                    // Submit logout form via POST
                    document.getElementById('logout-form').submit();
                }
            } catch (error) {
                const message = error.response?.data?.error || 'Failed to close shift';
                alert(message);
            }
        });
    </script>
</script>
<!-- Hidden logout form for POST -->
<form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
    @csrf
</form>
</body>
</html>
