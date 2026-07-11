<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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

    /*
     * Un pago pertenece a una orden.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
