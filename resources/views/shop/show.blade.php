@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Product Image with Modal -->
                    <div class="w-full md:w-1/2">
                        @if($product->image)
                            <div class="relative group">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                     class="w-full max-h-[500px] object-contain rounded-lg cursor-pointer" 
                                     onclick="openImageModal('{{ asset($product->image) }}', '{{ $product->name }}')" />
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="bg-black bg-opacity-50 rounded-full p-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
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
                                        <div class="h-40 overflow-hidden">
                                            <img src="{{ asset($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-40 object-cover hover:scale-110 transition-transform duration-300">
                                        </div>
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

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-75 flex items-center justify-center p-4">
    <div class="relative max-w-4xl mx-auto">
        <button onclick="closeImageModal()" class="absolute top-0 right-0 -mt-12 -mr-12 text-white hover:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain">
        <p id="modalCaption" class="text-white text-center mt-4 text-lg"></p>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openImageModal(imageSrc, caption) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalCaption').textContent = caption;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Close modal when clicking outside the image
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
            closeImageModal();
        }
    });
</script>
@endpush