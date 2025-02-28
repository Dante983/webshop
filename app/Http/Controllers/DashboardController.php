<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->count();
        $recentOrders = Order::with('items')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'recentOrders'
        ));
    }
}
