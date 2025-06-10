<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;

    protected $table = 'buyers';
    protected $primaryKey = 'buyer_id';

    protected $fillable = [
        'buyer_name',
        'buyer_type',
        'buyer_category',
        'demat_account_number',
        'contact_email',
        'contact_phone',
        'address'
    ];

    // Relationships
    public function buyApplications()
    {
        return $this->hasMany(BuyApplication::class, 'buyer_id', 'buyer_id');
    }

    public function transactions()
    {
        return $this->hasMany(ShareTransaction::class, 'buyer_id', 'buyer_id');
    }

    // Scopes
    public function scopeExistingPromoter($query)
    {
        return $query->where('buyer_category', 'Existing_Promoter');
    }

    public function scopePublic($query)
    {
        return $query->where('buyer_category', 'Public');
    }
}
