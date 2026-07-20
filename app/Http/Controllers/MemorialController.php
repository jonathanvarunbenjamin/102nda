<?php

namespace App\Http\Controllers;

use App\Models\FallenMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MemorialController extends Controller
{
    public function index()
    {
        $fallen = FallenMember::withCount(['tributes', 'photos'])
            ->orderBy('name')
            ->get();

        return view('memorial.index', compact('fallen'));
    }

    public function show(FallenMember $fallen)
    {
        $fallen->load(['tributes.user', 'photos.user', 'creator']);

        return view('memorial.show', compact('fallen'));
    }

    // ---- Admin: manage the fallen-member entries ----

    public function create()
    {
        $this->authorizeAdmin();

        return view('memorial.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academy_number' => ['nullable', 'string', 'max:100'],
            'squadron' => ['nullable', 'string', 'max:100'],
            'course' => ['nullable', 'string', 'max:100'],
            'date_of_passing' => ['nullable', 'date'],
            'biography' => ['nullable', 'string', 'max:5000'],
            'portrait' => ['nullable', 'image', 'max:8192'],
        ]);

        if ($request->hasFile('portrait')) {
            $data['portrait'] = $request->file('portrait')->store('memorial/portraits', 'public');
        }

        $fallen = FallenMember::create($data + ['created_by' => Auth::id()]);

        return redirect()->route('memorial.show', $fallen)
            ->with('status', 'Memorial page created.');
    }

    public function destroy(FallenMember $fallen)
    {
        $this->authorizeAdmin();

        if ($fallen->portrait) {
            Storage::disk('public')->delete($fallen->portrait);
        }
        foreach ($fallen->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }
        $fallen->delete();

        return redirect()->route('memorial.index')->with('status', 'Memorial page removed.');
    }

    // ---- Any member: add a tribute or a photo ----

    public function storeTribute(Request $request, FallenMember $fallen)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $fallen->tributes()->create([
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        return back()->with('status', 'Your recollection has been added.');
    }

    public function storePhoto(Request $request, FallenMember $fallen)
    {
        $data = $request->validate([
            'photo' => ['required', 'image', 'max:8192'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('photo')->store("memorial/{$fallen->id}", 'public');

        $fallen->photos()->create([
            'user_id' => Auth::id(),
            'path' => $path,
            'caption' => $data['caption'] ?? null,
        ]);

        return back()->with('status', 'Photo added.');
    }

    protected function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }
}
