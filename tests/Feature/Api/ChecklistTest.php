<?php

namespace Tests\Feature\Api;

use App\Models\Checklist;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChecklistTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_add_checklist_item()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $task = Task::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/tasks/{$task->id}/checklist", [
                'item' => 'New Checklist Item'
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('checklists', [
            'task_id' => $task->id,
            'item' => 'New Checklist Item',
        ]);
    }

    public function test_member_can_toggle_checklist_item()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $task = Task::factory()->create(['group_id' => $group->id]);
        $checklist = Checklist::factory()->create([
            'task_id' => $task->id,
            'completed' => false
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson("/api/checklists/{$checklist->id}/toggle");

        $response->assertStatus(200);
        $this->assertDatabaseHas('checklists', [
            'id' => $checklist->id,
            'completed' => true,
        ]);
    }

    public function test_member_can_delete_checklist_item()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $task = Task::factory()->create(['group_id' => $group->id]);
        $checklist = Checklist::factory()->create(['task_id' => $task->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/checklists/{$checklist->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('checklists', ['id' => $checklist->id]);
    }
}
