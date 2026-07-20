<?php

namespace App\Http\Controllers;

use App\Models\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $threads = ForumThread::with('user')
            ->withCount('posts')
            ->orderByDesc('pinned')
            ->orderByRaw('COALESCE(last_posted_at, created_at) desc')
            ->paginate(25);

        return view('forum.index', compact('threads'));
    }

    public function create()
    {
        return view('forum.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $thread = Auth::user()->forumThreads()->create([
            'title' => $data['title'],
            'category' => $data['category'] ?? null,
            'last_posted_at' => now(),
        ]);

        $thread->posts()->create([
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        return redirect()->route('forum.show', $thread)->with('status', 'Thread posted.');
    }

    public function show(ForumThread $thread)
    {
        $thread->load(['user', 'posts.user']);

        return view('forum.show', compact('thread'));
    }

    public function reply(Request $request, ForumThread $thread)
    {
        abort_if($thread->locked && ! Auth::user()->isAdmin(), 403, 'This thread is locked.');

        $data = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $thread->posts()->create([
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        $thread->update(['last_posted_at' => now()]);

        return back()->with('status', 'Reply posted.');
    }

    /** Admins can pin/lock threads. */
    public function toggle(Request $request, ForumThread $thread)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $field = $request->validate([
            'field' => ['required', 'in:pinned,locked'],
        ])['field'];

        $thread->update([$field => ! $thread->{$field}]);

        return back();
    }

    public function destroy(ForumThread $thread)
    {
        abort_unless(Auth::id() === $thread->user_id || Auth::user()->isAdmin(), 403);
        $thread->delete();

        return redirect()->route('forum.index')->with('status', 'Thread deleted.');
    }
}
