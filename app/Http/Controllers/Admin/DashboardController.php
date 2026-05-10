<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\SaleReturn;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $filter = request('filter', '30_days');

        $orderQuery = Order::query();

        $orderQuery = match ($filter) {

            'today' => $orderQuery->whereDate(
                'created_at',
                today()
            ),

            'yesterday' => $orderQuery->whereDate(
                'created_at',
                today()->subDay()
            ),

            default => $orderQuery->where(
                'created_at',
                '>=',
                now()->subDays(30)
            ),
        };

        $chartRevenue = [];

        if (in_array($filter, ['today', 'yesterday'])) {

            $targetDate = $filter === 'today'
                ? today()
                : today()->subDay();

            for ($i = 0; $i < 24; $i++) {

                $start = $targetDate->copy()->startOfDay()->addHours($i);
                $end = $start->copy()->endOfHour();

                $revenue = Order::whereBetween('created_at', [$start, $end])
                    ->sum('total');

                $chartRevenue[] = [
                    'label' => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00',
                    'revenue' => $revenue,
                ];
            }

        } else {

            for ($i = 29; $i >= 0; $i--) {

                $date = now()->subDays($i);

                $revenue = Order::whereDate('created_at', $date)
                    ->sum('total');

                $chartRevenue[] = [
                    'label' => $date->format('d M'),
                    'revenue' => $revenue,
                ];
            }
        }

        $chartLabels = collect($chartRevenue)->pluck('label');
        $chartData = collect($chartRevenue)->pluck('revenue');

        $totalRevenue = (clone $orderQuery)->sum('total');

        $totalOrders = (clone $orderQuery)->count();

        $totalRefund = SaleReturn::when(
            $filter === 'today',
            fn($q) => $q->whereDate('created_at', today())
        )
            ->when(
                $filter === 'yesterday',
                fn($q) => $q->whereDate('created_at', today()->subDay())
            )
            ->when(
                $filter === '30_days',
                fn($q) => $q->where('created_at', '>=', now()->subDays(30))
            )
            ->sum('refund_amount');

        $totalCustomers = User::where('role', 'customer')->count();

        $pendingOrders = Order::where('status', 'pending')->count();

        $recentOrders = (clone $orderQuery)
            ->with('user')
            ->whereNot('status', OrderStatus::DRAFT)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($order) {

                return [
                    'id' => $order->id,

                    'order_number' => $order->order_number,

                    'customer_name' =>
                        $order->shipping_name
                        ?? $order->user->name
                        ?? $order->customer->name
                        ?? 'Walk-In Customer',

                    'created_at' => $order->created_at->format('M d, Y'),

                    'total' => $order->total,

                    'status' => $order->status->value ?? 'pending',
                ];
            });

        $topProducts = OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as sales'),
            DB::raw('SUM(subtotal) as revenue')
        )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->take(5)
            ->get()
            ->map(function ($item) {

                return [
                    'name' => $item->product?->name,

                    'image' => $item->product?->thumbnail,

                    'sales' => $item->sales,

                    'revenue' => $item->revenue,
                ];
            });

        $lowStockProducts = Product::with('variants')
            ->get()
            ->where('total_stock', '<=', 10)
            ->sortBy('total_stock')
            ->take(5);

        $lowStockCount = $lowStockProducts->count();

        $totalProducts = Product::count();

        $totalCategories = Category::count();

        $outOfStock = Product::with('variants')
            ->get()
            ->where('total_stock', '<=', 0)
            ->count();

        $totalReviews = Review::count();

        $activeCoupons = Coupon::where('expires_at', '>', now())->count();

        $todayOrders = Order::whereDate('created_at', today())->count();

        $todayRevenue = Order::whereDate('created_at', today())->sum('total');

        $avgOrderValue = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;

        $websiteOrders = Order::where('is_pos', false)->count();

        $posOrders = Order::where('is_pos', true)->count();

        $totalChannelOrders = $websiteOrders + $posOrders;

        $websitePercentage = $totalChannelOrders > 0
            ? round(($websiteOrders / $totalChannelOrders) * 100)
            : 0;

        $posPercentage = $totalChannelOrders > 0
            ? round(($posOrders / $totalChannelOrders) * 100)
            : 0;

        $widgets = [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'pendingOrders' => $pendingOrders,
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'outOfStock' => $outOfStock,
            'totalReviews' => $totalReviews,
            'activeCoupons' => $activeCoupons,
            'todayOrders' => $todayOrders,
            'todayRevenue' => $todayRevenue,
            'avgOrderValue' => $avgOrderValue,
            'totalRefund' => $totalRefund,
        ];

        return view('admin.dashboard', compact(
            'widgets',
            'recentOrders',
            'topProducts',
            'lowStockCount',
            'lowStockProducts',
            'chartLabels',
            'chartData',
            'websiteOrders',
            'posOrders',
            'totalChannelOrders',
            'websitePercentage',
            'posPercentage',
            'filter'
        ));
    }
}
