<?php

namespace App\Models;

use Database\Factories\TipoDocumentoFuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoDocumentoFuncionario extends Model
{
    /** @use HasFactory<TipoDocumentoFuncionarioFactory> */
    use HasFactory;
    protected $table = 'tipos_documento_funcionario';

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

    public function funcoes(): BelongsToMany
    {
        return $this->belongsToMany(
            FuncaoFuncionario::class,
            'funcao_tipos_documento_funcionario',
            'tipo_documento_funcionario_id',
            'funcao_funcionario_id'
        );
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
