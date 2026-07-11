<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $table = 'table_orders';

    protected $fillable = [
        'user_id',
        'folio',
        'status',
        'subtotal',
        'total',
        'stripe_session_id',
        'stripe_payment_intent',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /**
     * Genera automáticamente el folio antes de crear la orden.
     */
    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (blank($order->folio)) {
                do {
                    $folio = 'ORD-'
                        . now()->format('Ymd')
                        . '-'
                        . Str::upper(Str::random(6));
                } while (static::where('folio', $folio)->exists());

                $order->folio = $folio;
            }
        });
    }

    /**
     * Una orden pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Una orden contiene muchos detalles.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Una orden tiene un pago.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
}
