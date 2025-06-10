<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardDecision extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_application_id',
        'decision_date',
        'decision',
        'decision_remarks',
        'board_members_present',
        'meeting_minute_reference'
    ];

    protected $casts = [
        'decision_date' => 'date',
        'board_members_present' => 'array'
    ];

    public function sellApplication()
    {
        return $this->belongsTo(SellApplication::class);
    }
}
