<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Event</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('visit.store') }}"
                  class="bg-white rounded-xl shadow-sm p-6 sm:p-8 space-y-5">
                @csrf
                <div>
                    <x-input-label for="title" value="Event title" />
                    <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title')" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="location" value="Location" />
                    <x-text-input id="location" name="location" class="block mt-1 w-full" :value="old('location')" />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="starts_at" value="Starts" />
                        <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="block mt-1 w-full" :value="old('starts_at')" />
                    </div>
                    <div>
                        <x-input-label for="ends_at" value="Ends (optional)" />
                        <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="block mt-1 w-full" :value="old('ends_at')" />
                        <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
                    </div>
                </div>
                <div>
                    <x-input-label for="description" value="Details" />
                    <textarea id="description" name="description" rows="4"
                              class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('description') }}</textarea>
                </div>
                <div class="flex items-center gap-3">
                    <x-primary-button>Create event</x-primary-button>
                    <a href="{{ route('visit.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
