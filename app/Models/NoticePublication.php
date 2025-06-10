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
}
