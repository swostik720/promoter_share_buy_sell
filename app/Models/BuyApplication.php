<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_application_id',
        'buyer_name',
        'buyer_type',
        'buyer_category',
        'share_quantity_to_buy',
        'offered_price_per_share',
        'application_date',
        'status',
        'citizenship_number',
        'pan_number',
        'demat_account',
        'contact_details'
    ];

    protected $casts = [
        'application_date' => 'date',
        'offered_price_per_share' => 'decimal:2',
        'contact_details' => 'array'
    ];

    public function sellApplication()
    {
        return $this->belongsTo(SellApplication::class);
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
            'buy_application',
            'buyer_citizenship',
            'buyer_cia_report',
            'buyer_tax_clearance',
            'buyer_income_source',
            'combine_application',
            'police_report',
            'self_declaration'
        ];

        if ($this->buyer_type === 'institutional') {
            $required[] = 'buyer_moa_aoa';
            $required[] = 'buyer_decision_minute';
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
