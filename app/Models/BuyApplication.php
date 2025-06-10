<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyApplication extends Model
{
    use HasFactory;

    protected $table = 'buy_applications';
    protected $primaryKey = 'buy_application_id';

    protected $fillable = [
        'sell_application_id',
        'buyer_id',
        'application_date',
        'requested_share_quantity',
        'application_status',
        'is_combine_application'
    ];

    protected $casts = [
        'application_date' => 'date',
        'requested_share_quantity' => 'decimal:2',
        'is_combine_application' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function sellApplication()
    {
        return $this->belongsTo(ShareSellApplication::class, 'sell_application_id', 'application_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'buyer_id');
    }

    public function documents()
    {
        return $this->hasMany(BuyApplicationDocument::class, 'buy_application_id', 'buy_application_id');
    }

    public function transactions()
    {
        return $this->hasMany(ShareTransaction::class, 'buy_application_id', 'buy_application_id');
    }
}
