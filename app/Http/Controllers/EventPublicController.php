<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class EventPublicController extends Controller
{
    public function index(): View
    {
        $events = Event::active()
            ->ordered()
            ->get();

        return view('events.index', compact('events'));
    }
}
