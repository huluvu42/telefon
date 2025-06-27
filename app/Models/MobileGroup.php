<?php

// app/Models/MobileGroup.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MobileGroup extends Model
{
    protected $fillable = [
        'name',
        'sheet_name',
        'column_position',
        'order_position'
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(MobileEntry::class, 'group_id')->orderBy('order_position');
    }
}