<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_application_id',
        'buy_application_id',
        'seller_id',
        'share_quantity',
        'price_per_share',
        'total_amount',
        'transaction_date',
        'transaction_reference',
        'status',
        'regulatory_notifications',
        'sebbon_notification_date',
        'nepse_notification_date',
        'nia_notification_date'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'price_per_share' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'regulatory_notifications' => 'array',
        'sebbon_notification_date' => 'date',
        'nepse_notification_date' => 'date',
        'nia_notification_date' => 'date'
    ];

    public function sellApplication()
    {
        return $this->belongsTo(SellApplication::class);
    }

    public function buyApplication()
    {
        return $this->belongsTo(BuyApplication::class);
    }

    public function seller()
    {
        return $this->belongsTo(Shareholder::class, 'seller_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
