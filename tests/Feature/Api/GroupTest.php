<?php

namespace Tests\Feature\Api;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_their_groups()
    {
        $user = User::factory()->create();
        $groups = Group::factory()->count(3)->create();
        
        foreach ($groups as $group) {
            GroupMember::factory()->create([
                'user_id' => $user->id,
                'group_id' => $group->id,
            ]);
        }

        $otherGroup = Group::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/groups');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_create_group()
    {
        $user = User::factory()->create();
        $groupData = [
            'name' => 'Study Group Alpha',
            'description' => 'A group for studying PHP',
            'deadline' => now()->addMonth()->format('Y-m-d'),
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/groups', $groupData);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', $groupData['name']);

        $this->assertDatabaseHas('groups', [
            'name' => 'Study Group Alpha',
            'creator_id' => $user->id,
        ]);

        $this->assertDatabaseHas('group_members', [
            'user_id' => $user->id,
            'role' => 'admin',
        ]);
    }

    public function test_member_can_view_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $group->id);
    }

    public function test_non_member_cannot_view_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/groups/{$group->id}");

        $response->assertStatus(403);
    }

    public function test_admin_can_update_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'role' => 'admin',
        ]);

        $updateData = [
            'name' => 'Updated Group Name',
            'description' => 'Updated Description',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/groups/{$group->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Group Name',
        ]);
    }

    public function test_non_admin_cannot_update_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'role' => 'member',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/groups/{$group->id}", ['name' => 'New Name']);

        $response->assertStatus(403);
    }

    public function test_creator_can_delete_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create(['creator_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/groups/{$group->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }

    public function test_non_creator_cannot_delete_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create(); // Different creator

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/groups/{$group->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_join_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/groups/join', ['code' => (string) $group->id]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('group_members', [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'role' => 'member',
        ]);
    }

    public function test_user_can_leave_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'role' => 'member',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/groups/{$group->id}/leave");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('group_members', [
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
    }
}
