@extends('layouts.app')

@section('title', 'Order Complete')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="text-center mb-8">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <h1 class="text-3xl font-bold mb-2">Thank You for Your Order!</h1>
                    <p class="text-gray-600">Your order has been placed successfully.</p>
                </div>

                <div class="max-w-3xl mx-auto">
                    <div class="border rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Order Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-gray-600">Order Number:</p>
                                <p class="font-semibold">{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Date:</p>
                                <p class="font-semibold">{{ $order->created_at->format('F j, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Total Amount:</p>
                                <p class="font-semibold">${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Payment Method:</p>
                                <p class="font-semibold">{{ ucfirst($order->payment_method) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status:</p>
                                <p class="font-semibold">{{ ucfirst($order->status) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Items Ordered</h2>
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left py-2">Product</th>
                                    <th class="text-left py-2">Price</th>
                                    <th class="text-left py-2">Quantity</th>
                                    <th class="text-left py-2">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-t">
                                        <td class="py-3">{{ $item->product_name }}</td>
                                        <td class="py-3">${{ number_format($item->price, 2) }}</td>
                                        <td class="py-3">{{ $item->quantity }}</td>
                                        <td class="py-3">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                        <div class="mb-4">
                            <p class="text-gray-600">Name:</p>
                            <p class="font-semibold">{{ $order->customer_name }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-gray-600">Email:</p>
                            <p class="font-semibold">{{ $order->customer_email }}</p>
                        </div>
                        @if($order->customer_phone)
                            <div class="mb-4">
                                <p class="text-gray-600">Phone:</p>
                                <p class="font-semibold">{{ $order->customer_phone }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-gray-600">Shipping Address:</p>
                            <p class="font-semibold">{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('shop.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 