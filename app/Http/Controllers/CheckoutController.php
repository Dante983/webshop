<?php
// app/Http/Controllers/CheckoutController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Omnipay\Omnipay;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);  // Set to false in production
    }

    public function index(): View|RedirectResponse
    {
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cartItems', 'totalAmount'));
    }

    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:paypal,credit_card'
        ]);

        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Store order in session for processing after payment
        $orderData = [
            'user_id' => Auth::id(),
            'payment_method' => $request->payment_method,
            'total_amount' => $totalAmount,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'items' => $cartItems
        ];

        session()->put('pending_order', $orderData);

        // Process PayPal payment (similar for credit card with appropriate gateway)
        $response = $this->gateway->purchase([
            'amount' => $totalAmount,
            'currency' => 'USD',
            'returnUrl' => route('checkout.success'),
            'cancelUrl' => route('checkout.cancel'),
        ])->send();

        if ($response->isRedirect()) {
            // Redirect to PayPal
            return redirect($response->getRedirectUrl());
        }

        // Payment failed
        return redirect()->back()->with('error', $response->getMessage());
    }

    public function success(Request $request): RedirectResponse
    {
        // Get the pending order data
        $orderData = session()->get('pending_order');

        if (!$orderData) {
            return redirect()->route('home')->with('error', 'No pending order found');
        }

        // Verify stock availability before processing
        $insufficientStock = false;
        $outOfStockItems = [];
        
        foreach ($orderData['items'] as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                $insufficientStock = true;
                $outOfStockItems[] = $item['name'];
            }
        }
        
        if ($insufficientStock) {
            return redirect()->route('cart.index')->with('error', 'Some items in your cart are no longer available in the requested quantity: ' . implode(', ', $outOfStockItems));
        }

        // Use a transaction for data integrity
        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'user_id' => $orderData['user_id'],
                'transaction_id' => $request->paymentId ?? null,
                'payment_method' => $orderData['payment_method'],
                'total_amount' => $orderData['total_amount'],
                'status' => 'paid',
                'customer_name' => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'],
                'customer_phone' => $orderData['customer_phone'],
                'shipping_address' => $orderData['shipping_address'],
                'notes' => $orderData['notes'] ?? null,
            ]);

            // Create order items and update product stock
            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
                
                // Update product stock
                $product = Product::find($item['id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }
            
            DB::commit();
            
            // Clear cart and pending order
            session()->forget(['cart', 'pending_order']);

            return redirect()->route('checkout.complete', $order)->with('success', 'Your order has been placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('checkout.index')->with('error', 'Payment was cancelled');
    }

    public function complete(Order $order): View
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('checkout.complete', compact('order'));
    }

    public function createPayPalOrder(Request $request)
    {
        $cartItems = session()->get('cart', []);
        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($totalAmount, 2, '.', '')
                ]
            ]],
            'application_context' => [
                'cancel_url' => route('checkout.cancel'),
                'return_url' => route('checkout.success')
            ]
        ];

        try {
            $client = app('paypal.client');
            $response = $client->execute($request);
            
            return response()->json([
                'id' => $response->result->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function capturePayPalOrder(Request $request)
    {
        // Get the pending order data
        $orderData = session()->get('pending_order');

        if (!$orderData) {
            return redirect()->route('home')->with('error', 'No pending order found');
        }

        // Verify stock availability before processing
        $insufficientStock = false;
        $outOfStockItems = [];
        
        foreach ($orderData['items'] as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                $insufficientStock = true;
                $outOfStockItems[] = $item['name'];
            }
        }
        
        if ($insufficientStock) {
            return redirect()->route('cart.index')->with('error', 'Some items in your cart are no longer available in the requested quantity: ' . implode(', ', $outOfStockItems));
        }

        // Use a transaction for data integrity
        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'user_id' => $orderData['user_id'],
                'transaction_id' => $request->paymentId ?? null,
                'payment_method' => $orderData['payment_method'],
                'total_amount' => $orderData['total_amount'],
                'status' => 'paid',
                'customer_name' => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'],
                'customer_phone' => $orderData['customer_phone'],
                'shipping_address' => $orderData['shipping_address'],
                'notes' => $orderData['notes'] ?? null,
            ]);

            // Create order items and update product stock
            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
                
                // Update product stock
                $product = Product::find($item['id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }
            
            DB::commit();
            
            // Clear cart and pending order
            session()->forget(['cart', 'pending_order']);

            return redirect()->route('checkout.complete', $order)->with('success', 'Your order has been placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }
}
