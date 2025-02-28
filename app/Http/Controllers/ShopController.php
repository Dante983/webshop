<?php
// app/Http/Controllers/ShopController.php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::where('is_active', true);

        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
            $currentCategory = $category;
        } else {
            $currentCategory = null;
        }

        $products = $query->paginate(12);
        $categories = Category::orderBy('sort_order')->get();

        return view('shop.index', compact('products', 'categories', 'currentCategory'));
    }

    public function show(Product $product): View
    {
        if (!$product->is_active) {
            abort(404);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
