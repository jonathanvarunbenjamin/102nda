<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Plan the Visit</h2>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('visit.create') }}"
                   class="rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    + New event
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if ($events->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center text-gray-500">
                    No events planned yet.
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('visit.create') }}" class="text-slate-700 underline">Create the first one</a>.
                    @endif
                </div>
            @else
                @foreach ($events as $event)
                    <a href="{{ route('visit.show', $event) }}"
                       class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    @if ($event->starts_at) 📅 {{ $event->starts_at->format('D, d M Y · g:i A') }} @endif
                                    @if ($event->location) · 📍 {{ $event->location }} @endif
                                </p>
                            </div>
                            <span class="shrink-0 rounded-full bg-green-100 text-green-700 text-xs px-3 py-1">
                                {{ $event->going_count }} going
                            </span>
                        </div>
                        @if ($event->description)
                            <p class="mt-3 text-gray-600 line-clamp-2">{{ $event->description }}</p>
                        @endif
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
