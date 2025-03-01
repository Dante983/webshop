@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar with categories -->
            <div class="w-full md:w-1/4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-lg font-semibold mb-4">Categories</h2>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('shop.index') }}" class="block py-1 {{ !$currentCategory ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                    All Products
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="block py-1 {{ $currentCategory && $currentCategory->id == $category->id ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Products grid -->
            <div class="w-full md:w-3/4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold mb-6">
                            {{ $currentCategory ? $currentCategory->name : 'All Products' }}
                        </h1>

                        @if($products->isEmpty())
                            <p class="text-gray-500">No products found.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($products as $product)
                                    <div class="bg-white rounded-lg shadow overflow-hidden border">
                                        <!-- Product image display -->
                                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75">
                                            @if($product->images->count() > 0)
                                                @php
                                                    $primaryImage = $product->images->where('is_primary', true)->first();
                                                    $displayImage = $primaryImage ? $primaryImage : $product->images->first();
                                                @endphp
                                                <img src="{{ asset('storage/' . $displayImage->image_path) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="h-48 w-full object-cover object-center">
                                            @else
                                                <img src="{{ asset('images/no-image.jpg') }}" 
                                                     alt="No image available" 
                                                     class="h-48 w-full object-cover object-center">
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold mb-2">{{ $product->name }}</h3>
                                            <p class="text-gray-600 mb-2 line-clamp-2">{{ $product->description }}</p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-lg font-bold">${{ number_format($product->price, 2) }}</span>
                                                <a href="{{ route('shop.product', $product->slug) }}" class="text-blue-600 hover:text-blue-800">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
