<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        try {
            // Check if the Order model exists and the table exists
            if (class_exists('App\Models\Order') && Schema::hasTable('orders')) {
                $orders = Order::where('user_id', $user->id)
                    ->latest()
                    ->with('items')
                    ->paginate(5);
            } else {
                $orders = collect();  // Empty collection if Order model doesn't exist
            }
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error loading orders: ' . $e->getMessage());
            $orders = collect();  // Empty collection on error
        }

        return view('profile.edit', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the user's order history.
     */
    public function orders(Request $request): View
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->with('items')
            ->paginate(10);

        return view('profile.orders', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    /**
     * Display a specific order.
     */
    public function showOrder(Request $request, Order $order): View
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->load('items.product');

        return view('profile.order-details', [
            'order' => $order
        ]);
    }
}
