<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = Album::withCount('photos')
            ->with(['user', 'photos' => fn ($q) => $q->limit(1)])
            ->latest()
            ->paginate(18);

        return view('gallery.index', compact('albums'));
    }

    public function create()
    {
        return view('gallery.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'max:8192'], // 8 MB each
        ]);

        $album = Auth::user()->albums()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        $this->storePhotos($request, $album);

        return redirect()->route('gallery.show', $album)
            ->with('status', 'Album created.');
    }

    public function show(Album $album)
    {
        $album->load(['user', 'photos.user']);

        return view('gallery.show', compact('album'));
    }

    /** Add more photos to an existing album. */
    public function addPhotos(Request $request, Album $album)
    {
        $request->validate([
            'photos' => ['required', 'array'],
            'photos.*' => ['image', 'max:8192'],
        ]);

        $this->storePhotos($request, $album);

        return back()->with('status', 'Photos added.');
    }

    public function destroyAlbum(Album $album)
    {
        $this->authorizeManage($album->user_id);

        foreach ($album->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }
        $album->delete();

        return redirect()->route('gallery.index')->with('status', 'Album deleted.');
    }

    public function destroyPhoto(Photo $photo)
    {
        $this->authorizeManage($photo->user_id);

        $album = $photo->album;
        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return redirect()->route('gallery.show', $album)->with('status', 'Photo removed.');
    }

    /** Persist uploaded files and attach them to the album. */
    protected function storePhotos(Request $request, Album $album): void
    {
        foreach ($request->file('photos', []) as $file) {
            $path = $file->store("albums/{$album->id}", 'public');
            $album->photos()->create([
                'user_id' => Auth::id(),
                'path' => $path,
            ]);
        }
    }

    /** Only the owner or an admin may delete. */
    protected function authorizeManage(int $ownerId): void
    {
        abort_unless(Auth::id() === $ownerId || Auth::user()->isAdmin(), 403);
    }
}
