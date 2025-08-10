<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'credits_amount',
        'price',
        'currency',
        'is_active',
        'sort_order',
        'features',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    /**
     * Relación con pagos
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope para paquetes activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar por orden de visualización
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Obtiene el precio formateado
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Obtiene el precio por crédito
     */
    public function getPricePerCreditAttribute(): float
    {
        return round($this->price / $this->credits_amount, 4);
    }

    /**
     * Obtiene los paquetes disponibles para mostrar al usuario
     */
    public static function getAvailablePackages()
    {
        return static::active()->ordered()->get();
    }
}
