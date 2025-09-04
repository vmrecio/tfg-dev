<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingRole extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];
}

