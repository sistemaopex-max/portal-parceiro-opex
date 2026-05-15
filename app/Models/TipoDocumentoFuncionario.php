<?php

namespace App\Models;

use Database\Factories\TipoDocumentoFuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TipoDocumentoFuncionario extends Model
{
    /** @use HasFactory<TipoDocumentoFuncionarioFactory> */
    use HasFactory;

    protected $table = 'tipos_documento_funcionario';

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'funcao_funcionario_id',
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
        static::creating(function (TipoDocumentoFuncionario $m) {
            if (empty($m->slug)) {
                $m->slug = static::gerarSlugUnico($m->nome);
            }
        });
    }

    public static function gerarSlugUnico(string $nome, ?int $ignoreId = null): string
    {
        $base = Str::slug($nome) ?: 'tipo';
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

    public function funcao(): BelongsTo
    {
        return $this->belongsTo(FuncaoFuncionario::class, 'funcao_funcionario_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoFuncionario::class, 'tipo_documento_funcionario_id');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<TipoDocumentoFuncionario>  $query
     * @return \Illuminate\Database\Eloquent\Builder<TipoDocumentoFuncionario>
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
