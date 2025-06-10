<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareTransaction extends Model
{
    use HasFactory;

    protected $table = 'share_transactions';
    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'sell_application_id',
        'buy_application_id',
        'seller_id',
        'buyer_id',
        'transaction_date',
        'share_quantity',
        'price_per_share',
        'total_amount',
        'transaction_status',
        'settlement_date'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'settlement_date' => 'date',
        'share_quantity' => 'decimal:2',
        'price_per_share' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function sellApplication()
    {
        return $this->belongsTo(ShareSellApplication::class, 'sell_application_id', 'application_id');
    }

    public function buyApplication()
    {
        return $this->belongsTo(BuyApplication::class, 'buy_application_id', 'buy_application_id');
    }

    public function seller()
    {
        return $this->belongsTo(PromoterShareholder::class, 'seller_id', 'shareholder_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'buyer_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('transaction_status', 'Completed');
    }

    public function scopePending($query)
    {
        return $query->where('transaction_status', 'Pending');
    }
}
