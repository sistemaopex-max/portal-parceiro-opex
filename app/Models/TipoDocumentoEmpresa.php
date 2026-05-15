<?php

namespace App\Models;

use Database\Factories\TipoDocumentoEmpresaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumentoEmpresa extends Model
{
    /** @use HasFactory<TipoDocumentoEmpresaFactory> */
    use HasFactory;

    protected $table = 'tipos_documento_empresa';

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

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoEmpresa::class, 'tipo_documento_empresa_id');
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
