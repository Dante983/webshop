@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>

                @if(empty($cartItems))
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">Your cart is empty.</p>
                        <a href="{{ route('shop.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Continue Shopping
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cartItems as $id => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-24 h-24 border border-gray-200 rounded-md overflow-hidden">
                                                    @if($item['image'])
                                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                                             alt="{{ $item['name'] }}" 
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <img src="{{ asset('images/no-image.jpg') }}" 
                                                             alt="No image available" 
                                                             class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">${{ number_format($item['price'], 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <input type="number" 
                                                       name="quantity" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1" 
                                                       class="quantity-input w-16 rounded-md border-gray-300"
                                                       data-product-id="{{ $id }}">
                                                <button type="button" class="update-btn ml-2 text-indigo-600 hover:text-indigo-900 hidden">Update</button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 item-total">${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('cart.remove', ['product' => $id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center">
                        <div class="mb-4 sm:mb-0">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Clear Cart
                                </button>
                            </form>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold mb-2 cart-total">Total: ${{ number_format($totalAmount, 2) }}</div>
                            <a href="{{ route('checkout.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.dataset.productId;
                const quantity = this.value;
                
                if (quantity < 1) {
                    this.value = 1;
                    return;
                }
                
                updateCartItem(productId, quantity, this);
            });
        });
        
        function updateCartItem(productId, quantity, inputElement) {
            // Show loading state
            const row = inputElement.closest('tr');
            row.classList.add('opacity-50');
            
            fetch('{{ route('cart.update-item') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update UI
                row.classList.remove('opacity-50');
                
                if (data.success) {
                    // Update item total
                    const itemTotal = row.querySelector('.item-total');
                    itemTotal.textContent = '$' + data.item_total;
                    
                    // Update cart total
                    const cartTotal = document.querySelector('.cart-total');
                    cartTotal.textContent = 'Total: $' + data.cart_total;
                    
                    // Update cart count in navigation
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cart_count;
                        cartCountElement.classList.remove('hidden');
                    }
                    
                    // Show success message
                    showNotification(data.message);
                } else {
                    // Show error message
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                row.classList.remove('opacity-50');
                showNotification('An error occurred. Please try again.', 'error');
            });
        }
        
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-md ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white max-w-md z-50`;
            notification.textContent = message;
            
            // Add to DOM
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    });
</script>
@endpush
@endsection 