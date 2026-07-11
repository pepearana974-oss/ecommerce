<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'table_payments';

    protected $fillable = [
        'order_id',
        'provider',
        'reference',
        'status',
        'amount',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'order_id' => 'integer',
            'amount' => 'decimal:2',
            'payload' => 'array',
        ];
    }

    /**
     * Un pago pertenece a una orden.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
