<!-- Product image in user order history -->
<div class="flex-shrink-0 w-16 h-16 border border-gray-200 rounded-md overflow-hidden">
    @php
        $product = App\Models\Product::find($item->product_id);
        $primaryImage = $product && $product->images->count() > 0 ? 
            $product->images->where('is_primary', true)->first() : null;
        $displayImage = $primaryImage ? $primaryImage : 
            ($product && $product->images->count() > 0 ? $product->images->first() : null);
    @endphp
    
    @if($displayImage)
        <img src="{{ asset('storage/' . $displayImage->image_path) }}" 
             alt="{{ $item->product_name }}" 
             class="w-full h-full object-cover">
    @else
        <img src="{{ asset('images/no-image.jpg') }}" 
             alt="No image available" 
             class="w-full h-full object-cover">
    @endif
</div> 