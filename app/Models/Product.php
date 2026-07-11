<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    /**
     * Nombre personalizado de la tabla.
     */
    protected $table = 'table_products';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'slug',
        'stock',
        'is_active',
    ];

    /**
     * Conversión automática de tipos.
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Accessor y mutator para el nombre.
     *
     * Al guardar elimina espacios.
     * Al consultar coloca iniciales mayúsculas.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
            set: fn (string $value) => trim($value),
        );
    }

    /**
     * Mutator para normalizar el slug.
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::slug($value),
        );
    }

    /**
     * Relación muchos a muchos:
     * un producto puede pertenecer a muchas categorías.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'table_category_product',
            'product_id',
            'category_id'
        )->withTimestamps();
    }

    /**
     * Un producto puede aparecer en muchos elementos del carrito.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(
            CartItem::class,
            'product_id'
        );
    }

    /**
     * Un producto puede aparecer en muchos detalles de órdenes.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'product_id'
        );
    }
}
