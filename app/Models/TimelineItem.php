<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TimelineItem extends Model
{
    protected $fillable = [
        'wedding_id',
        'title',
        'description',
        'status',
        'start_at',
        'end_at',
        'due_at',
        'position',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'due_at' => 'datetime',
        'position' => 'integer',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'timeline_item_user')->withTimestamps();
    }
}

