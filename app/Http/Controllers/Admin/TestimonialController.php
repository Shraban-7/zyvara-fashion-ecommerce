<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::ordered()->get();
        $pendingCount = Testimonial::pending()->count();

        return view('admin.testimonials.index', compact('testimonials', 'pendingCount'));
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => true, 'is_active' => true]);

        return redirect()->back()->with('success', 'Testimonial approved and published!');
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial rejected and removed.');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request, true);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = upload_file($request->file('avatar'), 'testimonials');
        }

        $validated['is_active'] = $request->has('is_active');

        Testimonial::create($validated);

        return redirect()->back()->with('success', 'Testimonial created successfully!');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $this->validateData($request, false);

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar) {
                delete_file($testimonial->avatar);
            }
            $validated['avatar'] = upload_file($request->file('avatar'), 'testimonials');
        }

        $validated['is_active'] = $request->has('is_active');

        $testimonial->update($validated);

        return redirect()->back()->with('success', 'Testimonial updated successfully!');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->avatar) {
            delete_file($testimonial->avatar);
        }

        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted successfully!');
    }

    private function validateData(Request $request, bool $creating): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'quote' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => ($creating ? 'nullable' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }
}
