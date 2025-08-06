<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class refunds extends Model
{
    use HasFactory;

    protected $primaryKey = 'refund_id';
    
    protected $fillable = [
        'transaction_id',
        'amount',
        'reason',
        'processed_by',
        'status',
        'reference_number'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(transactions::class, 'transaction_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}