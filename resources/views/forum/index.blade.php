<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Forums</h2>
            <a href="{{ route('forum.create') }}"
               class="rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                + New topic
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($threads->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center text-gray-500">
                    No discussions yet. <a href="{{ route('forum.create') }}" class="text-slate-700 underline">Start one</a>.
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100">
                    @foreach ($threads as $thread)
                        <a href="{{ route('forum.show', $thread) }}" class="flex items-center justify-between gap-4 p-4 hover:bg-gray-50">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">
                                    @if ($thread->pinned) 📌 @endif
                                    @if ($thread->locked) 🔒 @endif
                                    {{ $thread->title }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    @if ($thread->category) <span class="bg-gray-100 rounded px-1.5 py-0.5 text-xs">{{ $thread->category }}</span> @endif
                                    by {{ Str::of($thread->user->name)->before(' (') }}
                                    · {{ ($thread->last_posted_at ?? $thread->created_at)->diffForHumans() }}
                                </p>
                            </div>
                            <span class="shrink-0 text-sm text-gray-500">{{ $thread->posts_count }} 💬</span>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6">{{ $threads->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
