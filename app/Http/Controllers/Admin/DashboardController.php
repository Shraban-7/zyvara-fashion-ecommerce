<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(DashboardService $dashboard): View
    {
        $data = $dashboard->getDashboardData();

        return view('admin.dashboard', $data);
    }
}
