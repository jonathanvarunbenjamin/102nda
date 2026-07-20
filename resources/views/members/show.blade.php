<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $member->name }}</h2>
            <a href="{{ route('members.index') }}" class="text-sm text-slate-600 hover:underline">&larr; All members</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-5">
                    @if ($member->avatar)
                        <img src="{{ $member->avatar }}" alt="" class="h-20 w-20 rounded-full object-cover">
                    @else
                        <div class="h-20 w-20 rounded-full bg-slate-200 flex items-center justify-center text-2xl text-slate-600 font-semibold">
                            {{ Str::of($member->name)->substr(0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $member->name }}</h1>
                        <p class="text-gray-500">
                            {{ $member->profile?->squadron ? $member->profile->squadron.' Squadron' : '' }}
                            {{ $member->profile?->course ? '· '.$member->profile->course.' Course' : '' }}
                        </p>
                    </div>
                </div>

                @if ($member->profile?->bio)
                    <p class="mt-6 text-gray-700 whitespace-pre-line">{{ $member->profile->bio }}</p>
                @endif

                <dl class="mt-6 grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                    @php $p = $member->profile; @endphp
                    <x-detail label="Academy number" :value="$p?->academy_number" />
                    <x-detail label="Squadron" :value="$p?->squadron" />
                    <x-detail label="Email" :value="$member->email" />
                    <x-detail label="Phone" :value="$p?->phone" />
                    <x-detail label="Date of birth" :value="$p?->date_of_birth?->format('d M Y')" />
                    <x-detail label="Date of marriage" :value="$p?->date_of_marriage?->format('d M Y')" />
                    <x-detail label="Address" :value="$p?->address" class="sm:col-span-2" />
                </dl>
            </div>

            @php
                $spouse = $member->familyMembers->firstWhere('relation', 'spouse');
                $children = $member->familyMembers->where('relation', 'child');
            @endphp
            @if ($spouse || $children->count())
                <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
                    <h3 class="font-semibold text-gray-900">Family</h3>
                    <ul class="mt-3 space-y-2 text-sm text-gray-700">
                        @if ($spouse)
                            <li>💍 <span class="font-medium">{{ $spouse->name }}</span> (spouse)
                                {{ $spouse->date_of_birth ? '· '.$spouse->date_of_birth->format('d M Y') : '' }}</li>
                        @endif
                        @foreach ($children as $child)
                            <li>👶 <span class="font-medium">{{ $child->name }}</span>
                                {{ $child->date_of_birth ? '· '.$child->date_of_birth->format('d M Y') : '' }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
