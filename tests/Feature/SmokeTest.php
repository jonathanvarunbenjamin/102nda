<?php

namespace Tests\Feature;

use App\Models\FallenMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_member_and_admin_pages_load(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'approved']);
        $fallen = FallenMember::create(['name' => 'Test Brother', 'created_by' => $admin->id]);

        $pages = [
            '/dashboard',
            '/members',
            "/members/{$admin->id}",
            '/gallery',
            '/gallery/create',
            '/memorial',
            '/memorial/create',
            "/memorial/{$fallen->id}",
            '/visit',
            '/visit/create',
            '/forum',
            '/forum/create',
            '/profile',
            '/admin/members',
            '/admin/members?status=approved',
        ];

        foreach ($pages as $uri) {
            $this->actingAs($admin)->get($uri)->assertStatus(200);
        }
    }

    public function test_pending_user_is_sent_to_waiting_room(): void
    {
        $pending = User::factory()->create(['status' => 'pending']);

        $this->actingAs($pending)->get('/dashboard')->assertRedirect(route('pending'));
    }

    public function test_non_admin_cannot_reach_admin_area(): void
    {
        $member = User::factory()->create(['role' => 'member', 'status' => 'approved']);

        $this->actingAs($member)->get('/admin/members')->assertForbidden();
    }

    public function test_member_can_save_profile_with_family(): void
    {
        $member = User::factory()->create(['status' => 'approved']);

        $this->actingAs($member)->patch('/profile', [
            'name' => 'Wg Cdr Test',
            'academy_number' => '12345',
            'squadron' => 'Hunter',
            'spouse_name' => 'Test Spouse',
            'children' => [
                ['name' => 'Child One', 'date_of_birth' => '2005-01-01'],
                ['name' => 'Child Two'],
            ],
        ])->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('profiles', ['academy_number' => '12345', 'squadron' => 'Hunter']);
        $this->assertDatabaseHas('family_members', ['relation' => 'spouse', 'name' => 'Test Spouse']);
        $this->assertEquals(2, $member->familyMembers()->where('relation', 'child')->count());
    }
}
