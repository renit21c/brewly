@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Daily Sales Report</h2>
        <p class="text-gray-600">{{ today()->format('l, M d, Y') }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-gold">
            <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
            <p class="text-3xl font-bold text-cafe-gold mt-2">{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-gray-500 text-xs mt-2">Rp</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-peach">
            <p class="text-gray-600 text-sm font-medium">Transactions</p>
            <p class="text-3xl font-bold text-cafe-peach mt-2">{{ $totalTransactions }}</p>
            <p class="text-gray-500 text-xs mt-2">Total Orders</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-rust">
            <p class="text-gray-600 text-sm font-medium">Tax Collected</p>
            <p class="text-3xl font-bold text-cafe-rust mt-2">{{ number_format($totalTax, 0, ',', '.') }}</p>
            <p class="text-gray-500 text-xs mt-2">Rp</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-sky">
            <p class="text-gray-600 text-sm font-medium">Total Discount</p>
            <p class="text-3xl font-bold text-cafe-sky mt-2">{{ number_format($totalDiscount, 0, ',', '.') }}</p>
            <p class="text-gray-500 text-xs mt-2">Rp</p>
        </div>
    </div>

    <!-- Payment Breakdown -->
    @if($paymentBreakdown->isNotEmpty())
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Payment Methods Breakdown</h3>
        <div class="space-y-3">
            @foreach($paymentBreakdown as $method => $amount)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <span class="text-gray-700 font-medium">{{ $method }}</span>
                <span class="text-lg font-bold text-cafe-gold">{{ number_format($amount, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Order Type Breakdown -->
    @if($orderTypeBreakdown->isNotEmpty())
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Order Types Breakdown</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700">Order Type</th>
                        <th class="px-4 py-2 text-right text-gray-700">Quantity</th>
                        <th class="px-4 py-2 text-right text-gray-700">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderTypeBreakdown as $type => $data)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ $type }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $data['count'] }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-cafe-gold">{{ number_format($data['amount'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Transactions List -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Today's Transactions</h3>
        @if($transactions->isEmpty())
        <p class="text-gray-500 text-center py-8">No transactions today</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700">#</th>
                        <th class="px-4 py-2 text-left text-gray-700">Time</th>
                        <th class="px-4 py-2 text-left text-gray-700">Type</th>
                        <th class="px-4 py-2 text-right text-gray-700">Amount</th>
                        <th class="px-4 py-2 text-right text-gray-700">Tax</th>
                        <th class="px-4 py-2 text-right text-gray-700">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">#{{ $transaction->id }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $transaction->created_at->format('H:i:s') }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $transaction->orderType?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ number_format($transaction->tax, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-cafe-gold">{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
