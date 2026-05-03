<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\TaskOverview;
use Illuminate\Http\Request;

class TaskOverviewController extends Controller
{
    public function show(Group $group, Request $request)
    {
        if (!$group->isMember($request->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $overview = $group->taskOverview()->first();

        return response()->json($overview);
    }

    public function store(Group $group, Request $request)
    {
        if (!$group->isMember($request->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $overview = TaskOverview::updateOrCreate(
            ['group_id' => $group->id],
            [
                'content' => $validated['content'],
                'updated_by' => $request->user()->id,
            ]
        );

        return response()->json($overview);
    }
}
