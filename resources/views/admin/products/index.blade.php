@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
<div class="space-y-6">
    <!-- Add Product Button -->
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-cafe-coffee">All Products</h3>
        <a href="{{ route('products.create') }}" class="flex items-center gap-2 bg-cafe-gold text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition font-semibold">
            <i data-feather="plus" class="w-5 h-5"></i>
            Add Product
        </a>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cafe-coffee text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Image</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Category</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Price</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Stock</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <i data-feather="image" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-cafe-coffee font-medium">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-cafe-latte">{{ $product->category }}</td>
                            <td class="px-6 py-4 text-cafe-gold font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if ($product->stock > 10)
                                        bg-green-100 text-green-800
                                    @elseif ($product->stock > 0)
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-cafe-gold hover:bg-cafe-peach bg-opacity-10 rounded transition" title="Edit">
                                        <i data-feather="edit-2" class="w-5 h-5"></i>
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="inline" onsubmit="return confirm('Delete {{ $product->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-cafe-rust hover:bg-cafe-rust hover:bg-opacity-10 rounded transition" title="Delete">
                                            <i data-feather="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No products found. <a href="{{ route('products.create') }}" class="text-cafe-gold font-semibold hover:underline">Create one</a>
                            </td>
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
