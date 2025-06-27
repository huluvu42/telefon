<?php

// app/Models/MobileEntry.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileEntry extends Model
{
    protected $fillable = [
        'group_id',
        'phone',
        'name',
        'order_position'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(MobileGroup::class);
    }
}