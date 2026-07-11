<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

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

    /**
     * Calcula el total automáticamente antes de guardar.
     */
    protected static function booted(): void
    {
        static::saving(function (OrderItem $item): void {
            $item->total = round(
                (float) $item->price * (int) $item->quantity,
                2
            );
        });
    }

    /**
     * Un detalle pertenece a una orden.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Un detalle pertenece a un producto.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
