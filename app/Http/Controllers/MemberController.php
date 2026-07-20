<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /** Directory of approved members, searchable. */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $members = User::query()
            ->where('status', 'approved')
            ->with('profile')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('profile', function ($p) use ($search) {
                            $p->where('academy_number', 'like', "%{$search}%")
                                ->orWhere('squadron', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(24)
            ->withQueryString();

        return view('members.index', compact('members', 'search'));
    }

    /** A single member's public-to-members profile. */
    public function show(User $member)
    {
        abort_unless($member->isApproved(), 404);

        $member->load(['profile', 'familyMembers']);

        return view('members.show', compact('member'));
    }
}
