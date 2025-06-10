<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyApplicationDocument extends Model
{
    use HasFactory;

    protected $table = 'buy_application_documents';
    protected $primaryKey = 'document_id';

    protected $fillable = [
        'buy_application_id',
        'document_type',
        'document_name',
        'document_path',
        'uploaded_by'
    ];

    // Relationships
    public function buyApplication()
    {
        return $this->belongsTo(BuyApplication::class, 'buy_application_id', 'buy_application_id');
    }
}
