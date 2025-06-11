<?php

namespace Modules\Admin\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\App\Models\Album;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Admin\App\Models\Category;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $albums = Album::all();
        return view('admin::album.index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin::album.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $locale = app()->getLocale();

        $validated = $request->validate([
            'name' => 'required|string|unique:albums,name->' . $locale,
            'slug' => 'required|unique:albums,slug',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image',
        ]);

        // Create an empty album
        $album = new Album();

        // Set translatable fields
        $album->setTranslation('name', $locale, $validated['name']);

        if (!empty($validated['description'])) {
            $album->setTranslation('description', $locale, $validated['description']);
        }

        // Non-translatable fields
        $album->slug = $validated['slug'];
        $album->category_id = $validated['category_id'];

        $album->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filePath = $file->storeAs("albums/{$album->id}", $file->getClientOriginalName(), 'public');
            }
        }

        return redirect()->route('admin.album.index')->with('success', 'Album sikeresen létrehozva!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $album = Album::findOrFail($id);
        $categories = Category::all();

        $images = \Storage::disk('public')->files("albums/{$album->id}");

        return view('admin::album.edit', compact('album', 'categories', 'images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $album = Album::findOrFail($id);

        $locale = app()->getLocale();

        $validated = $request->validate([
            'name' => 'required|string|unique:albums,name->' . $locale . ',' . $album->id,
            'slug' => 'required|unique:albums,slug,' . $album->id,
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filePath = $file->storeAs("albums/{$album->id}", $file->getClientOriginalName(), 'public');
            }
        }

        // Set only current locale's translation
        $album->setTranslation('name', $locale, $validated['name']);
        $album->setTranslation('description', $locale, $validated['description'] ?? '');

        // Update non-translatable fields
        $album->slug = $validated['slug'];

        $album->save();

        return redirect()->route('admin.album.index')->with('success', 'Album sikeresen frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $album = Album::findOrFail($id);
        $album->delete();

        return redirect()->route('admin.album.index')->with('success', 'Album sikeresen törölve!');
    }

    public function removeImage(Request $request)
    {
        $imagePath = $request->input('image_path');
        
        if (\Storage::disk('public')->exists($imagePath)) {
            \Storage::disk('public')->delete($imagePath);
            return response()->json(['success' => true, 'message' => 'Kép sikeresen törölve!']);
        }

        return response()->json(['success' => false, 'message' => 'Kép nem található.'], 404);
    }
}
