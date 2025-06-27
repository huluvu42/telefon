<?php

// app/Models/Upload.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Upload extends Model
{
    protected $fillable = [
        'filename',
        'original_filename',
        'type',
        'path',
        'records_processed',
        'processing_log',
        'uploaded_by'
    ];

    protected $casts = [
        'processing_log' => 'array'
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}