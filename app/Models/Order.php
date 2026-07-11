<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'table_orders';

    protected $fillable = [
        'user_id',
        'folio',
        'status',
        'subtotal',
        'total',
        'stripe_session_id',
        'stripe_payment_intent',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /*
     * Una orden pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /*
 * Una orden contiene muchos detalles.
 */
public function items()
{
    return $this->hasMany(OrderItem::class, 'order_id');
}
/*
 * Una orden tiene un pago.
 */
public function payment()
{
    return $this->hasOne(Payment::class, 'order_id');
}
}
