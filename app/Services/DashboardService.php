<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\ReturnStatus;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SaleReturn;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Build the full dashboard data set.
     * Expensive aggregates are cached for 5 minutes.
     */
    public function getDashboardData(): array
    {
        return Cache::remember('admin_dashboard_v1', now()->addMinutes(5), function () {
            return [
                'stats' => $this->statCards(),
                'revenue' => [
                    'today' => $this->revenueSeries(7),
                    'month' => $this->revenueSeries(30),
                ],
                'recentOrders' => $this->recentOrders(),
                'lowStock' => $this->lowStockProducts(),
                'topProducts' => $this->topSellingProducts(),
                'flashSale' => $this->activeFlashSale(),
                'categories' => $this->categoryBreakdown(),
                'notifications' => $this->recentNotifications(),
                'statusBadgeMap' => [
                    'pending' => 'warning',
                    'confirmed' => 'info',
                    'processing' => 'info',
                    'shipped' => 'pending',
                    'out_for_delivery' => 'warning',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                    'returned' => 'danger',
                    'refunded' => 'danger',
                    'draft' => 'pending',
                ],
            ];
        });
    }

    /**
     * The four top stat cards.
     */
    protected function statCards(): array
    {
        $now = now();

        // Today vs yesterday
        $todayRevenue = (float) Order::whereDate('created_at', $now->toDateString())->sum('total');
        $yesterdayRevenue = (float) Order::whereDate('created_at', $now->copy()->subDay()->toDateString())->sum('total');

        // This month vs last month
        $monthRevenue = (float) Order::whereBetween('created_at', [
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth(),
        ])->sum('total');
        $lastMonthRevenue = (float) Order::whereBetween('created_at', [
            $now->copy()->subMonth()->startOfMonth(),
            $now->copy()->subMonth()->endOfMonth(),
        ])->sum('total');

        $todayOrders = Order::whereDate('created_at', $now->toDateString())->count();
        $yesterdayOrders = Order::whereDate('created_at', $now->copy()->subDay()->toDateString())->count();

        $totalProducts = Product::count();
        $activeFlash = FlashSale::current()->first();

        return [
            [
                'key' => 'revenue',
                'label' => 'Revenue (Today)',
                'value' => money($todayRevenue),
                'raw' => $todayRevenue,
                'pct' => $this->percent($todayRevenue, $yesterdayRevenue),
                'icon' => 'banknote',
                'icon_color' => 'text-success',
                'icon_bg' => 'bg-success-50',
                'sub' => 'vs yesterday',
            ],
            [
                'key' => 'revenue_month',
                'label' => 'Revenue (Month)',
                'value' => money($monthRevenue),
                'raw' => $monthRevenue,
                'pct' => $this->percent($monthRevenue, $lastMonthRevenue),
                'icon' => 'trending-up',
                'icon_color' => 'text-accent',
                'icon_bg' => 'bg-accent-50',
                'sub' => 'vs last month',
            ],
            [
                'key' => 'orders',
                'label' => 'Orders (Today)',
                'value' => number_format($todayOrders),
                'raw' => $todayOrders,
                'pct' => $this->percent($todayOrders, $yesterdayOrders),
                'icon' => 'shopping-cart',
                'icon_color' => 'text-primary',
                'icon_bg' => 'bg-primary-50',
                'sub' => 'vs yesterday',
            ],
            [
                'key' => 'products',
                'label' => 'Total Products',
                'value' => number_format($totalProducts),
                'raw' => $totalProducts,
                'pct' => null,
                'icon' => 'package',
                'icon_color' => 'text-secondary-600',
                'icon_bg' => 'bg-secondary-100',
                'sub' => $activeFlash ? 'Flash sale live' : 'No active sale',
            ],
            [
                'key' => 'pending_returns',
                'label' => 'Pending Returns',
                'value' => number_format(ReturnRequest::where('status', ReturnStatus::PENDING)->count()),
                'raw' => ReturnRequest::where('status', ReturnStatus::PENDING)->count(),
                'pct' => null,
                'icon' => 'undo-2',
                'icon_color' => 'text-warning',
                'icon_bg' => 'bg-warning-50',
                'sub' => 'Awaiting review',
                'link' => route('admin.returns.index', ['status' => ReturnStatus::PENDING->value]),
            ],
        ];
    }

    /**
     * Daily revenue for the last $days days.
     */
    protected function revenueSeries(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $rows = Order::where('created_at', '>=', $start)
            ->where('status', '!=', OrderStatus::DRAFT->value)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->pluck('revenue', 'date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();
            $labels[] = $days <= 7 ? $date->format('D') : $date->format('d M');
            $data[] = (float) ($rows[$key] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    protected function recentOrders(): Collection
    {
        return Order::with(['user', 'customer'])
            ->where('status', '!=', OrderStatus::DRAFT->value)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->shipping_name
                        ?? $order->user?->name
                        ?? $order->customer?->name
                        ?? 'Walk-In Customer',
                    'created_at' => $order->created_at->format('M d, Y'),
                    'total' => (float) $order->total,
                    'status' => $order->status->value,
                    'status_label' => $order->status->label(),
                ];
            });
    }

    protected function lowStockProducts(): Collection
    {
        return Product::query()
            ->where('stock_in', '>', 0)
            ->where('stock_in', '<=', 10)
            ->orderBy('stock_in', 'asc')
            ->take(8)
            ->get(['id', 'name', 'slug', 'stock_in', 'sku', 'image']);
    }

    protected function topSellingProducts(): Collection
    {
        $monthStart = now()->startOfMonth();

        return OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as units'), DB::raw('SUM(subtotal) as revenue'))
            ->whereHas('order', fn ($q) => $q->where('created_at', '>=', $monthStart)
                ->where('status', '!=', OrderStatus::DRAFT->value))
            ->with('product:id,name,slug,image')
            ->groupBy('product_id')
            ->orderByDesc('units')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product?->name ?? 'Deleted product',
                    'image' => $item->product?->image,
                    'slug' => $item->product?->slug,
                    'units' => (int) $item->units,
                    'revenue' => (float) $item->revenue,
                ];
            });
    }

    protected function activeFlashSale(): ?array
    {
        $sale = FlashSale::current()->with('products:id,name,price,image')->first();

        if (! $sale) {
            return null;
        }

        $products = $sale->products->map(function ($product) use ($sale) {
            $salePrice = $product->pivot->sale_price ?? $product->price;
            $discount = $product->price > 0
                ? (int) round((($product->price - $salePrice) / $product->price) * 100)
                : 0;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => (float) $product->price,
                'sale_price' => (float) $salePrice,
                'discount' => $discount,
            ];
        });

        $avgDiscount = $products->isNotEmpty()
            ? (int) round($products->avg('discount'))
            : 0;

        return [
            'id' => $sale->id,
            'name' => $sale->title ?? 'Flash Sale',
            'ends_at' => $sale->ends_at->timestamp,
            'products_count' => $products->count(),
            'avg_discount' => $avgDiscount,
            'products' => $products->take(5),
        ];
    }

    protected function categoryBreakdown(): array
    {
        $categories = Category::query()
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(6)
            ->get(['id', 'name']);

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('products_count')->toArray(),
        ];
    }

    protected function recentNotifications(): Collection
    {
        return Notification::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'type' => $n->type?->value ?? 'info',
                    'action_url' => $n->action_url,
                    'is_unread' => is_null($n->read_at),
                    'time' => $n->created_at->diffForHumans(),
                ];
            });
    }

    protected function percent($current, $previous): ?float
    {
        if (! $previous) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
