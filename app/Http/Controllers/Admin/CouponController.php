<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function __construct(
        protected CouponService $couponService
    ) {
    }

    public function index()
    {
        $coupons = Coupon::withCount('usages')
            ->latest()
            ->paginate(15);

        // Quick stats
        $stats = [
            'active' => Coupon::where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->where(function ($q) {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                })
                ->count(),
            'total_discount' => (float) CouponUsage::sum('discount_amount'),
            'most_used' => Coupon::withCount('usages')
                ->having('usages_count', '>', 0)
                ->orderByDesc('usages_count')
                ->first(),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->limit(200)->get(['id', 'name', 'sku']);

        return view('admin.coupons.create', compact('categories', 'products'));
    }

    public function store(StoreCouponRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        $coupon->loadCount('usages');

        $usages = $coupon->usages()
            ->with(['user', 'order'])
            ->latest('used_at')
            ->paginate(20);

        return view('admin.coupons.show', compact('coupon', 'usages'));
    }

    public function edit(Coupon $coupon)
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->limit(200)->get(['id', 'name', 'sku']);

        return view('admin.coupons.edit', compact('coupon', 'categories', 'products'));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    public function duplicate(Coupon $coupon)
    {
        $duplicate = $coupon->replicate();
        $duplicate->code = $coupon->code . '_COPY_' . Str::random(4);
        $duplicate->used_count = 0;
        $duplicate->is_active = false;
        $duplicate->save();

        return redirect()->route('admin.coupons.edit', $duplicate)
            ->with('success', 'Coupon duplicated. Update the code and activate when ready.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $coupon->is_active,
        ]);
    }

    public function generateCode(Request $request)
    {
        $code = $this->uniqueCode(8);

        return response()->json(['code' => $code]);
    }

    protected function uniqueCode(int $length): string
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }
}
