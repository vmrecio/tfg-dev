<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorSpecialty extends Model
{
    protected $fillable = [
        'name',
        'display_name',
    ];

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }
}

