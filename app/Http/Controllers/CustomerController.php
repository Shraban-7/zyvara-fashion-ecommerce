<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Wishlist;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_orders'   => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'wishlist_count' => Wishlist::where('user_id', $user->id)->count(),
            'addresses'      => Address::where('user_id', $user->id)->count(),
            'reward_points'  => $user->reward_points ?? 0,
        ];

        $recent_orders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('stats', 'recent_orders'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function addresses()
    {
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->get();
        return view('customer.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'type' => 'required|in:home,office',
        ]);

        $user = Auth::user();

        $makeDefault = $request->has('is_default');
        if ($makeDefault) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'type' => $request->type,
            'is_default' => $makeDefault,
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'type' => 'required|in:home,office',
        ]);

        $makeDefault = $request->has('is_default');
        if ($makeDefault) {
            Address::where('user_id', Auth::id())->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'type' => $request->type,
            'is_default' => $makeDefault,
        ]);

        return back()->with('success', 'Address updated successfully!');
    }

    public function deleteAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();
        return back()->with('success', 'Address deleted successfully!');
    }

    public function setDefaultAddress(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Only one default per user.
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated!');
    }

    public function wishlist()
    {
        $user = Auth::user();
        $wishlists = Wishlist::where('user_id', $user->id)
            ->with('product.images')
            ->latest()
            ->paginate(12);

        return view('customer.wishlist', compact('wishlists'));
    }

    public function reviews()
    {
        $user = Auth::user();
        $reviews = Review::where('user_id', $user->id)
            ->with('product', 'images')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Pending reviews: items from delivered orders that the user hasn't reviewed yet.
        $reviewedProductIds = $reviews->pluck('product_id')->push(0)->all();
        $pending_reviews = OrderItem::whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'delivered');
            })
            ->whereNotNull('product_id')
            ->whereNotIn('product_id', $reviewedProductIds)
            ->with('product')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name ?? 'Product',
                    'product_image' => $item->product->thumbnail ?? null,
                    'order_id'     => $item->order_id,
                    'delivered_at' => optional($item->order)->created_at?->format('M d, Y'),
                ];
            });

        return view('customer.reviews', compact('reviews', 'pending_reviews'));
    }
}
