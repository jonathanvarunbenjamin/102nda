<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add a Fallen Brother</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('memorial.store') }}" enctype="multipart/form-data"
                  class="bg-white rounded-xl shadow-sm p-6 sm:p-8 space-y-5">
                @csrf
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <x-input-label for="academy_number" value="Academy no." />
                        <x-text-input id="academy_number" name="academy_number" class="block mt-1 w-full" :value="old('academy_number')" />
                    </div>
                    <div>
                        <x-input-label for="squadron" value="Squadron" />
                        <x-text-input id="squadron" name="squadron" class="block mt-1 w-full" :value="old('squadron')" />
                    </div>
                    <div>
                        <x-input-label for="date_of_passing" value="Date of passing" />
                        <x-text-input id="date_of_passing" name="date_of_passing" type="date" class="block mt-1 w-full" :value="old('date_of_passing')" />
                    </div>
                </div>
                <div>
                    <x-input-label for="biography" value="Biography / remembrance (optional)" />
                    <textarea id="biography" name="biography" rows="4"
                              class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('biography') }}</textarea>
                </div>
                <div>
                    <x-input-label for="portrait" value="Portrait photo (optional)" />
                    <input id="portrait" name="portrait" type="file" accept="image/*"
                           class="block mt-1 w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                </div>
                <div class="flex items-center gap-3">
                    <x-primary-button>Create memorial page</x-primary-button>
                    <a href="{{ route('memorial.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
