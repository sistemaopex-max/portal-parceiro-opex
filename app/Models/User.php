<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_PARTNER = 'partner';

    public const ROLE_INTERNAL = 'internal';

    public const ROLE_ADMIN = 'admin';

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class);
    }

    public function currentPartner(): ?Partner
    {
        $id = session('current_partner_id');

        $partner = $this->partners()
            ->ativos()
            ->when($id, fn ($q) => $q->where('id', $id))
            ->first();

        return $partner ?? $this->partners()->ativos()->orderBy('razao_social')->first();
    }

    public function isPartner(): bool
    {
        return $this->role === self::ROLE_PARTNER;
    }

    public function isInternal(): bool
    {
        return $this->role === self::ROLE_INTERNAL;
    }

    public function isBackOffice(): bool
    {
        return $this->isAdmin() || $this->isInternal();
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function dashboardUrl(): string
    {
        if ($this->isPartner()) {
            return route('parceiro.dashboard', absolute: false);
        }

        return route('admin.dashboard', absolute: false);
    }

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
}
