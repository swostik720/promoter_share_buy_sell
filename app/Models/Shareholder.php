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
        'gender',
        'contact_number',
        'email',
        'address',
        'boid',
        'father_name',
        'grandfather_name',
        'contact_person',
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

    public function getFullNameAttribute()
    {
        if ($this->type === 'individual' && $this->father_name && $this->grandfather_name) {
            return "{$this->name} S/O {$this->father_name} S/O {$this->grandfather_name}";
        }
        return $this->name;
    }
}
