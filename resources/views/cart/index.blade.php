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
                                                @if($item['image'])
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 object-cover rounded-md" src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">${{ number_format($item['price'], 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('cart.update', ['product' => $id]) }}" method="POST" class="flex items-center">
                                                @csrf
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 rounded-md border-gray-300">
                                                <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-900">Update</button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
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
                            <div class="text-lg font-bold mb-2">Total: ${{ number_format($totalAmount, 2) }}</div>
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
@endsection 