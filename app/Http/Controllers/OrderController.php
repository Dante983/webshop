<?php
// app/Http/Controllers/Admin/OrderController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('user')->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items.product', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $validatedData['status']]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully');
    }
}
