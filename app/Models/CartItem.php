<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'table_cart_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'cart_id' => 'integer',
            'product_id' => 'integer',
            'quantity' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    protected $appends = [
        'total',
    ];

    /*
     * El elemento pertenece a un carrito.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    /*
     * El elemento pertenece a un producto.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /*
     * Calcula el total del producto:
     * precio por cantidad.
     */
    public function getTotalAttribute(): string
    {
        $total = (float) $this->price * (int) $this->quantity;

        return '$' . number_format($total, 2, '.', '');
    }

    /*
     * Evita que la cantidad sea menor que uno.
     */
    public function setQuantityAttribute($value): void
    {
        $this->attributes['quantity'] = max(1, (int) $value);
    }
}
