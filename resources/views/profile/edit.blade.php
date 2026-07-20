<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Profile</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'profile-updated')
                <div class="rounded-md bg-green-50 border border-green-200 p-3 text-sm text-green-800">
                    Your profile has been saved.
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}"
                  class="bg-white shadow sm:rounded-lg p-6 sm:p-8 space-y-8">
                @csrf
                @method('patch')

                {{-- Identity --}}
                <section>
                    <h3 class="text-lg font-semibold text-gray-900">Your details</h3>
                    <p class="text-sm text-gray-500">Signed in with Google as {{ $user->email }}</p>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-input-label for="name" value="Full name" />
                            <x-text-input id="name" name="name" class="block mt-1 w-full"
                                          :value="old('name', $user->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="academy_number" value="Academy number" />
                            <x-text-input id="academy_number" name="academy_number" class="block mt-1 w-full"
                                          :value="old('academy_number', $user->profile?->academy_number)" />
                        </div>
                        <div>
                            <x-input-label for="squadron" value="Squadron" />
                            <x-text-input id="squadron" name="squadron" class="block mt-1 w-full"
                                          :value="old('squadron', $user->profile?->squadron)" />
                        </div>
                        <div>
                            <x-input-label for="course" value="Course" />
                            <x-text-input id="course" name="course" class="block mt-1 w-full"
                                          :value="old('course', $user->profile?->course ?? '102nd')" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone number" />
                            <x-text-input id="phone" name="phone" class="block mt-1 w-full"
                                          :value="old('phone', $user->profile?->phone)" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="address" value="Address" />
                            <textarea id="address" name="address" rows="2"
                                      class="block mt-1 w-full border-gray-300 focus:border-slate-500 focus:ring-slate-500 rounded-md shadow-sm">{{ old('address', $user->profile?->address) }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="date_of_birth" value="Date of birth" />
                            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="block mt-1 w-full"
                                          :value="old('date_of_birth', $user->profile?->date_of_birth?->format('Y-m-d'))" />
                        </div>
                        <div>
                            <x-input-label for="date_of_marriage" value="Date of marriage" />
                            <x-text-input id="date_of_marriage" name="date_of_marriage" type="date" class="block mt-1 w-full"
                                          :value="old('date_of_marriage', $user->profile?->date_of_marriage?->format('Y-m-d'))" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="bio" value="About you (optional)" />
                            <textarea id="bio" name="bio" rows="3"
                                      class="block mt-1 w-full border-gray-300 focus:border-slate-500 focus:ring-slate-500 rounded-md shadow-sm"
                                      placeholder="Current city, career, service highlights…">{{ old('bio', $user->profile?->bio) }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- Family --}}
                <section class="border-t border-gray-100 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900">Family</h3>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="spouse_name" value="Spouse's name" />
                            <x-text-input id="spouse_name" name="spouse_name" class="block mt-1 w-full"
                                          :value="old('spouse_name', $spouse?->name)" />
                        </div>
                        <div>
                            <x-input-label for="spouse_dob" value="Spouse's date of birth" />
                            <x-text-input id="spouse_dob" name="spouse_dob" type="date" class="block mt-1 w-full"
                                          :value="old('spouse_dob', $spouse?->date_of_birth?->format('Y-m-d'))" />
                        </div>
                    </div>

                    <p class="mt-6 text-sm font-medium text-gray-700">Children (up to 3)</p>
                    <div class="mt-2 space-y-3">
                        @for ($i = 0; $i < 3; $i++)
                            @php $child = $children[$i] ?? null; @endphp
                            <div class="grid gap-4 sm:grid-cols-2">
                                <x-text-input name="children[{{ $i }}][name]" class="block w-full"
                                              placeholder="Child {{ $i + 1 }} name"
                                              :value="old('children.'.$i.'.name', $child?->name)" />
                                <x-text-input name="children[{{ $i }}][date_of_birth]" type="date" class="block w-full"
                                              :value="old('children.'.$i.'.date_of_birth', $child?->date_of_birth?->format('Y-m-d'))" />
                            </div>
                        @endfor
                    </div>
                </section>

                <div class="flex items-center gap-4">
                    <x-primary-button>Save profile</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
