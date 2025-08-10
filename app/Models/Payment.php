<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_package_id',
        'stripe_payment_intent_id',
        'stripe_session_id',
        'status',
        'amount',
        'currency',
        'credits_purchased',
        'stripe_metadata',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'stripe_metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Relación con usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con paquete de pago
     */
    public function paymentPackage(): BelongsTo
    {
        return $this->belongsTo(PaymentPackage::class);
    }

    /**
     * Scope para pagos exitosos
     */
    public function scopeSucceeded($query)
    {
        return $query->where('status', 'succeeded');
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Verifica si el pago fue exitoso
     */
    public function isSucceeded(): bool
    {
        return $this->status === 'succeeded';
    }

    /**
     * Verifica si el pago está pendiente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Marca el pago como exitoso
     */
    public function markAsSucceeded(): void
    {
        $this->update([
            'status' => 'succeeded',
            'paid_at' => now(),
        ]);
    }

    /**
     * Marca el pago como fallido
     */
    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }
}
