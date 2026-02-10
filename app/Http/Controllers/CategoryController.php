<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('sort_order', 'asc')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }
}
