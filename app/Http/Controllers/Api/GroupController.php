<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Get all groups for authenticated user
     */
    public function index(Request $request)
    {
        $groups = $request->user()
            ->groups()
            ->with('creator:id,name,email', 'members')
            ->paginate(15);

        return response()->json([
            'data' => $groups->items(),
            'pagination' => [
                'current_page' => $groups->currentPage(),
                'last_page' => $groups->lastPage(),
                'per_page' => $groups->perPage(),
                'total' => $groups->total(),
            ]
        ]);
    }

    /**
     * Create new group
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $group = Group::create([
            'creator_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
        ]);

        // Add creator as admin
        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => $request->user()->id,
            'role' => 'admin',
        ]);

        return response()->json([
            'message' => 'Group created successfully',
            'data' => $group->load('creator', 'members')
        ], 201);
    }

    /**
     * Get group details
     */
    public function show(Group $group, Request $request)
    {
        if (!$group->isMember($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'data' => $group->load('creator', 'members', 'tasks', 'taskOverview')
        ]);
    }

    /**
     * Update group
     */
    public function update(Request $request, Group $group)
    {
        if (!$group->isAdmin($request->user())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:active,completed,archived',
        ]);

        $group->update($validated);

        return response()->json([
            'message' => 'Group updated successfully',
            'data' => $group
        ]);
    }

    /**
     * Delete group
     */
    public function destroy(Request $request, Group $group)
    {
        if ($group->creator_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Only group creator can delete'
            ], 403);
        }

        $group->delete();

        return response()->json([
            'message' => 'Group deleted successfully'
        ]);
    }

    /**
     * Join group
     */
    public function join(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        // Implementasi kode join dapat disesuaikan
        $group = Group::where('id', $validated['code'])->first();

        if (!$group) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }

        if ($group->isMember($request->user())) {
            return response()->json([
                'message' => 'Already a member of this group'
            ], 409);
        }

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => $request->user()->id,
            'role' => 'member',
        ]);

        return response()->json([
            'message' => 'Joined group successfully',
            'data' => $group->load('members')
        ], 200);
    }

    /**
     * Leave group
     */
    public function leave(Request $request, Group $group)
    {
        if (!$group->isMember($request->user())) {
            return response()->json([
                'message' => 'Not a member of this group'
            ], 404);
        }

        if ($group->creator_id === $request->user()->id) {
            return response()->json([
                'message' => 'Group creator cannot leave'
            ], 403);
        }

        GroupMember::where('group_id', $group->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json([
            'message' => 'Left group successfully'
        ]);
    }

    /**
     * Get group members
     */
    public function members(Group $group, Request $request)
    {
        if (!$group->isMember($request->user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($group->members);
    }
}