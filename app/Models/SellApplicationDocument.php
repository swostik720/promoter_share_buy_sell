<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellApplicationDocument extends Model
{
    use HasFactory;

    protected $table = 'sell_application_documents';
    protected $primaryKey = 'document_id';

    protected $fillable = [
        'application_id',
        'document_type',
        'document_name',
        'document_path',
        'uploaded_by'
    ];

    // Relationships
    public function sellApplication()
    {
        return $this->belongsTo(ShareSellApplication::class, 'application_id', 'application_id');
    }
}
