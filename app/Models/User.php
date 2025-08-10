<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method bool consumeCredits(int $amount = 1)
 * @method UserCredit getCredits()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany payments()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Relación con créditos del usuario
     */
    public function userCredit(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserCredit::class);
    }

    /**
     * Relación con pagos del usuario
     */
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Obtiene los créditos del usuario
     */
    public function getCredits(): UserCredit
    {
        return UserCredit::getOrCreateForUser($this->id);
    }

    /**
     * Verifica si el usuario tiene créditos suficientes
     */
    public function hasCredits(int $amount = 1): bool
    {
        return $this->getCredits()->hasCredits($amount);
    }

    /**
     * Consume créditos del usuario
     */
    public function consumeCredits(int $amount = 1): bool
    {
        return $this->getCredits()->consumeCredits($amount);
    }
}
