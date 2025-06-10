<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'document_type',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'upload_date',
        'is_verified',
        'remarks'
    ];

    protected $casts = [
        'upload_date' => 'date',
        'is_verified' => 'boolean'
    ];

    public function documentable()
    {
        return $this->morphTo();
    }
}
