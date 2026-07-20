<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Fallen Brothers</h2>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('memorial.create') }}"
                   class="rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    + Add a brother
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <p class="text-gray-600 mb-6">In lasting memory of the course-mates we have lost. Share your recollections and photographs.</p>

            @if ($fallen->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center text-gray-500">
                    No memorial pages yet.
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($fallen as $person)
                        <a href="{{ route('memorial.show', $person) }}"
                           class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                            <div class="aspect-[4/3] bg-gray-100">
                                @if ($person->portraitUrl())
                                    <img src="{{ $person->portraitUrl() }}" alt="" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-5xl text-gray-300">🕯️</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900">{{ $person->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $person->squadron ? $person->squadron.' Sqn' : '' }}
                                    @if ($person->date_of_passing) · d. {{ $person->date_of_passing->format('Y') }} @endif
                                </p>
                                <p class="text-xs text-gray-400 mt-2">
                                    {{ $person->tributes_count }} recollection{{ $person->tributes_count === 1 ? '' : 's' }}
                                    · {{ $person->photos_count }} photo{{ $person->photos_count === 1 ? '' : 's' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
