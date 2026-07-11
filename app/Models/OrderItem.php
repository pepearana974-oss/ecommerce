<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'table_order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'order_id' => 'integer',
            'product_id' => 'integer',
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /*
     * Un detalle pertenece a una orden.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /*
     * Un detalle pertenece a un producto.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
