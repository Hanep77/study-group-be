<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Group;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Get all tasks for a group
     */
    public function index(Request $request, Group $group)
    {
        if (!$group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $query = $group->tasks()->with('creator', 'assignee', 'checklists');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->get('priority'));
        }

        // Filter by assigned_to
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->get('assigned_to'));
        }

        $tasks = $query->paginate(20);

        return response()->json([
            'data' => $tasks->items(),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ]
        ]);
    }

    /**
     * Create task
     */
    public function store(Request $request, Group $group)
    {
        if (!$group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create([
            'group_id' => $group->id,
            'created_by' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'] ?? 'medium',
            'assigned_to' => $validated['assigned_to'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task->load('creator', 'assignee', 'checklists')
        ], 201);
    }

    /**
     * Get task detail
     */
    public function show(Request $request, Task $task)
    {
        if (!$task->group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'data' => $task->load('creator', 'assignee', 'checklists', 'group')
        ]);
    }

    /**
     * Update task
     */
    public function update(Request $request, Task $task)
    {
        if (!$task->group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:todo,in_progress,done',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => $task->load('creator', 'assignee', 'checklists')
        ]);
    }

    /**
     * Delete task
     */
    public function destroy(Request $request, Task $task)
    {
        if ($task->created_by !== $request->user()->id && !$task->group->isAdmin($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }

    /**
     * Update task status
     */
    public function updateStatus(Request $request, Task $task)
    {
        if (!$task->group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done'
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Task status updated',
            'data' => $task
        ]);
    }
}