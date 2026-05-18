<?php

namespace App\Models;

use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Partner extends Model
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory;

    protected $table = 'parceiros';

    public const CREATED_AT = 'criado_em';

    public const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'user_id',
        'categoria_id',
        'razao_social',
        'slug',
        'cnpj',
        'telefone',
        'endereco',
        'email',
        'cidade',
        'uf',
        'ativo',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function (Partner $partner) {
            if (empty($partner->slug)) {
                $partner->slug = static::gerarSlugUnico($partner);
            }
        });
    }

    public static function gerarSlugUnico(Partner $partner): string
    {
        $base = Str::slug($partner->razao_social ?? 'parceiro');
        $slug = $base;
        $i = 2;

        while (static::query()->where('slug', $slug)->where('id', '!=', $partner->id ?? 0)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PartnerCategory::class, 'categoria_id');
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function documentosEmpresa(): HasMany
    {
        return $this->hasMany(DocumentoEmpresa::class, 'parceiro_id');
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class, 'parceiro_id');
    }

    public function documentacaoEmDia(): bool
    {
        $docsOk = $this->documentosEmpresa
            ->every(fn ($d) => $d->status === \App\Enums\StatusDocumento::Valido);

        $funcionariosOk = $this->funcionarios
            ->every(fn ($f) => $f->documentacao_em_dia);

        return $docsOk && $funcionariosOk;
    }

    public static function normalizarCnpj(?string $cnpj): ?string
    {
        if ($cnpj === null || trim($cnpj) === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $cnpj);

        return $digits !== '' ? $digits : null;
    }

    public static function formatarCnpj(?string $cnpj): ?string
    {
        $digits = self::normalizarCnpj($cnpj);

        if ($digits === null || strlen($digits) !== 14) {
            return $digits;
        }

        return preg_replace(
            '/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/',
            '$1.$2.$3/$4-$5',
            $digits,
        );
    }

    public function getCnpjFormatadoAttribute(): ?string
    {
        return self::formatarCnpj($this->cnpj);
    }

    public function getLocalFilialAttribute(): ?string
    {
        $local = implode('/', array_filter([
            filled($this->cidade) ? $this->cidade : null,
            filled($this->uf) ? strtoupper($this->uf) : null,
        ]));

        return $local !== '' ? $local : null;
    }

    public function getRotuloFilialAttribute(): string
    {
        $local = $this->local_filial;

        if ($local !== null) {
            return $local.' — '.$this->razao_social;
        }

        return $this->razao_social;
    }

    protected function setCnpjAttribute(?string $value): void
    {
        $this->attributes['cnpj'] = self::normalizarCnpj($value);
    }
}
