<!-- resources/views/checkout/index.blade.php -->
@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6">Checkout</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-lg font-semibold mb-3">Order Summary</h2>
                        <div class="border rounded-lg p-4 mb-4">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between mb-2">
                                    <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                    <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between font-bold">
                                    <span>Total:</span>
                                    <span>${{ number_format($totalAmount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold mb-3">Shipping & Payment</h2>
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" class="w-full rounded-md border-gray-300" required>
                                @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" class="w-full rounded-md border-gray-300" required>
                                @error('customer_email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone (optional)</label>
                                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" class="w-full rounded-md border-gray-300">
                                @error('customer_phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" class="w-full rounded-md border-gray-300" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Order Notes (optional)</label>
                                <textarea name="notes" id="notes" rows="2" class="w-full rounded-md border-gray-300">{{ old('notes') }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" id="paypal" value="paypal" {{ old('payment_method', 'paypal') == 'paypal' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                                        <label for="paypal" class="ml-2 block text-sm text-gray-700">PayPal</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" id="credit_card" value="credit_card" {{ old('payment_method') == 'credit_card' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                                        <label for="credit_card" class="ml-2 block text-sm text-gray-700">Credit Card (via PayPal)</label>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Proceed to Payment
                            </button>
                        </form>
                    </div>
                </div>

                <!-- PayPal Button -->
                <div id="paypal-button-container" class="mt-4"></div>
            </div>
        </div>
    </div>
</div>

<!-- PayPal JavaScript SDK -->
<script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=USD"></script>
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch('{{ route('checkout.paypal.create') }}', {
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                return data.id;
            });
        },
        onApprove: function(data, actions) {
            return fetch('{{ route('checkout.paypal.capture') }}?order_id=' + data.orderID, {
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(details) {
                window.location.href = '{{ route('checkout.success') }}';
            });
        }
    }).render('#paypal-button-container');
</script>
@endsection
