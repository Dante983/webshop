@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('profile.orders') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                    ← Back to Orders
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Order #{{ $order->id }}</h3>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($order->status == 'delivered') bg-green-100 text-green-800 
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800 
                            @elseif($order->status == 'shipped') bg-blue-100 text-blue-800 
                            @elseif($order->status == 'processing') bg-yellow-100 text-yellow-800 
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Order Information</h4>
                            <p class="text-sm text-gray-900 dark:text-gray-100">Date: {{ $order->created_at->format('F j, Y') }}</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">Payment Method: {{ ucfirst($order->payment_method) }}</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">Transaction ID: {{ $order->transaction_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Shipping Information</h4>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $order->customer_name }}</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $order->customer_email }}</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $order->customer_phone ?? 'No phone provided' }}</p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Order Items</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($item->product && $item->product->images->first())
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-md object-cover" 
                                                             src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                             alt="{{ $item->product_name }}">
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $item->product_name }}
                                                    </div>
                                                    @if($item->product)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            <a href="{{ route('shop.product', $item->product->slug) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                                View Product
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            ${{ number_format($item->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            ${{ number_format($item->price * $item->quantity, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                        Total:
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->notes)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Order Notes</h4>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($order->status != 'cancelled' && $order->status != 'delivered')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Need Help?</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            If you have any questions about your order, please contact our customer support.
                        </p>
                        <a href="mailto:support@example.com" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Contact Support
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
