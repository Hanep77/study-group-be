<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $groupsCount = $user->groups()->count();
        $tasksTodoCount = $user->assignedTasks()->where('status', 'todo')->count();
        $tasksInProgressCount = $user->assignedTasks()->where('status', 'in_progress')->count();
        $tasksDoneCount = $user->assignedTasks()->where('status', 'done')->count();

        $myTasks = $user->assignedTasks()
            ->with('group')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'groups_count' => $groupsCount,
            'tasks_todo_count' => $tasksTodoCount,
            'tasks_in_progress_count' => $tasksInProgressCount,
            'tasks_done_count' => $tasksDoneCount,
            'recent_tasks' => $myTasks->take(5), // Reuse myTasks for recent_tasks
            'groups' => $user->groups()->with('creator', 'members')->get(),
            'myTasks' => $myTasks,
        ]);
    }
}
