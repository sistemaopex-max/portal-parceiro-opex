<?php

namespace App\Models;

use Database\Factories\FuncaoFuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuncaoFuncionario extends Model
{
    /** @use HasFactory<FuncaoFuncionarioFactory> */
    use HasFactory;
    protected $table = 'funcoes_funcionario';

    protected $fillable = [
        'nome',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function tiposDocumentoExigidos(): BelongsToMany
    {
        return $this->belongsToMany(
            TipoDocumentoFuncionario::class,
            'funcao_tipos_documento_funcionario',
            'funcao_funcionario_id',
            'tipo_documento_funcionario_id'
        );
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
