<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

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
     * Genera automáticamente el slug cuando está vacío.
     */
    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            if (blank($category->slug) && filled($category->name)) {
                /*
                 * El mutator slug() convierte el nombre:
                 * "Ropa de Verano" en "ropa-de-verano".
                 */
                $category->slug = $category->name;
            }
        });
    }

    /**
     * Accessor y mutator para el nombre.
     *
     * Al guardar elimina espacios al principio y al final.
     * Al consultar coloca las iniciales en mayúscula.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => ucwords($value),
            set: fn (string $value): string => trim($value),
        );
    }

    /**
     * Convierte el slug al formato correcto.
     *
     * También permite recibir null cuando el usuario deja
     * el campo vacío. En ese caso, booted() genera el slug.
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string =>
                filled($value) ? Str::slug($value) : null,
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
