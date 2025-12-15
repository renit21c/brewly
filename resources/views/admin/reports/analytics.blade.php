@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Menu Analytics</h2>
        <form class="flex gap-2" method="GET">
            <select name="period" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All Time</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-cafe-gold text-white rounded-lg hover:bg-opacity-90 transition">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cafe-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8L5.257 19.393A2 2 0 005 18.07V5a2 2 0 012-2h10a2 2 0 012 2v10.25"></path>
                </svg>
                Top 10 Products
            </h3>
            @if($topProducts->isEmpty())
            <p class="text-gray-500 text-center py-8">No sales data available</p>
            @else
            <div class="space-y-3">
                @foreach($topProducts as $index => $item)
                <div class="p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $index + 1 }}. {{ $item['product']->name }}</p>
                            <p class="text-sm text-gray-600">{{ $item['quantity'] }} units sold</p>
                        </div>
                        <p class="text-lg font-bold text-cafe-gold">{{ number_format($item['revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-cafe-gold h-2 rounded-full" style="width: {{ ($item['revenue'] / $topProducts->first()['revenue']) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Worst Selling Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-cafe-rust" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17H5m12 0h-2m2 0l-1.586-1.586a2 2 0 00-2.828 0L9 16m12-12V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2z"></path>
                </svg>
                Slow Moving Items
            </h3>
            @if($bottomProducts->isEmpty())
            <p class="text-gray-500 text-center py-8">All items are selling well</p>
            @else
            <div class="space-y-3">
                @foreach($bottomProducts as $item)
                <div class="p-3 bg-gray-50 rounded hover:bg-gray-100 transition border-l-4 border-cafe-rust">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item['product']->name }}</p>
                            <p class="text-sm text-gray-600">{{ $item['quantity'] }} units sold</p>
                        </div>
                        <p class="text-lg font-bold text-cafe-rust">{{ number_format($item['revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Product Summary Table -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">All Products Performance</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700">Product Name</th>
                        <th class="px-4 py-2 text-right text-gray-700">Units</th>
                        <th class="px-4 py-2 text-right text-gray-700">Revenue</th>
                        <th class="px-4 py-2 text-right text-gray-700">Rank</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $index => $item)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $item['product']->name }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $item['quantity'] }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-cafe-gold">{{ number_format($item['revenue'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-cafe-gold text-white text-sm font-bold">#{{ $index + 1 }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No sales data available for this period</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
