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
        $categories = Category::where('status', 1)->orderBy('position', 'asc')->get();

        return response()->json($categories);
    }

    public function getCategory($slug)
    {
        $category = Category::where('slug', $slug)->where('status', 1)->first();

        return response()->json($category);
    }

    public function getCategoryAlbums($slug)
    {
        $category = Category::where('slug', $slug)->where('status', 1)->first();

        // Use sortBy instead of orderBy on collections
        $albums = $category ? $category->albums->sortBy('position') : [];

        foreach ($albums as $album) {
            $albumPath = "public/albums/{$album->id}";

            $files = Storage::files($albumPath);

            $album->imgCount = count($files);
        }

        // Convert collection to array for proper JSON serialization
        return response()->json($albums->values()->all());
    }

    public function getAlbum($slug)
    {
        $album = Album::where('slug', $slug)->where('status', 1)->first();

        if (!$album) {
            return response()->json(['error' => 'Album not found'], 404);
        }

        $albumPath = "public/albums/{$album->id}";

        $files = Storage::files($albumPath);

        $album->imgCount = count($files);

        return response()->json($album);
    }

    public function getAlbumImages($slug, Request $request)
    {
        $album = Album::where('slug', $slug)->where('status', 1)->first();

        if (!$album) {
            return response()->json(['error' => 'Album not found'], 404);
        }

        $albumPath = "public/albums/{$album->id}";
        $files = Storage::files($albumPath);
        
        // Pagination parameters
        $page = $request->input('page', 1);
        $perPage = 30;
        
        // Calculate total pages
        $totalImages = count($files);
        $totalPages = ceil($totalImages / $perPage);
        
        // Get the subset of files for the current page
        $offset = ($page - 1) * $perPage;
        $paginatedFiles = array_slice($files, $offset, $perPage);
        
        // Format the paths
        $images = array_map(function($file) {
            return str_replace('public/', '', $file);
        }, $paginatedFiles);
        
        return response()->json([
            'images' => $images,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => $perPage,
                'total_images' => $totalImages,
                'total_pages' => $totalPages
            ]
        ]);
    }
}