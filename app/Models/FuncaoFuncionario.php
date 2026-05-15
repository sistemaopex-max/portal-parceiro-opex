<?php

namespace App\Models;

use Database\Factories\FuncaoFuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FuncaoFuncionario extends Model
{
    /** @use HasFactory<FuncaoFuncionarioFactory> */
    use HasFactory;

    protected $table = 'funcoes_funcionario';

    public const CREATED_AT = 'criado_em';

    public const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'categoria_id',
        'nome',
        'slug',
        'ativo',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (FuncaoFuncionario $m) {
            if (empty($m->slug)) {
                $m->slug = static::gerarSlugUnico($m->nome);
            }
        });
    }

    public static function gerarSlugUnico(string $nome, ?int $ignoreId = null): string
    {
        $base = Str::slug($nome) ?: 'funcao';
        $candidate = $base;
        $i = 2;

        while (
            static::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(PartnerCategory::class, 'categoria_id');
    }

    public function tiposDocumentoFuncionario(): HasMany
    {
        return $this->hasMany(TipoDocumentoFuncionario::class, 'funcao_funcionario_id');
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class, 'funcao_funcionario_id');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<FuncaoFuncionario>  $query
     * @return \Illuminate\Database\Eloquent\Builder<FuncaoFuncionario>
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
