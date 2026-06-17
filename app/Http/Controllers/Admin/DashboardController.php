<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Customer;
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
        $filter = request('filter', 'today');

        /*
        |--------------------------------------------------------------------------
        | Current Period Query
        |--------------------------------------------------------------------------
        */

        $orderQuery = Order::query();

        switch ($filter) {

            case 'today':

                $currentStart = today()->startOfDay();
                $currentEnd = today()->endOfDay();

                $previousStart = today()->subDay()->startOfDay();
                $previousEnd = today()->subDay()->endOfDay();

                break;

            case 'this_week':
                $currentStart = now()->startOfWeek(Carbon::SUNDAY);
                $currentEnd = now()->endOfWeek(Carbon::SATURDAY);
                $previousStart = now()
                    ->subWeek()
                    ->startOfWeek(Carbon::SUNDAY);

                $previousEnd = now()
                    ->subWeek()
                    ->endOfWeek(Carbon::SATURDAY);

                break;

            case 'this_month':
                $currentStart = now()->startOfMonth();
                $currentEnd = now()->endOfMonth();
                $previousStart = now()->subMonth()->startOfMonth();
                $previousEnd = now()->subMonth()->endOfMonth();

                break;

            default:
                $currentStart = today()->startOfDay();
                $currentEnd = today()->endOfDay();
                $previousStart = today()->subDay()->startOfDay();
                $previousEnd = today()->subDay()->endOfDay();

                break;
        }

        $orderQuery->whereBetween('created_at', [
            $currentStart,
            $currentEnd
        ]);

        $chartRevenue = [];

        if ($filter === 'today') {

            for ($i = 0; $i < 24; $i++) {

                $start = today()->copy()->startOfDay()->addHours($i);

                $end = $start->copy()->endOfHour();

                $revenue = Order::whereBetween('created_at', [$start, $end])
                    ->sum('total');

                $chartRevenue[] = [
                    'label' => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00',
                    'revenue' => $revenue,
                ];
            }

        } elseif ($filter === 'this_week') {

            for ($i = 0; $i < 7; $i++) {

                $date = now()->startOfWeek()->copy()->addDays($i);

                $revenue = Order::whereDate('created_at', $date)
                    ->sum('total');

                $chartRevenue[] = [
                    'label' => $date->format('D'),
                    'revenue' => $revenue,
                ];
            }

        } elseif ($filter === 'this_month') {

            $daysInMonth = now()->daysInMonth;

            for ($i = 1; $i <= $daysInMonth; $i++) {

                $date = now()->copy()->startOfMonth()->addDays($i - 1);

                $revenue = Order::whereDate('created_at', $date)
                    ->sum('total');

                $chartRevenue[] = [
                    'label' => $date->format('d'),
                    'revenue' => $revenue,
                ];
            }
        }

        $chartLabels = collect($chartRevenue)->pluck('label');
        $chartData = collect($chartRevenue)->pluck('revenue');
        $totalRevenue = (clone $orderQuery)->sum('total');
        $totalOrders = (clone $orderQuery)->count();
        $totalRefund = SaleReturn::whereBetween('created_at', [
            $currentStart,
            $currentEnd
        ])->sum('refund_amount');

        $totalWebUser = User::where('role', 'customer')->count();
        $totalPosUser = Customer::count();

        $totalCustomers = $totalWebUser + $totalPosUser;

        $previousRevenue = Order::whereBetween('created_at', [
            $previousStart,
            $previousEnd
        ])->sum('total');

        $previousOrders = Order::whereBetween('created_at', [
            $previousStart,
            $previousEnd
        ])->count();

        $previousRefund = SaleReturn::whereBetween('created_at', [
            $previousStart,
            $previousEnd
        ])->sum('refund_amount');

        $previousCustomers = User::whereBetween('created_at', [
            $previousStart,
            $previousEnd
        ])->where('role', 'customer')->count();

        $calculatePercentage = function ($current, $previous) {

            if ($previous <= 0) {
                return 100;
            }

            return round((($current - $previous) / $previous) * 100, 1);
        };

        $revenuePercentage = $calculatePercentage(
            $totalRevenue,
            $previousRevenue
        );

        $ordersPercentage = $calculatePercentage(
            $totalOrders,
            $previousOrders
        );

        $customersPercentage = $calculatePercentage(
            $totalCustomers,
            $previousCustomers
        );

        $refundPercentage = $calculatePercentage(
            $totalRefund,
            $previousRefund
        );

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

        $websiteOrders = Order::where('is_pos', false)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();

        $posOrders = Order::where('is_pos', true)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();

        $totalChannelOrders = $websiteOrders + $posOrders;

        $websitePercentage = $totalChannelOrders > 0
            ? round(($websiteOrders / $totalChannelOrders) * 100)
            : 0;

        $posPercentage = $totalChannelOrders > 0
            ? round(($posOrders / $totalChannelOrders) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Recent Customers
        |--------------------------------------------------------------------------
        */

        $recentCustomers = User::where('role', 'customer')
            ->withCount(['orders' => function ($query) {
                $query->whereNot('status', OrderStatus::DRAFT);
            }])
            ->withSum(['orders' => function ($query) {
                $query->whereNot('status', OrderStatus::DRAFT);
            }], 'total')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($customer) {
                return [
                    'name'         => $customer->name,
                    'email'        => $customer->email,
                    'orders_count' => $customer->orders_count,
                    'total_spent'  => $customer->orders_sum_total ?? 0,
                    'joined_at'    => $customer->created_at->diffForHumans(),
                ];
            });

        $widgets = [
            'totalRevenue' => $totalRevenue,
            'totalRevenuePercentage' => $revenuePercentage,
            'totalOrders' => $totalOrders,
            'totalOrdersPercentage' => $ordersPercentage,
            'totalCustomers' => $totalCustomers,
            'totalCustomersPercentage' => $customersPercentage,
            'totalRefund' => $totalRefund,
            'totalRefundPercentage' => $refundPercentage,
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'outOfStock' => $outOfStock,
            'totalReviews' => $totalReviews,
            'activeCoupons' => $activeCoupons,
            'todayOrders' => $todayOrders,
            'todayRevenue' => $todayRevenue,
            'avgOrderValue' => $avgOrderValue,
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
            'filter',
            'recentCustomers'
        ));
    }
}
