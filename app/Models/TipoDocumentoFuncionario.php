<?php

namespace App\Models;

use Database\Factories\TipoDocumentoFuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumentoFuncionario extends Model
{
    /** @use HasFactory<TipoDocumentoFuncionarioFactory> */
    use HasFactory;

    protected $table = 'tipos_documento_funcionario';

    protected $fillable = [
        'funcao_funcionario_id',
        'nome',
        'ativo',
    ];

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
