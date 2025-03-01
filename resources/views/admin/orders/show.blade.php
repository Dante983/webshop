@extends('layouts.admin')

@section('title', 'Order #' . $order->id)
@section('header', 'Order Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold">Order #{{ $order->id }}</h2>
            <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('F j, Y, g:i a') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                Back to Orders
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
            <p class="mb-2"><span class="font-medium">Name:</span> {{ $order->customer_name }}</p>
            <p class="mb-2"><span class="font-medium">Email:</span> {{ $order->customer_email }}</p>
            @if($order->customer_phone)
                <p class="mb-2"><span class="font-medium">Phone:</span> {{ $order->customer_phone }}</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Shipping Address</h3>
            <p>{{ $order->shipping_address }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
            <p class="mb-2"><span class="font-medium">Order Status:</span> 
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : '' }}
                    {{ $order->status === 'delivered' ? 'bg-purple-100 text-purple-800' : '' }}
                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <p class="mb-2"><span class="font-medium">Payment Method:</span> {{ ucfirst($order->payment_method) }}</p>
            <p class="mb-2"><span class="font-medium">Transaction ID:</span> {{ $order->transaction_id ?? 'N/A' }}</p>
            <p class="mb-2"><span class="font-medium">Total Amount:</span> ${{ number_format($order->total_amount, 2) }}</p>
            @if($order->notes)
                <p class="mb-2"><span class="font-medium">Notes:</span> {{ $order->notes }}</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Order Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
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
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                        @if($item->product)
                                            <div class="text-sm text-gray-500">
                                                <a href="{{ route('admin.products.show', $item->product) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    View Product
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($item->price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Update Order Status</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex items-center">
                @csrf
                @method('PATCH')
                <select name="status" class="rounded-md border-gray-300 mr-2">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 