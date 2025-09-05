<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'wedding_vendor')
            ->withPivot(['status', 'contract_amount', 'notes'])
            ->withTimestamps();
    }
}
