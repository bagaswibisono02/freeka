<?php

namespace App\Http\Controllers;

use App\Models\Categorie as Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')
            ->orderBy('order')
            ->get();
            
        // Group categories by parent for easier display
        $mainCategories = $categories->where('parent_id', null);
        $subCategories = $categories->where('parent_id', '!=', null)->groupBy('parent_id');
        
        return view('admin.categories.index', compact('mainCategories', 'subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::where('parent_id', null)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

                    // Create empty category for form
        $category = new Category();
            
        return view('admin.categories.create', compact('parentCategories', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        // Generate slug from name
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        // Set default values
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('parent', 'children');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('parent_id', null)
            ->where('id', '!=', $category->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        // Generate new slug if name changed
        if ($category->name !== $validated['name']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products or subcategories
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category that has subcategories.');
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Generate unique slug for category
     */
    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Update category order (for drag & drop sorting)
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.order' => 'required|integer',
            'categories.*.parent_id' => 'nullable|exists:categories,id'
        ]);

        foreach ($request->categories as $categoryData) {
            Category::where('id', $categoryData['id'])->update([
                'order' => $categoryData['order'],
                'parent_id' => $categoryData['parent_id'] ?? null
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.categories.index')
            ->with('success', "Category {$status} successfully.");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        // dd($request);
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $action = $request->action;
        $categoryIds = $request->categories;

        switch ($action) {
            case 'activate':
                Category::whereIn('id', $categoryIds)->update(['is_active' => true]);
                $message = 'Selected categories activated successfully.';
                break;
                
            case 'deactivate':
                Category::whereIn('id', $categoryIds)->update(['is_active' => false]);
                $message = 'Selected categories deactivated successfully.';
                break;
                
            case 'delete':
                // Check if any category has subcategories
                $categoriesWithChildren = Category::whereIn('id', $categoryIds)
                    ->whereHas('children')
                    ->count();
                    
                if ($categoriesWithChildren > 0) {
                    return redirect()->route('admin.categories.index')
                        ->with('error', 'Cannot delete categories that have subcategories.');
                }
                
                // Delete images and categories
                $categories = Category::whereIn('id', $categoryIds)->get();
                foreach ($categories as $category) {
                    if ($category->image) {
                        Storage::disk('public')->delete($category->image);
                    }
                    $category->delete();
                }
                $message = 'Selected categories deleted successfully.';
                break;
        }

        return redirect()->route('admin.categories.index')
            ->with('success', $message);
    }
}