<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vendor extends Model
{
    protected $fillable = [
        'user_id',
        'vendor_specialty_id',
        'company_name',
        'phone',
        'email',
        'website',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function weddings(): BelongsToMany
    {
        return $this->belongsToMany(Wedding::class, 'wedding_vendor')
            ->withPivot(['status', 'contract_amount', 'notes'])
            ->withTimestamps();
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(VendorSpecialty::class, 'vendor_specialty_id');
    }
}
