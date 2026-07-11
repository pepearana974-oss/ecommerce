<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'table_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    /*
     * Relación muchos a muchos:
     * una categoría puede tener muchos productos.
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'table_category_product',
            'category_id',
            'product_id'
        )->withTimestamps();
    }
}
