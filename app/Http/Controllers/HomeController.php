<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->take(6)
            ->get();

        $categories = Category::orderBy('sort_order')->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
