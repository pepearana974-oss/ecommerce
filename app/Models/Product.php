<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

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
     * Atributos calculados que aparecerán al convertir
     * el producto a arreglo o JSON.
     */
    protected $appends = [
        'price_formatted',
    ];

    /**
     * Genera automáticamente el slug cuando está vacío.
     */
    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (blank($product->slug) && filled($product->name)) {
                $product->slug = $product->name;
            }
        });
    }

    /**
     * Al guardar elimina espacios.
     * Al consultar coloca iniciales mayúsculas.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => ucwords($value),
            set: fn (string $value): string => trim($value),
        );
    }

    /**
     * Normaliza el slug.
     *
     * "Laptop Gamer HP" se guarda como:
     * "laptop-gamer-hp"
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): string => Str::slug($value),
        );
    }

    /**
     * Devuelve el precio formateado.
     *
     * Ejemplo: $1,250.50
     */
    protected function priceFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (
                mixed $value,
                array $attributes
            ): string => '$' . number_format(
                (float) ($attributes['price'] ?? 0),
                2,
                '.',
                ','
            ),
        );
    }

    /**
     * Un producto puede pertenecer a muchas categorías.
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
     * Un producto puede aparecer en varios carritos.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(
            CartItem::class,
            'product_id'
        );
    }

    /**
     * Un producto puede aparecer en varias órdenes.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'product_id'
        );
    }

    /**
     * Permite consultar solamente productos activos.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
