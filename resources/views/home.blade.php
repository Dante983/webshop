@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <h1 class="text-4xl font-bold mb-4">Welcome to Our Webshop</h1>
                    <p class="text-lg text-gray-600 mb-6">Discover amazing products at great prices</p>
                    <a href="{{ route('shop.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        Shop Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            <h2 class="text-2xl font-bold mb-4">Featured Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover mb-4">
                            @endif
                            <h3 class="text-xl font-bold mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold">${{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('shop.product', $product->slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Categories -->
        <div>
            <h2 class="text-2xl font-bold mb-4">Shop by Category</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                        <h3 class="text-lg font-semibold mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $category->description }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 