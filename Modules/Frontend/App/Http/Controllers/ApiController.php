<?php

namespace Modules\Frontend\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\App\Models\Category;

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
}