<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Photo Gallery</h2>
            <a href="{{ route('gallery.create') }}"
               class="rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                + New album
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($albums->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center text-gray-500">
                    No albums yet. Be the first to <a href="{{ route('gallery.create') }}" class="text-slate-700 underline">create one</a>.
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($albums as $album)
                        <a href="{{ route('gallery.show', $album) }}"
                           class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                            <div class="aspect-video bg-gray-100">
                                @if ($album->photos->first())
                                    <img src="{{ $album->photos->first()->url() }}" alt=""
                                         class="h-full w-full object-cover group-hover:scale-105 transition">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-4xl text-gray-300">📷</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900">{{ $album->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $album->photos_count }} photo{{ $album->photos_count === 1 ? '' : 's' }}
                                    · by {{ Str::of($album->user->name)->before(' (') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6">{{ $albums->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
