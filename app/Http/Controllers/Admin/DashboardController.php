<?php

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
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'totalRevenue',
            'recentOrders'
        ));
    }
} 