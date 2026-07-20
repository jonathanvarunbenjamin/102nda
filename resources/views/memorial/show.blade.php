<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">In memory of {{ $fallen->name }}</h2>
            <a href="{{ route('memorial.index') }}" class="text-sm text-slate-600 hover:underline">&larr; Fallen Brothers</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Profile --}}
            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row gap-6">
                    <div class="sm:w-48 shrink-0">
                        @if ($fallen->portraitUrl())
                            <img src="{{ $fallen->portraitUrl() }}" alt="" class="w-full rounded-lg object-cover">
                        @else
                            <div class="aspect-[4/5] rounded-lg bg-gray-100 flex items-center justify-center text-5xl text-gray-300">🕯️</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $fallen->name }}</h1>
                        <p class="text-gray-500 mt-1">
                            {{ $fallen->squadron ? $fallen->squadron.' Squadron' : '' }}
                            {{ $fallen->academy_number ? '· '.$fallen->academy_number : '' }}
                        </p>
                        @if ($fallen->date_of_passing)
                            <p class="text-gray-500">Passed away {{ $fallen->date_of_passing->format('d F Y') }}</p>
                        @endif
                        @if ($fallen->biography)
                            <p class="mt-4 text-gray-700 whitespace-pre-line">{{ $fallen->biography }}</p>
                        @endif
                    </div>
                </div>

                @if (Auth::user()->isAdmin())
                    <form method="POST" action="{{ route('memorial.destroy', $fallen) }}" class="mt-4"
                          onsubmit="return confirm('Remove this memorial page?')">
                        @csrf @method('delete')
                        <button class="text-sm text-red-600 hover:underline">Remove memorial page</button>
                    </form>
                @endif
            </div>

            {{-- Photos --}}
            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                <h3 class="font-semibold text-gray-900">Photographs</h3>

                @if ($fallen->photos->isEmpty())
                    <p class="mt-2 text-sm text-gray-500">No photos yet. Share one below.</p>
                @else
                    <div class="mt-4 grid gap-3 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($fallen->photos as $photo)
                            <figure class="bg-gray-50 rounded-lg overflow-hidden">
                                <a href="{{ $photo->url() }}" target="_blank">
                                    <img src="{{ $photo->url() }}" alt="" class="aspect-square w-full object-cover">
                                </a>
                                @if ($photo->caption)
                                    <figcaption class="p-2 text-xs text-gray-500">{{ $photo->caption }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('memorial.photo', $fallen) }}" enctype="multipart/form-data"
                      class="mt-5 flex flex-col sm:flex-row sm:items-end gap-3 border-t border-gray-100 pt-5">
                    @csrf
                    <div>
                        <x-input-label for="photo" value="Add a photo" />
                        <input id="photo" name="photo" type="file" accept="image/*" required
                               class="block mt-1 text-sm text-gray-600 file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700">
                    </div>
                    <div class="flex-1">
                        <x-input-label for="caption" value="Caption (optional)" />
                        <x-text-input id="caption" name="caption" class="block mt-1 w-full" placeholder="e.g. NDA passing out, 2001" />
                    </div>
                    <x-primary-button>Upload</x-primary-button>
                </form>
            </div>

            {{-- Recollections --}}
            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                <h3 class="font-semibold text-gray-900">Recollections</h3>

                <form method="POST" action="{{ route('memorial.tribute', $fallen) }}" class="mt-4 space-y-3">
                    @csrf
                    <textarea name="body" rows="3" required
                              placeholder="Share a memory or incident with {{ Str::of($fallen->name)->before(' ') }}…"
                              class="block w-full border-gray-300 rounded-md shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('body') }}</textarea>
                    <x-input-error :messages="$errors->get('body')" />
                    <x-primary-button>Post recollection</x-primary-button>
                </form>

                <div class="mt-6 space-y-4">
                    @forelse ($fallen->tributes as $tribute)
                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-gray-800 whitespace-pre-line">{{ $tribute->body }}</p>
                            <p class="mt-1 text-xs text-gray-400">
                                — {{ Str::of($tribute->user->name)->before(' (') }}, {{ $tribute->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Be the first to share a memory.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
