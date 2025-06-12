<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticePublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_application_id',
        'publication_date',
        'newspaper_name',
        'notice_content',
        'notice_reference'
    ];

    protected $casts = [
        'publication_date' => 'date'
    ];

    public function sellApplication()
    {
        return $this->belongsTo(SellApplication::class);
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

        if ($this->seller_type === 'institutional') {
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
