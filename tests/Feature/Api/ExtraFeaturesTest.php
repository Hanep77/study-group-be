<?php

namespace Tests\Feature\Api;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtraFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_dashboard_data()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        Task::factory()->create([
            'group_id' => $group->id,
            'assigned_to' => $user->id,
            'status' => 'todo'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'groups_count',
                'tasks_todo_count',
                'tasks_in_progress_count',
                'tasks_done_count',
                'recent_tasks'
            ]);
    }

    public function test_member_can_list_group_members()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/groups/{$group->id}/members");

        $response->assertStatus(200);
    }

    public function test_member_can_get_and_update_overview()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        // Create overview
        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/groups/{$group->id}/overview", [
                'content' => 'This is a group overview'
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('content', 'This is a group overview');

        // Get overview
        $response = $this->getJson("/api/groups/{$group->id}/overview");
        $response->assertStatus(200)
            ->assertJsonPath('content', 'This is a group overview');
    }
}
