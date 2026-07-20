<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $event->title }}</h2>
            <a href="{{ route('visit.index') }}" class="text-sm text-slate-600 hover:underline">&larr; All events</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                <p class="text-gray-600">
                    @if ($event->starts_at) 📅 {{ $event->starts_at->format('l, d F Y · g:i A') }} @endif
                    @if ($event->ends_at) &ndash; {{ $event->ends_at->format('g:i A') }} @endif
                </p>
                @if ($event->location)
                    <p class="text-gray-600 mt-1">📍 {{ $event->location }}</p>
                @endif
                @if ($event->description)
                    <p class="mt-4 text-gray-800 whitespace-pre-line">{{ $event->description }}</p>
                @endif

                @if (Auth::user()->isAdmin())
                    <form method="POST" action="{{ route('visit.destroy', $event) }}" class="mt-4"
                          onsubmit="return confirm('Delete this event?')">
                        @csrf @method('delete')
                        <button class="text-sm text-red-600 hover:underline">Delete event</button>
                    </form>
                @endif
            </div>

            {{-- RSVP --}}
            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                <h3 class="font-semibold text-gray-900">Your RSVP</h3>
                <form method="POST" action="{{ route('visit.rsvp', $event) }}" class="mt-4 space-y-4">
                    @csrf
                    <div class="flex flex-wrap gap-3">
                        @foreach (['going' => 'Going', 'maybe' => 'Maybe', 'not_going' => "Can't make it"] as $val => $label)
                            <label class="flex items-center gap-2 rounded-md border px-4 py-2 cursor-pointer
                                          {{ ($myRsvp?->status ?? '') === $val ? 'border-slate-600 bg-slate-50' : 'border-gray-300' }}">
                                <input type="radio" name="status" value="{{ $val }}"
                                       {{ ($myRsvp?->status ?? '') === $val ? 'checked' : '' }}
                                       class="text-slate-600 focus:ring-slate-500">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="guests" value="Guests accompanying you" />
                            <x-text-input id="guests" name="guests" type="number" min="0" max="20" class="block mt-1 w-full"
                                          :value="old('guests', $myRsvp?->guests ?? 0)" />
                        </div>
                        <div>
                            <x-input-label for="note" value="Note (optional)" />
                            <x-text-input id="note" name="note" class="block mt-1 w-full" :value="old('note', $myRsvp?->note)" />
                        </div>
                    </div>
                    <x-primary-button>Save RSVP</x-primary-button>
                </form>
            </div>

            {{-- Attendees --}}
            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                <h3 class="font-semibold text-gray-900">Who's coming</h3>
                @php $going = $event->rsvps->where('status', 'going'); @endphp
                @if ($going->isEmpty())
                    <p class="mt-2 text-sm text-gray-500">No confirmed attendees yet.</p>
                @else
                    <ul class="mt-3 divide-y divide-gray-100">
                        @foreach ($going as $rsvp)
                            <li class="py-2 flex justify-between text-sm">
                                <span class="text-gray-800">{{ Str::of($rsvp->user->name)->before(' (') }}</span>
                                <span class="text-gray-500">
                                    {{ $rsvp->guests > 0 ? '+'.$rsvp->guests.' guest'.($rsvp->guests === 1 ? '' : 's') : '' }}
                                    {{ $rsvp->note }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-3 text-sm font-medium text-gray-700">
                        Total heads: {{ $going->count() + $going->sum('guests') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
