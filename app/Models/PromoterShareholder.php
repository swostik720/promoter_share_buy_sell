<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoterShareholder extends Model
{
    use HasFactory;

    protected $table = 'promoter_shareholders';
    protected $primaryKey = 'shareholder_id';

    protected $fillable = [
        'shareholder_name',
        'shareholder_type',
        'share_quantity',
        'demat_account_number',
        'contact_email',
        'contact_phone',
        'address',
        'status'
    ];

    protected $casts = [
        'share_quantity' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function shareholderSellApplications()
    {
        return $this->hasMany(ShareSellApplication::class, 'seller_id', 'shareholder_id');
    }

    public function transactions()
    {
        return $this->hasMany(ShareTransaction::class, 'seller_id', 'shareholder_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeIndividual($query)
    {
        return $query->where('shareholder_type', 'Individual');
    }

    public function scopeInstitutional($query)
    {
        return $query->where('shareholder_type', 'Institutional');
    }
}
