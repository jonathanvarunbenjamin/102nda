<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Album</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('gallery.store') }}" enctype="multipart/form-data"
                  class="bg-white rounded-xl shadow-sm p-6 sm:p-8 space-y-5">
                @csrf
                <div>
                    <x-input-label for="title" value="Album title" />
                    <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title')" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="description" value="Description (optional)" />
                    <textarea id="description" name="description" rows="2"
                              class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('description') }}</textarea>
                </div>
                <div>
                    <x-input-label for="photos" value="Photos" />
                    <input id="photos" name="photos[]" type="file" accept="image/*" multiple
                           class="block mt-1 w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                    <p class="mt-1 text-xs text-gray-500">You can select multiple images (up to 8&nbsp;MB each). You can also add more later.</p>
                    <x-input-error :messages="$errors->get('photos.*')" class="mt-2" />
                </div>
                <div class="flex items-center gap-3">
                    <x-primary-button>Create album</x-primary-button>
                    <a href="{{ route('gallery.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
