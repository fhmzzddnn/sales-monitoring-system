<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'sale_id',
        'amount_paid',
        'payment_status',
    ];

    protected $casts = [
        'amount_paid' => 'float',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
