<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                @if ($thread->pinned) 📌 @endif
                @if ($thread->locked) 🔒 @endif
                {{ $thread->title }}
            </h2>
            <a href="{{ route('forum.index') }}" class="text-sm text-slate-600 hover:underline shrink-0">&larr; Forums</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (Auth::user()->isAdmin() || Auth::id() === $thread->user_id)
                <div class="flex flex-wrap gap-3 text-sm">
                    @if (Auth::user()->isAdmin())
                        <form method="POST" action="{{ route('forum.toggle', $thread) }}">
                            @csrf @method('patch')
                            <input type="hidden" name="field" value="pinned">
                            <button class="text-slate-600 hover:underline">{{ $thread->pinned ? 'Unpin' : 'Pin' }}</button>
                        </form>
                        <form method="POST" action="{{ route('forum.toggle', $thread) }}">
                            @csrf @method('patch')
                            <input type="hidden" name="field" value="locked">
                            <button class="text-slate-600 hover:underline">{{ $thread->locked ? 'Unlock' : 'Lock' }}</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('forum.destroy', $thread) }}"
                          onsubmit="return confirm('Delete this thread?')">
                        @csrf @method('delete')
                        <button class="text-red-600 hover:underline">Delete thread</button>
                    </form>
                </div>
            @endif

            @foreach ($thread->posts as $post)
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex items-center gap-3">
                        @if ($post->user->avatar)
                            <img src="{{ $post->user->avatar }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::of($post->user->name)->before(' (') }}</p>
                            <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <p class="mt-3 text-gray-800 whitespace-pre-line">{{ $post->body }}</p>
                </div>
            @endforeach

            @if ($thread->locked && ! Auth::user()->isAdmin())
                <p class="text-sm text-gray-500 text-center py-4">🔒 This thread is locked. No new replies.</p>
            @else
                <form method="POST" action="{{ route('forum.reply', $thread) }}"
                      class="bg-white rounded-xl shadow-sm p-5 space-y-3">
                    @csrf
                    <x-input-label for="body" value="Your reply" />
                    <textarea id="body" name="body" rows="4" required
                              class="block w-full border-gray-300 rounded-md shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('body') }}</textarea>
                    <x-input-error :messages="$errors->get('body')" />
                    <x-primary-button>Post reply</x-primary-button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
