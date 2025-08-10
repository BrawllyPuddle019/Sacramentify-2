<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'available_credits',
        'used_credits',
        'total_credits',
        'last_reset_at',
    ];

    protected $casts = [
        'last_reset_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica si el usuario tiene créditos suficientes
     */
    public function hasCredits(int $amount = 1): bool
    {
        return $this->available_credits >= $amount;
    }

    /**
     * Consume créditos
     */
    public function consumeCredits(int $amount = 1): bool
    {
        if (!$this->hasCredits($amount)) {
            return false;
        }

        $this->available_credits -= $amount;
        $this->used_credits += $amount;
        $this->save();

        return true;
    }

    /**
     * Añade créditos
     */
    public function addCredits(int $amount): void
    {
        $this->available_credits += $amount;
        $this->total_credits += $amount;
        $this->save();
    }

    /**
     * Obtiene o crea el registro de créditos para un usuario
     */
    public static function getOrCreateForUser(int $userId): self
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            [
                'available_credits' => 10, // Créditos iniciales gratuitos
                'used_credits' => 0,
                'total_credits' => 10,
            ]
        );
    }
}
