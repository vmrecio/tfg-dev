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
        'start_at',
        'end_at',
        'location',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
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
