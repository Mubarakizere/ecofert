<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role_type',
        'email',
        'avatar',
        'password',
    ];

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
            ->take(2)
            ->join('');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the waste logs for this user.
     */
    public function wasteLogs(): HasMany
    {
        return $this->hasMany(WasteLog::class);
    }

    /**
     * Get the experiments for this user.
     */
    public function experiments(): HasMany
    {
        return $this->hasMany(Experiment::class, 'user_id', 'id');
    }

    /**
     * Get the user's waste stock balances.
     */
    public function wasteStocks(): HasMany
    {
        return $this->hasMany(UserWasteStock::class);
    }

    /**
     * Get the user's AI chat messages.
     */
    public function aiChatMessages(): HasMany
    {
        return $this->hasMany(AiChatMessage::class);
    }

    /**
     * Get the user's fertilizer batches.
     */
    public function fertilizerBatches(): HasMany
    {
        return $this->hasMany(FertilizerBatch::class);
    }

    /**
     * Check if the user is an Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role_type === 'Admin';
    }

    /**
     * Check if the user is an Extension Officer.
     */
    public function isExtensionOfficer(): bool
    {
        return $this->role_type === 'Extension Officer';
    }

    /**
     * Check if the user is a Household user.
     */
    public function isHousehold(): bool
    {
        return $this->role_type === 'Household';
    }

    /**
     * Check if the user has admin panel access (Admin or Extension Officer).
     */
    public function hasAdminAccess(): bool
    {
        return in_array($this->role_type, ['Admin', 'Extension Officer']);
    }
}

