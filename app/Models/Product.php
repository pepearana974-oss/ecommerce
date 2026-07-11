<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'table_products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'slug',
        'stock',
        'is_active',
    ];

    /*
     * Relación muchos a muchos:
     * un producto puede pertenecer a muchas categorías.
     */
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'table_category_product',
            'product_id',
            'category_id'
        )->withTimestamps();
    }
}
