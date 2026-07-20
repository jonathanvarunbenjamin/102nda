<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Topic</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('forum.store') }}"
                  class="bg-white rounded-xl shadow-sm p-6 sm:p-8 space-y-5">
                @csrf
                <div>
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title')" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="category" value="Category (optional)" />
                    <x-text-input id="category" name="category" class="block mt-1 w-full" :value="old('category')"
                                  placeholder="e.g. Reunion, General, Logistics" />
                </div>
                <div>
                    <x-input-label for="body" value="Message" />
                    <textarea id="body" name="body" rows="6" required
                              class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('body') }}</textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                </div>
                <div class="flex items-center gap-3">
                    <x-primary-button>Post topic</x-primary-button>
                    <a href="{{ route('forum.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
