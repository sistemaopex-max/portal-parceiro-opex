<?php

namespace App\Models;

use Database\Factories\FuncaoFuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'ativo',
    ];

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
