<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome, {{ Str::of(Auth::user()->name)->before(' (') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Hero --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-700 rounded-xl shadow p-8 text-white">
                <h1 class="text-2xl font-bold">102nd NDA Course &mdash; Silver Jubilee</h1>
                <p class="mt-2 text-slate-200 max-w-2xl">
                    Welcome to our private course reunion home. Reconnect with course-mates, share memories and
                    photos, honour our fallen brothers, and help plan the get-together.
                </p>
                @unless (Auth::user()->profile && Auth::user()->profile->academy_number)
                    <a href="{{ route('profile.edit') }}"
                       class="inline-block mt-4 rounded-md bg-white/95 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-white">
                        Complete your profile &rarr;
                    </a>
                @endunless
            </div>

            {{-- Section cards --}}
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $cards = [
                        ['members.index', 'Members', 'Browse the course directory and everyone\'s details.', '👥'],
                        ['gallery.index', 'Photo Gallery', 'Share and relive our albums together.', '📷'],
                        ['memorial.index', 'Fallen Brothers', 'Remember and pay tribute to those we\'ve lost.', '🕯️'],
                        ['visit.index', 'Plan the Visit', 'Reunion events, dates and who\'s coming.', '📅'],
                        ['forum.index', 'Forums', 'Discussions, planning and staying in touch.', '💬'],
                        ['profile.edit', 'My Profile', 'Your details, family and contact info.', '📝'],
                    ];
                @endphp

                @foreach ($cards as [$route, $title, $desc, $icon])
                    <a href="{{ route($route) }}"
                       class="group bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-slate-300 transition">
                        <div class="text-3xl">{{ $icon }}</div>
                        <h3 class="mt-3 font-semibold text-gray-900 group-hover:text-slate-700">{{ $title }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $desc }}</p>
                    </a>
                @endforeach
            </div>

            @if (Auth::user()->isAdmin())
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-amber-900">Administrator</h3>
                            <p class="text-sm text-amber-800">Approve new members and manage the course roster.</p>
                        </div>
                        <a href="{{ route('admin.members.index') }}"
                           class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                            Open Admin
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
