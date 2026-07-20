<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Members</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="GET" action="{{ route('members.index') }}" class="flex gap-2">
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="Search by name, academy no. or squadron…"
                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                <x-primary-button>Search</x-primary-button>
            </form>

            @if ($members->isEmpty())
                <p class="text-gray-500">No members found.</p>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($members as $member)
                        <a href="{{ route('members.show', $member) }}"
                           class="flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                            @if ($member->avatar)
                                <img src="{{ $member->avatar }}" alt="" class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div class="h-12 w-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-semibold">
                                    {{ Str::of($member->name)->substr(0, 1) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $member->name }}</p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $member->profile?->squadron ? $member->profile->squadron.' Sqn' : '' }}
                                    {{ $member->profile?->academy_number ? '· '.$member->profile->academy_number : '' }}
                                </p>
                            </div>
                            @if ($member->isAdmin())
                                <span class="ml-auto text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded">Admin</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div>{{ $members->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
