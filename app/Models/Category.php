<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug,'
    ];
    /* Hacer comentarios atajo: Alt + Shif + A */
    /* Relacion de una tabla a otra
       Relacion de una tabla categories a la category_product
       */

       public function products (){
        return $this->belongsToMany(Product::class, 'category_product', 'id', 'category_id');
       }
       /* Simulacion si la tabla categories tuviera relacion uno a mucho con products */
       public function productsSimulacion (){
        return $this->hasMany(Product::class);
       }
}
