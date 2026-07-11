<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'table_carts';

    protected $fillable = [
        'user_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
        ];
    }

    protected $appends = [
        'subtotal',
        'items_count',
    ];

    /*
     * Un carrito pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
     * Un carrito puede tener muchos productos agregados.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    /*
     * Calcula el subtotal del carrito.
     */
    public function getSubtotalAttribute(): string
    {
        $subtotal = (float) $this->items()
            ->sum(DB::raw('quantity * price'));

        return '$' . number_format($subtotal, 2, '.', '');
    }

    /*
     * Calcula la cantidad total de productos.
     */
    public function getItemsCountAttribute(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    /*
     * Permite consultar solamente carritos activos.
     */
    public function scopeIsActive($query)
    {
        return $query->where('status', 'active');
    }
}
