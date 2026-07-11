<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Un usuario puede tener varios carritos.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    /**
     * Un usuario puede tener muchas órdenes.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Obtiene el carrito activo del usuario.
     */
    public function activeCart(): HasOne
    {
        return $this->hasOne(Cart::class, 'user_id')
            ->where('status', 'active');
    }
}
