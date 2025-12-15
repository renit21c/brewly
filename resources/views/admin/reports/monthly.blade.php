@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Monthly Sales Report</h2>
        <form class="flex gap-2" method="GET">
            <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::createFromFormat('m', $m)->format('F') }}
                </option>
                @endfor
            </select>
            <select name="year" class="px-4 py-2 border border-gray-300 rounded-lg">
                @for($y = now()->year - 2; $y <= now()->year; $y++)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="px-6 py-2 bg-cafe-gold text-white rounded-lg hover:bg-opacity-90 transition">Filter</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-gold">
            <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
            <p class="text-3xl font-bold text-cafe-gold mt-2">{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-gray-500 text-xs mt-2">Rp</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-peach">
            <p class="text-gray-600 text-sm font-medium">Total Transactions</p>
            <p class="text-3xl font-bold text-cafe-peach mt-2">{{ $totalTransactions }}</p>
            <p class="text-gray-500 text-xs mt-2">Orders</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-sky">
            <p class="text-gray-600 text-sm font-medium">Average Transaction</p>
            <p class="text-3xl font-bold text-cafe-sky mt-2">{{ number_format($averageTransaction, 0, ',', '.') }}</p>
            <p class="text-gray-500 text-xs mt-2">Rp per order</p>
        </div>
    </div>

    <!-- Daily Breakdown Chart Table -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Daily Breakdown</h3>
        @if($dailyBreakdown->isEmpty())
        <p class="text-gray-500 text-center py-8">No data available for this period</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700">Date</th>
                        <th class="px-4 py-2 text-right text-gray-700">Transactions</th>
                        <th class="px-4 py-2 text-right text-gray-700">Revenue</th>
                        <th class="px-4 py-2 text-right text-gray-700">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyBreakdown as $date => $data)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ \Carbon\Carbon::parse($date)->format('l, M d') }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $data['count'] }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-cafe-gold">{{ number_format($data['amount'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-cafe-gold h-2 rounded-full" style="width: {{ ($data['amount'] / $totalRevenue) * 100 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Stats -->
    <div class="bg-gradient-to-r from-cafe-gold to-cafe-peach rounded-lg shadow p-6 text-white">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <p class="text-white text-opacity-90">Days Active</p>
                <p class="text-2xl font-bold mt-1">{{ $dailyBreakdown->count() }}</p>
            </div>
            <div>
                <p class="text-white text-opacity-90">Best Day</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($dailyBreakdown->max('amount'), 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-white text-opacity-90">Daily Average</p>
                <p class="text-2xl font-bold mt-1">{{ number_format($dailyBreakdown->avg('amount'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
