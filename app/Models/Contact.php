<?php

// app/Models/Contact.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'title',
        'phone',
        'mobile',
        'fax',
        'email',
        'building',
        'department',
        'source'
    ];

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->name);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'ilike', "%{$search}%")
              ->orWhere('first_name', 'ilike', "%{$search}%")
              ->orWhere('phone', 'ilike', "%{$search}%")
              ->orWhere('mobile', 'ilike', "%{$search}%")
              ->orWhere('fax', 'ilike', "%{$search}%")
              ->orWhere('email', 'ilike', "%{$search}%");
        });
    }

    public function scopeFilterByBuilding($query, $building)
    {
        if ($building) {
            return $query->where('building', $building);
        }
        return $query;
    }

    public function scopeFilterByDepartment($query, $department)
    {
        if ($department) {
            return $query->where('department', $department);
        }
        return $query;
    }
}
