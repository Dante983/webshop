<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(10);
        
        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::all();
        
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        // Generate slug
        $validatedData['slug'] = Str::slug($validatedData['name']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }
        
        // Set boolean fields
        $validatedData['is_featured'] = $request->has('is_featured');
        $validatedData['is_active'] = $request->has('is_active');
        
        Product::create($validatedData);
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function show(Product $product): View
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::all();
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);
        
        // Generate slug
        $validatedData['slug'] = Str::slug($validatedData['name']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }
        
        // Set boolean fields
        $validatedData['is_featured'] = $request->has('is_featured');
        $validatedData['is_active'] = $request->has('is_active');
        
        $product->update($validatedData);
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
} 