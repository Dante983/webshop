@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Product Image -->
                    <div class="w-full md:w-1/2">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto rounded-lg">
                        @else
                            <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                                <span class="text-gray-500">No image</span>
                            </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="w-full md:w-1/2">
                        <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
                        <p class="text-gray-600 mb-4">Category: {{ $product->category->name }}</p>
                        <div class="text-2xl font-bold text-blue-600 mb-4">${{ number_format($product->price, 2) }}</div>
                        
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold mb-2">Description</h2>
                            <p class="text-gray-700">{{ $product->description }}</p>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-700 mb-2">
                                <span class="font-semibold">Availability:</span> 
                                @if($product->stock > 0)
                                    <span class="text-green-600">In Stock ({{ $product->stock }} available)</span>
                                @else
                                    <span class="text-red-600">Out of Stock</span>
                                @endif
                            </p>
                        </div>

                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <div class="flex items-center mb-4">
                                    <label for="quantity" class="mr-2">Quantity:</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 rounded-md border-gray-300">
                                </div>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Add to Cart
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Related Products -->
                @if($relatedProducts->count() > 0)
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-4">Related Products</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                            @foreach($relatedProducts as $relatedProduct)
                                <div class="bg-white rounded-lg shadow overflow-hidden border">
                                    @if($relatedProduct->image)
                                        <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-40 object-cover">
                                    @else
                                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500">No image</span>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold mb-2">{{ $relatedProduct->name }}</h3>
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                            <a href="{{ route('shop.product', $relatedProduct->slug) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 