<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /** Show the signed-in member's own profile edit form. */
    public function edit(Request $request)
    {
        $user = $request->user();
        $user->load(['profile', 'familyMembers']);

        $spouse = $user->familyMembers->firstWhere('relation', FamilyMember::RELATION_SPOUSE);
        $children = $user->familyMembers->where('relation', FamilyMember::RELATION_CHILD)->values();

        return view('profile.edit', compact('user', 'spouse', 'children'));
    }

    /** Save the member's profile, service details and family. */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academy_number' => ['nullable', 'string', 'max:100'],
            'squadron' => ['nullable', 'string', 'max:100'],
            'course' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'date_of_birth' => ['nullable', 'date'],
            'date_of_marriage' => ['nullable', 'date'],
            'bio' => ['nullable', 'string', 'max:2000'],

            'spouse_name' => ['nullable', 'string', 'max:255'],
            'spouse_dob' => ['nullable', 'date'],

            'children' => ['nullable', 'array', 'max:'.FamilyMember::MAX_CHILDREN],
            'children.*.name' => ['nullable', 'string', 'max:255'],
            'children.*.date_of_birth' => ['nullable', 'date'],
        ]);

        // Core identity
        $user->update(['name' => $data['name']]);

        // Profile (one-to-one)
        $user->profile()->updateOrCreate([], [
            'academy_number' => $data['academy_number'] ?? null,
            'squadron' => $data['squadron'] ?? null,
            'course' => $data['course'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'date_of_marriage' => $data['date_of_marriage'] ?? null,
            'bio' => $data['bio'] ?? null,
        ]);

        // Rebuild family members from the form (simple + reliable at this scale).
        $user->familyMembers()->delete();

        if (! empty($data['spouse_name'])) {
            $user->familyMembers()->create([
                'relation' => FamilyMember::RELATION_SPOUSE,
                'name' => $data['spouse_name'],
                'date_of_birth' => $data['spouse_dob'] ?? null,
            ]);
        }

        foreach ($data['children'] ?? [] as $child) {
            if (! empty($child['name'])) {
                $user->familyMembers()->create([
                    'relation' => FamilyMember::RELATION_CHILD,
                    'name' => $child['name'],
                    'date_of_birth' => $child['date_of_birth'] ?? null,
                ]);
            }
        }

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }
}
