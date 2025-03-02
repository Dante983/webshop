<?php
// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function index(): View
    {
        $cartItems = session()->get('cart', []);
        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cartItems', 'totalAmount'));
    }

    public function add(Product $product, Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        // Get the primary image or the first image
        $primaryImage = $product->images()->where('is_primary', true)->first();
        $imagePath = $primaryImage ? $primaryImage->image_path : ($product->images->first() ? $product->images->first()->image_path : null);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
            $message = $product->name . ' quantity updated in cart!';
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $imagePath
            ];
            $message = $product->name . ' added to cart!';
        }

        session()->put('cart', $cart);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => count($cart)
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            // Calculate new totals
            $itemTotal = $cart[$productId]['price'] * $cart[$productId]['quantity'];
            $cartTotal = 0;
            
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated!',
                    'item_total' => number_format($itemTotal, 2),
                    'cart_total' => number_format($cartTotal, 2),
                    'cart_count' => count($cart)
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart!'
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);
        $productId = $request->route('product');
        
        if (isset($cart[$productId])) {
            $productName = $cart[$productId]['name'];
            unset($cart[$productId]);
            session()->put('cart', $cart);
            
            return redirect()->back()->with('success', $productName . ' removed from cart!');
        }

        return redirect()->back()->with('error', 'Product not found in cart!');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->back()->with('success', 'Cart cleared!');
    }
}
