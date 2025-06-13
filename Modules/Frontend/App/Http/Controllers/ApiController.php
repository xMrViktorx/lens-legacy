<?php

namespace Modules\Frontend\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\App\Models\Category;
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
}