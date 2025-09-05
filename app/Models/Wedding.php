<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Wedding extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'event_date',
        'location',
        'description',
        'pair_signature',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wedding_user')
            ->withPivot(['wedding_role_id', 'status'])
            ->withTimestamps();
    }
}
