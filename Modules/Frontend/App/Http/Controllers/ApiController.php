<?php

namespace Modules\Frontend\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\App\Models\Category;
use Modules\Admin\App\Models\Album;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    public function getCategory($slug)
    {
        $category = Category::where('slug', $slug)->get();

        return response()->json($category);
    }

    public function getCategoryAlbums($slug)
    {
        $category = Category::where('slug', $slug)->first();

        $albums = $category ? $category->albums : [];

        foreach ($albums as $album) {
            $albumPath = "public/albums/{$album->id}";

            $files = Storage::files($albumPath);

            $album->imgCount = count($files);
        }

        return response()->json($albums);
    }

    public function getAlbum($slug)
    {
        $album = Album::where('slug', $slug)->first();

        if (!$album) {
            return response()->json(['error' => 'Album not found'], 404);
        }

        $albumPath = "public/albums/{$album->id}";

        $files = Storage::files($albumPath);

        $album->imgCount = count($files);

        return response()->json($album);
    }

    public function getAlbumImages($slug)
    {
        $album = Album::where('slug', $slug)->first();

        if (!$album) {
            return response()->json(['error' => 'Album not found'], 404);
        }

        $albumPath = "public/albums/{$album->id}";
        $files = Storage::files($albumPath);
        
        // Format the paths to be usable in frontend
        $images = array_map(function($file) {
            // Remove 'public/' from the path as Storage::url will add the appropriate path
            return str_replace('public/', '', $file);
        }, $files);
        
        return response()->json($images);
    }
}