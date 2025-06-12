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
        'status',
        'verified_by',
        'verified_at',
        'remarks'
    ];

    protected $casts = [
        'upload_date' => 'datetime',
        'verified_at' => 'datetime',
        'file_size' => 'integer'
    ];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
