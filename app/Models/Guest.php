<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Guest extends Model
{
    protected $fillable = [
        'wedding_id',
        'name',
        'last_name',
        'email',
        'phone',
        'side',
        'group',
        'table_name',
        'seats',
        'invitation_token',
        'invitation_sent_at',
        'rsvp_status',
        'dietary',
        'notes',
        'responded_at',
    ];

    protected $casts = [
        'invitation_sent_at' => 'datetime',
        'responded_at' => 'datetime',
        'seats' => 'integer',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Guest $guest) {
            if (empty($guest->invitation_token)) {
                $guest->invitation_token = Str::random(40);
            }
        });
    }
}
