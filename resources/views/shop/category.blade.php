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