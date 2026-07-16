<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'quote' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['is_active'] = false;
        $validated['is_approved'] = false;

        if ($user = $request->user()) {
            $validated['name'] = $validated['name'] ?: $user->name;
        }

        Testimonial::create($validated);

        return back()->with('testimonial_success', 'Thank you! Your testimonial has been submitted and is pending review.');
    }
}
