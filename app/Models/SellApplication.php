<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'share_quantity_to_sell',
        'proposed_price_per_share',
        'application_date',
        'demat_account',
        'status',
        'reason'
    ];

    protected $casts = [
        'application_date' => 'date',
        'proposed_price_per_share' => 'decimal:2'
    ];

    public function seller()
    {
        return $this->belongsTo(Shareholder::class, 'seller_id');
    }

    public function buyApplications()
    {
        return $this->hasMany(BuyApplication::class);
    }

    public function boardDecision()
    {
        return $this->hasOne(BoardDecision::class);
    }

    public function noticePublication()
    {
        return $this->hasOne(NoticePublication::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function getRequiredDocuments()
    {
        $required = [
            'sell_application',
            'seller_citizenship', 
            'seller_tax_clearance',
            'seller_cia_report'
        ];

        if ($this->seller && $this->seller->type === 'institutional') {
            $required[] = 'seller_moa_aoa';
            $required[] = 'seller_decision_minute';
        }

        return $required;
    }

    public function getUploadedDocuments()
    {
        return $this->documents->pluck('document_type')->toArray();
    }

    public function getMissingDocuments()
    {
        return array_diff($this->getRequiredDocuments(), $this->getUploadedDocuments());
    }

    public function hasAllRequiredDocuments()
    {
        return empty($this->getMissingDocuments());
    }
}
