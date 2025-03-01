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
                        <div class="product-images mb-6">
                            <!-- Main image display -->
                            <div class="main-image-container mb-2 border rounded overflow-hidden">
                                @if($product->images->count() > 0)
                                    @foreach($product->images as $image)
                                        <div class="main-image {{ $loop->first ? 'block' : 'hidden' }}" data-image-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="main-image block">
                                        <img src="{{ asset('images/no-image.jpg') }}" alt="No image available" class="w-full h-96 object-cover">
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Thumbnails -->
                            @if($product->images->count() > 1)
                                <div class="thumbnails-container flex space-x-2 overflow-x-auto">
                                    @foreach($product->images as $image)
                                        <div class="thumbnail cursor-pointer border rounded {{ $loop->first ? 'border-blue-500' : 'border-gray-200' }}" data-image-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.thumbnail');
        const mainImages = document.querySelectorAll('.main-image');
        
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const imageId = this.getAttribute('data-image-id');
                
                // Hide all main images
                mainImages.forEach(image => {
                    image.classList.add('hidden');
                });
                
                // Show the selected image
                document.querySelector(`.main-image[data-image-id="${imageId}"]`).classList.remove('hidden');
                
                // Update thumbnail borders
                thumbnails.forEach(thumb => {
                    thumb.classList.remove('border-blue-500');
                    thumb.classList.add('border-gray-200');
                });
                
                // Highlight the selected thumbnail
                this.classList.remove('border-gray-200');
                this.classList.add('border-blue-500');
            });
        });
    });
</script>
@endpush