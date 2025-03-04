@extends('layouts.admin')

@section('title', $product->name)
@section('header', 'Product Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this product?')">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    @if($product->images)
                        @foreach($product->images as $image)
                            @if($image->is_primary)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover rounded">
                            @endif
                        @endforeach
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                            <span class="text-gray-500">No image</span>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Product ID</h3>
                        <p>{{ $product->id }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Name</h3>
                        <p>{{ $product->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Slug</h3>
                        <p>{{ $product->slug }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Category</h3>
                        <p>{{ $product->category->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Price</h3>
                        <p>${{ number_format($product->price, 2) }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Stock</h3>
                        <p>{{ $product->stock }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <div class="flex mt-1">
                            @if($product->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                            @if($product->is_featured)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Featured
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                        <p>{{ $product->created_at->format('F j, Y, g:i a') }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                        <p>{{ $product->updated_at->format('F j, Y, g:i a') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                <div class="bg-gray-50 p-4 rounded">
                    <p>{{ $product->description }}</p>
                </div>
            </div>

            <!-- Product images section -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Product Images</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($product->images as $image)
                        <div class="relative border rounded p-2">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="h-32 w-full object-cover rounded">
                            <div class="mt-2 flex items-center justify-between">
                            </div>
                            @if($image->is_primary)
                                <div class="absolute top-0 right-0 bg-green-500 text-white text-xs px-2 py-1 rounded-bl">Primary</div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 col-span-4">No images uploaded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
