<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', UserRole::CUSTOMER->value)
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }
}
