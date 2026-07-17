<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderByDesc('priority')
            ->orderBy('display_order')
            ->orderBy('title')
            ->get();

        return view('admin.events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link_url' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'priority' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'nullable|string',
        ]);

        $imagePath = upload_file($request->file('image'), 'events');

        Event::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'image' => $imagePath,
            'link_url' => $validated['link_url'],
            'badge_text' => $validated['badge_text'],
            'priority' => $validated['priority'] ?? 0,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'display_order' => $validated['display_order'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Event created successfully!');
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link_url' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'priority' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            delete_file($event->image);
            $event->image = upload_file($request->file('image'), 'events');
        }

        $event->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'image' => $event->image,
            'link_url' => $validated['link_url'],
            'badge_text' => $validated['badge_text'],
            'priority' => $validated['priority'] ?? 0,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'display_order' => $validated['display_order'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Event updated successfully!');
    }

    public function delete(Event $event)
    {
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}
