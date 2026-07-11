<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    /**
     * Nombre personalizado de la tabla.
     */
    protected $table = 'table_categories';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Accessor y mutator para el nombre.
     *
     * Al guardar, elimina espacios al principio y al final.
     * Al consultar, coloca las palabras con inicial mayúscula.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
            set: fn (string $value) => trim($value),
        );
    }

    /**
     * Mutator para el slug.
     *
     * Ejemplo:
     * "Equipos de Computación" se guarda como
     * "equipos-de-computacion".
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::slug($value),
        );
    }

    /**
     * Relación muchos a muchos:
     * una categoría puede tener muchos productos.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'table_category_product',
            'category_id',
            'product_id'
        )->withTimestamps();
    }
}
