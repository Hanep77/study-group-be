<?php

namespace Tests\Feature\Api;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_list_group_tasks()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        Task::factory()->count(5)->create(['group_id' => $group->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/groups/{$group->id}/tasks");

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_member_can_create_task()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        $taskData = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'priority' => 'high',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/groups/{$group->id}/tasks", $taskData);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'New Task');

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'group_id' => $group->id,
            'created_by' => $user->id,
        ]);
    }

    public function test_member_can_view_task()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $task = Task::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $task->id);
    }

    public function test_member_can_update_task()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $task = Task::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/tasks/{$task->id}", [
                'title' => 'Updated Task Title',
                'status' => 'in_progress'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'in_progress',
        ]);
    }

    public function test_creator_can_delete_task()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $task = Task::factory()->create([
            'group_id' => $group->id,
            'created_by' => $user->id
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_admin_can_delete_others_task()
    {
        $admin = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $admin->id,
            'group_id' => $group->id,
            'role' => 'admin',
        ]);
        
        $task = Task::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_member_can_update_task_status()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        GroupMember::factory()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $task = Task::factory()->create(['group_id' => $group->id, 'status' => 'todo']);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson("/api/tasks/{$task->id}/status", ['status' => 'done']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'done',
        ]);
    }
}
