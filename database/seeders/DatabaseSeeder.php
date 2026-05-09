<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Task;
use App\Models\Checklist;
use App\Models\TaskOverview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $user1 = User::create([
            'name' => 'Yudis',
            'email' => 'yudis@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::create([
            'name' => 'Hanep',
            'email' => 'hanep@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $user3 = User::create([
            'name' => 'Andi',
            'email' => 'andi@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Create Groups
        $group1 = Group::create([
            'creator_id' => $user1->id,
            'name' => 'Proyek Mobile App',
            'description' => 'Grup pengembangan aplikasi Study Group menggunakan React Native.',
            'deadline' => now()->addMonths(1),
        ]);

        $group2 = Group::create([
            'creator_id' => $user2->id,
            'name' => 'Belajar Laravel',
            'description' => 'Diskusi dan tugas seputar framework Laravel.',
            'deadline' => now()->addWeeks(2),
        ]);

        // 3. Add Members
        GroupMember::create(['group_id' => $group1->id, 'user_id' => $user1->id, 'role' => 'admin']);
        GroupMember::create(['group_id' => $group1->id, 'user_id' => $user2->id, 'role' => 'member']);
        GroupMember::create(['group_id' => $group1->id, 'user_id' => $user3->id, 'role' => 'member']);

        GroupMember::create(['group_id' => $group2->id, 'user_id' => $user2->id, 'role' => 'admin']);
        GroupMember::create(['group_id' => $group2->id, 'user_id' => $user1->id, 'role' => 'member']);

        // 4. Create Overview
        TaskOverview::create([
            'group_id' => $group1->id,
            'content' => "## Project Roadmap\n1. Setup Environment\n2. Auth Integration\n3. Feature Group Development\n4. Final Testing",
            'updated_by' => $user1->id
        ]);

        // 5. Create Tasks for Group 1
        $task1 = Task::create([
            'group_id' => $group1->id,
            'created_by' => $user1->id,
            'title' => 'Integrasi API Login',
            'description' => 'Menghubungkan layar login mobile ke endpoint Laravel Sanctum.',
            'priority' => 'high',
            'status' => 'done',
            'assigned_to' => $user1->id,
            'due_date' => now()->addDays(3),
        ]);

        $task2 = Task::create([
            'group_id' => $group1->id,
            'created_by' => $user1->id,
            'title' => 'Desain Layar Dashboard',
            'description' => 'Membuat tampilan ringkasan tugas untuk user.',
            'priority' => 'medium',
            'status' => 'in_progress',
            'assigned_to' => $user2->id,
            'due_date' => now()->addDays(7),
        ]);

        $task3 = Task::create([
            'group_id' => $group1->id,
            'created_by' => $user2->id,
            'title' => 'Setup Database Migration',
            'description' => 'Menyusun skema database untuk grup dan tugas.',
            'priority' => 'low',
            'status' => 'todo',
            'assigned_to' => $user3->id,
            'due_date' => now()->addDays(2),
        ]);

        // 6. Add Checklists
        Checklist::create(['task_id' => $task1->id, 'item' => 'Buat API Client', 'completed' => true]);
        Checklist::create(['task_id' => $task1->id, 'item' => 'Handle Token Storage', 'completed' => true]);
        Checklist::create(['task_id' => $task1->id, 'item' => 'Test redirect login', 'completed' => true]);

        Checklist::create(['task_id' => $task2->id, 'item' => 'Slicing UI', 'completed' => true]);
        Checklist::create(['task_id' => $task2->id, 'item' => 'Integrasi Data', 'completed' => false]);
    }
}
