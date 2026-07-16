<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\Request;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::ordered()->get();

        $usedKeys = $sections->pluck('section_key')->all();
        $availableKeys = collect(HomeSection::AVAILABLE_SECTIONS)
            ->keys()
            ->reject(fn ($key) => in_array($key, $usedKeys))
            ->values();

        return view('admin.home-sections.index', compact('sections', 'availableKeys'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_key' => 'required|string|in:' . implode(',', array_keys(HomeSection::AVAILABLE_SECTIONS)) . '|unique:home_sections,section_key',
            'title' => 'nullable|string|max:255',
            'eyebrow' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'item_limit' => 'required|integer|min:1|max:50',
        ]);

        $validated['display_order'] = (int) (HomeSection::max('display_order') ?? 0) + 1;
        $validated['is_visible'] = $request->has('is_visible');

        HomeSection::create($validated);

        return redirect()->back()->with('success', 'Section added successfully!');
    }

    public function update(Request $request, HomeSection $home_section)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'eyebrow' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'item_limit' => 'required|integer|min:1|max:50',
        ]);

        $validated['is_visible'] = $request->has('is_visible');

        $home_section->update($validated);

        return redirect()->back()->with('success', 'Section updated successfully!');
    }

    public function destroy(HomeSection $home_section)
    {
        $home_section->delete();

        return redirect()->back()->with('success', 'Section removed successfully!');
    }

    public function toggleStatus(HomeSection $home_section)
    {
        $home_section->update(['is_visible' => ! $home_section->is_visible]);

        return response()->json([
            'success' => true,
            'is_visible' => $home_section->is_visible,
        ]);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:home_sections,id',
        ]);

        foreach ($validated['order'] as $position => $id) {
            HomeSection::where('id', $id)->update(['display_order' => $position]);
        }

        HomeSection::clearCache();

        return response()->json(['success' => true]);
    }
}
