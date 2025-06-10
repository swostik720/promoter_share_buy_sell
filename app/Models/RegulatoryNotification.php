<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegulatoryNotification extends Model
{
    use HasFactory;

    protected $table = 'regulatory_notifications';
    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'sell_application_id',
        'regulatory_body',
        'notification_type',
        'notification_date',
        'notification_status',
        'reference_number',
        'remarks'
    ];

    protected $casts = [
        'notification_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function sellApplication()
    {
        return $this->belongsTo(ShareSellApplication::class, 'sell_application_id', 'application_id');
    }
}
