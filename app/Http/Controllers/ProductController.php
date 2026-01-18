<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // In the future, you can add filtering logic here
        // For now, just return the view with static template
        return view('products.index');
    }

    /**
     * Display the specified product.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        // In the future, fetch product by slug
        return view('products.show');
    }
}
