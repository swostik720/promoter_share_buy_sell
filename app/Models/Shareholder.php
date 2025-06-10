<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'category',
        'share_quantity',
        'citizenship_number',
        'pan_number',
        'demat_account',
        'contact_details',
        'is_active'
    ];

    protected $casts = [
        'contact_details' => 'array',
        'is_active' => 'boolean'
    ];

    public function sellApplications()
    {
        return $this->hasMany(SellApplication::class, 'seller_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
