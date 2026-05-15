<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerInvitation extends Model
{
    protected $table = 'convites';

    public const CREATED_AT = 'criado_em';

    public const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'criado_por',
        'categoria_id',
        'email',
        'token',
        'expira_em',
        'usado_em',
    ];

    protected function casts(): array
    {
        return [
            'expira_em' => 'datetime',
            'usado_em' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PartnerCategory::class, 'categoria_id');
    }

    public function isExpired(): bool
    {
        return $this->expira_em->isPast();
    }

    public function isUsed(): bool
    {
        return $this->usado_em !== null;
    }

    public function isValid(): bool
    {
        return ! $this->isUsed() && ! $this->isExpired();
    }
}
