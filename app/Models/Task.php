<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'created_by',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relationships
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class);
    }

    // Helper methods
    public function getCompletionPercentage(): int
    {
        $total = $this->checklists()->count();
        if ($total === 0) return 0;

        $completed = $this->checklists()->where('completed', true)->count();
        return (int) (($completed / $total) * 100);
    }
}