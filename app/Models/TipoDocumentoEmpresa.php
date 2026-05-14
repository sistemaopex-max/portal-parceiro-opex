<?php

namespace App\Models;

use Database\Factories\TipoDocumentoEmpresaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoDocumentoEmpresa extends Model
{
    /** @use HasFactory<TipoDocumentoEmpresaFactory> */
    use HasFactory;
    protected $table = 'tipos_documento_empresa';

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

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(
            PartnerCategory::class,
            'categoria_tipos_documento_empresa',
            'tipo_documento_empresa_id',
            'partner_category_id'
        );
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<TipoDocumentoEmpresa>  $query
     * @return \Illuminate\Database\Eloquent\Builder<TipoDocumentoEmpresa>
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
