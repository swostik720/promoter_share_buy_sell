<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareSellApplication extends Model
{
    use HasFactory;

    protected $table = 'share_sell_applications';
    protected $primaryKey = 'application_id';

    protected $fillable = [
        'seller_id',
        'application_date',
        'share_quantity_to_sell',
        'application_status',
        'bod_decision_date',
        'bod_decision',
        'bod_remarks',
        'notice_publication_date',
        'newspaper_name'
    ];

    protected $casts = [
        'application_date' => 'date',
        'bod_decision_date' => 'date',
        'notice_publication_date' => 'date',
        'share_quantity_to_sell' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(PromoterShareholder::class, 'seller_id', 'shareholder_id');
    }

    public function documents()
    {
        return $this->hasMany(SellApplicationDocument::class, 'application_id', 'application_id');
    }

    public function buyApplications()
    {
        return $this->hasMany(BuyApplication::class, 'sell_application_id', 'application_id');
    }

    public function regulatoryNotifications()
    {
        return $this->hasMany(RegulatoryNotification::class, 'sell_application_id', 'application_id');
    }

    public function transactions()
    {
        return $this->hasMany(ShareTransaction::class, 'sell_application_id', 'application_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('application_status', 'Submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('bod_decision', 'Approved');
    }
}
