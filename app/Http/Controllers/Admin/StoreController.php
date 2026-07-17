<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    protected const DAYS = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    public function index()
    {
        $stores = Store::ordered()->paginate(20);

        return view('admin.stores.index', compact('stores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'nullable|string|max:120',
            'postal_code' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:120',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:120',
            'google_maps_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'display_order' => 'required|integer|min:0',
        ]);

        $data = $request->only([
            'name', 'address_line1', 'address_line2', 'city', 'state',
            'postal_code', 'country', 'phone', 'email', 'google_maps_url', 'display_order',
        ]);

        $data['slug'] = $this->uniqueSlug($request->name);
        $data['latitude'] = $request->filled('latitude') ? $request->latitude : null;
        $data['longitude'] = $request->filled('longitude') ? $request->longitude : null;
        $data['opening_hours'] = $this->collectHours($request);
        $data['is_flagship'] = $request->has('is_flagship');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = upload_file($request->file('image'), 'stores');
        }

        Store::create($data);

        return back()->with('success', 'Store created successfully!');
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'nullable|string|max:120',
            'postal_code' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:120',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:120',
            'google_maps_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'display_order' => 'required|integer|min:0',
        ]);

        $data = $request->only([
            'name', 'address_line1', 'address_line2', 'city', 'state',
            'postal_code', 'country', 'phone', 'email', 'google_maps_url', 'display_order',
        ]);

        if ($store->name !== $request->name) {
            $data['slug'] = $this->uniqueSlug($request->name, $store->id);
        }

        $data['latitude'] = $request->filled('latitude') ? $request->latitude : null;
        $data['longitude'] = $request->filled('longitude') ? $request->longitude : null;
        $data['opening_hours'] = $this->collectHours($request);
        $data['is_flagship'] = $request->has('is_flagship');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($store->image) {
                delete_file($store->image);
            }
            $data['image'] = upload_file($request->file('image'), 'stores');
        }

        $store->update($data);

        return back()->with('success', 'Store updated successfully!');
    }

    public function delete(Store $store)
    {
        if ($store->image) {
            delete_file($store->image);
        }

        $store->delete();

        return back()->with('success', 'Store deleted successfully!');
    }

    protected function collectHours(Request $request): array
    {
        $hours = [];
        foreach (self::DAYS as $day) {
            $value = trim((string) $request->input("opening_hours.$day"));
            $hours[$day] = $value === '' ? 'closed' : $value;
        }

        return $hours;
    }

    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (Store::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
