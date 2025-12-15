@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Create New Product</h2>
        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-opacity-90 transition">
            Cancel
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-cafe-coffee mb-2">Product Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-semibold text-cafe-coffee mb-2">Category</label>
                            <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="e.g., Coffee, Tea, Food" required
                                class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('category') border-red-500 @enderror">
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="price" class="block text-sm font-semibold text-cafe-coffee mb-2">Price (Rp)</label>
                                <input type="number" id="price" name="price" step="0.01" value="{{ old('price') }}" required
                                    class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('price') border-red-500 @enderror">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-semibold text-cafe-coffee mb-2">Initial Stock</label>
                                <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" required
                                    class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="mb-6">
                            <label for="image" class="block text-sm font-semibold text-cafe-coffee mb-2">Product Image</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full px-4 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold @error('image') border-red-500 @enderror">
                            <p class="text-gray-500 text-xs mt-1">Max file size: 2MB. Formats: JPEG, PNG, JPG, GIF</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 bg-cafe-gold text-white font-semibold py-2 px-4 rounded-lg hover:bg-opacity-90 transition">
                                Create Product
                            </button>
                            <a href="{{ route('products.index') }}" class="flex-1 bg-gray-400 text-white font-semibold py-2 px-4 rounded-lg hover:bg-opacity-90 transition text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
    </div>
</div>
@endsection
