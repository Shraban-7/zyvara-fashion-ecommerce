<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialPost;
use Illuminate\Http\Request;

class SocialPostController extends Controller
{
    public function index()
    {
        $posts = SocialPost::ordered()->get();

        return view('admin.social-posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|in:instagram,facebook',
            'caption' => 'nullable|string|max:255',
            'post_url' => 'nullable|url|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['image'] = upload_file($request->file('image'), 'social');
        $validated['is_active'] = $request->has('is_active');

        SocialPost::create($validated);

        return redirect()->back()->with('success', 'Social post added successfully!');
    }

    public function update(Request $request, SocialPost $social_post)
    {
        $validated = $request->validate([
            'platform' => 'required|in:instagram,facebook',
            'caption' => 'nullable|string|max:255',
            'post_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            delete_file($social_post->image);
            $validated['image'] = upload_file($request->file('image'), 'social');
        }

        $validated['is_active'] = $request->has('is_active');

        $social_post->update($validated);

        return redirect()->back()->with('success', 'Social post updated successfully!');
    }

    public function destroy(SocialPost $social_post)
    {
        delete_file($social_post->image);
        $social_post->delete();

        return redirect()->back()->with('success', 'Social post deleted successfully!');
    }
}
