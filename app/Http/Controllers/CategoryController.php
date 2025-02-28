<?php
// app/Http/Controllers/Admin/CategoryController.php
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
        $categories = Category::orderBy('sort_order')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $validatedData['slug'] = Str::slug($request->name);
        $validatedData['sort_order'] = $request->sort_order ?? 0;

        Category::create($validatedData);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $validatedData['slug'] = Str::slug($request->name);
        $validatedData['sort_order'] = $request->sort_order ?? 0;

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

    public function updateOrder(Request $request): RedirectResponse
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        foreach ($request->categories as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category order updated successfully');
    }
}
