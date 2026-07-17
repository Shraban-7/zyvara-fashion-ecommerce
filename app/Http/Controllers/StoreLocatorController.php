<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class StoreLocatorController extends Controller
{
    public function index(): View
    {
        $stores = Cache::remember('active_stores', now()->addHours(6), function () {
            return Store::where('is_active', true)->ordered()->get();
        });

        return view('stores.index', compact('stores'));
    }
}
