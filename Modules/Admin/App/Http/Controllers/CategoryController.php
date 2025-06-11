<?php

namespace Modules\Admin\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Admin\App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return view('admin::category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $locale = app()->getLocale();

        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name->' . $locale,
            'slug' => 'required|string|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'image',
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Create an empty category
        $category = new Category();

        // Set translatable fields
        $category->setTranslation('name', $locale, $validated['name']);
        
        if (!empty($validated['description'])) {
            $category->setTranslation('description', $locale, $validated['description']);
        }

        // Non-translatable fields
        $category->slug = $validated['slug'];
        $category->image = $validated['image'] ?? null;

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Kategória sikeresen létrehozva!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin::category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $category = Category::findOrFail($id);

        $locale = app()->getLocale();

        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name->' . $locale . ',' . $category->id,
            'slug' => 'required|string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'image',
        ]);

        // Update image if new one was uploaded
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
            $category->image = $validated['image'];
        }

        // Set only current locale's translation
        $category->setTranslation('name', $locale, $validated['name']);
        $category->setTranslation('description', $locale, $validated['description'] ?? '');

        // Update non-translatable fields
        $category->slug = $validated['slug'];

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Kategória sikeresen frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Kategória sikeresen törölve!');
    }
}
