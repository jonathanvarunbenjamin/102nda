<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /** Admin overview: pending queue + all members. */
    public function index(Request $request)
    {
        $filter = $request->query('status', 'pending');

        $users = User::query()
            ->with('profile')
            ->when(in_array($filter, ['pending', 'approved', 'rejected', 'suspended'], true),
                fn ($q) => $q->where('status', $filter))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        $counts = [
            'pending' => User::where('status', 'pending')->count(),
            'approved' => User::where('status', 'approved')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.members.index', compact('users', 'filter', 'counts'));
    }

    /** Change a member's status: approved | rejected | suspended | pending. */
    public function updateStatus(Request $request, User $user)
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected,suspended,pending'],
        ]);

        $user->update([
            'status' => $data['status'],
            'approved_at' => $data['status'] === 'approved' ? ($user->approved_at ?? now()) : null,
            'approved_by' => $data['status'] === 'approved' ? Auth::id() : null,
        ]);

        return back()->with('status', "Member {$user->name} set to {$data['status']}.");
    }

    /** Promote or demote a member's admin role. */
    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'in:member,admin'],
        ]);

        // Safety: don't let the last admin remove their own admin rights.
        if ($data['role'] === 'member' && $user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('status', 'Cannot remove the last remaining admin.');
        }

        $user->update(['role' => $data['role']]);

        return back()->with('status', "{$user->name} is now a {$data['role']}.");
    }
}
