<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $album->title }}</h2>
            <a href="{{ route('gallery.index') }}" class="text-sm text-slate-600 hover:underline">&larr; All albums</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6">
                @if ($album->description)
                    <p class="text-gray-700">{{ $album->description }}</p>
                @endif
                <p class="text-sm text-gray-500 mt-1">
                    Created by {{ Str::of($album->user->name)->before(' (') }} · {{ $album->created_at->format('d M Y') }}
                </p>

                @if (Auth::id() === $album->user_id || Auth::user()->isAdmin())
                    <form method="POST" action="{{ route('gallery.album.destroy', $album) }}" class="mt-3"
                          onsubmit="return confirm('Delete this whole album and all its photos?')">
                        @csrf @method('delete')
                        <button class="text-sm text-red-600 hover:underline">Delete album</button>
                    </form>
                @endif
            </div>

            {{-- Add photos --}}
            <form method="POST" action="{{ route('gallery.photos', $album) }}" enctype="multipart/form-data"
                  class="bg-white rounded-xl shadow-sm p-6 flex flex-col sm:flex-row sm:items-end gap-3">
                @csrf
                <div class="flex-1">
                    <x-input-label for="photos" value="Add more photos" />
                    <input id="photos" name="photos[]" type="file" accept="image/*" multiple required
                           class="block mt-1 w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                </div>
                <x-primary-button>Upload</x-primary-button>
            </form>

            @if ($album->photos->isEmpty())
                <p class="text-gray-500">No photos in this album yet.</p>
            @else
                <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($album->photos as $photo)
                        <div class="group relative bg-white rounded-lg shadow-sm overflow-hidden">
                            <a href="{{ $photo->url() }}" target="_blank">
                                <img src="{{ $photo->url() }}" alt="{{ $photo->caption }}"
                                     class="aspect-square w-full object-cover">
                            </a>
                            @if (Auth::id() === $photo->user_id || Auth::user()->isAdmin())
                                <form method="POST" action="{{ route('gallery.photo.destroy', $photo) }}"
                                      class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition">
                                    @csrf @method('delete')
                                    <button class="rounded bg-black/60 text-white text-xs px-2 py-1">✕</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
