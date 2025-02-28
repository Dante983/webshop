<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);
        
        // Generate slug
        $validatedData['slug'] = Str::slug($validatedData['name']);
        
        Category::create($validatedData);
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function show(Category $category): View
    {
        $category->load('products');
        
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);
        
        // Generate slug
        $validatedData['slug'] = Str::slug($validatedData['name']);
        
        $category->update($validatedData);
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Cannot delete category with associated products');
        }
        
        $category->delete();
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
} 