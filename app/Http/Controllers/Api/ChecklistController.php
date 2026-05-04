<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\Task;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    /**
     * Add checklist item
     */
    public function store(Request $request, Task $task)
    {
        if (!$task->group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'item' => 'required|string|max:255',
        ]);

        $checklist = Checklist::create([
            'task_id' => $task->id,
            'item' => $validated['item'],
            'completed' => false,
        ]);

        return response()->json([
            'message' => 'Checklist item added',
            'data' => $checklist
        ], 201);
    }

    /**
     * Get checklist items for a task
     */
    public function index(Task $task, Request $request)
    {
        if (!$task->group->isMember($request->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($task->checklists);
    }

    /**
     * Update checklist item
     */
    public function update(Request $request, Checklist $checklist)
    {
        if (!$checklist->task->group->isMember($request->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'item' => 'nullable|string|max:255',
            'completed' => 'nullable|boolean',
        ]);

        $checklist->update($validated);

        return response()->json($checklist);
    }

    /**
     * Toggle checklist item
     */
    public function toggle(Request $request, Checklist $checklist)
    {
        if (!$checklist->task->group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $checklist->update(['completed' => !$checklist->completed]);

        return response()->json([
            'message' => 'Checklist toggled',
            'data' => $checklist
        ]);
    }

    /**
     * Delete checklist item
     */
    public function destroy(Request $request, Checklist $checklist)
    {
        if (!$checklist->task->group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $checklist->delete();

        return response()->json([
            'message' => 'Checklist item deleted'
        ]);
    }
}