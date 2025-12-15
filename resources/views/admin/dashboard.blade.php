@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-gold">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Total Products</p>
                    <p class="text-3xl font-bold text-cafe-coffee">{{ \App\Models\Product::count() }}</p>
                </div>
                <i data-feather="coffee" class="w-12 h-12 text-cafe-gold opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-peach">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Today's Transactions</p>
                    <p class="text-3xl font-bold text-cafe-coffee">{{ \App\Models\Transaction::whereDate('created_at', today())->count() }}</p>
                </div>
                <i data-feather="shopping-cart" class="w-12 h-12 text-cafe-peach opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-rust">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Today's Revenue</p>
                    <p class="text-3xl font-bold text-cafe-coffee">Rp {{ number_format(\App\Models\Transaction::whereDate('created_at', today())->where('paid', true)->sum('total_price'), 0, ',', '.') }}</p>
                </div>
                <i data-feather="trending-up" class="w-12 h-12 text-cafe-rust opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-sky">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 text-sm">Active Shift</p>
                    <p class="text-3xl font-bold text-cafe-coffee">
                        @php
                            $activeShift = \App\Models\Shift::where('user_id', auth()->id())->where('status', 'open')->first();
                        @endphp
                        {{ $activeShift ? '✓' : '-' }}
                    </p>
                </div>
                <i data-feather="clock" class="w-12 h-12 text-cafe-sky opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-cafe-coffee">Recent Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-cafe-coffee">Invoice</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-cafe-coffee">Cashier</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-cafe-coffee">Amount</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-cafe-coffee">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-cafe-coffee">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse (\App\Models\Transaction::latest()->limit(10)->get() as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-cafe-coffee">{{ $transaction->invoice_code }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $transaction->cashier->name }}</td>
                            <td class="px-6 py-4 font-semibold text-cafe-gold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                    {{ $transaction->void ? 'bg-red-100 text-red-800' : ($transaction->paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $transaction->void ? 'Void' : ($transaction->paid ? 'Paid' : 'Unpaid') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $transaction->created_at->format('H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No transactions yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>feather.replace();</script>
@endsection
