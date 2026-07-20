<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['rsvps as going_count' => fn ($q) => $q->where('status', 'going')])
            ->orderByRaw('starts_at IS NULL, starts_at asc')
            ->get();

        return view('visit.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['rsvps.user', 'creator']);
        $myRsvp = Auth::check() ? $event->rsvpFor(Auth::user()) : null;

        return view('visit.show', compact('event', 'myRsvp'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('visit.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'location' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $event = Event::create($data + ['created_by' => Auth::id()]);

        return redirect()->route('visit.show', $event)->with('status', 'Event created.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeAdmin();
        $event->delete();

        return redirect()->route('visit.index')->with('status', 'Event removed.');
    }

    /** A member RSVPs (or updates their RSVP). */
    public function rsvp(Request $request, Event $event)
    {
        $data = $request->validate([
            'status' => ['required', 'in:going,maybe,not_going'],
            'guests' => ['nullable', 'integer', 'min:0', 'max:20'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $event->rsvps()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'status' => $data['status'],
                'guests' => $data['guests'] ?? 0,
                'note' => $data['note'] ?? null,
            ]
        );

        return back()->with('status', 'Your RSVP has been saved.');
    }

    protected function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }
}
