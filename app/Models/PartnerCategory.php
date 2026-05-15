<?php

namespace App\Models;

use Database\Factories\PartnerCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerCategory extends Model
{
    /** @use HasFactory<PartnerCategoryFactory> */
    use HasFactory;

    protected $table = 'categorias_parceiro';

    public const CREATED_AT = 'criado_em';

    public const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class, 'categoria_id');
    }

    public function tiposDocumentoEmpresa(): HasMany
    {
        return $this->hasMany(TipoDocumentoEmpresa::class, 'categoria_id');
    }

    public function tiposDocumentoEmpresaAtivos(): HasMany
    {
        return $this->tiposDocumentoEmpresa()->ativos();
    }

    public function funcoesFuncionario(): HasMany
    {
        return $this->hasMany(FuncaoFuncionario::class, 'categoria_id');
    }

    public function funcoesFuncionarioAtivas(): HasMany
    {
        return $this->funcoesFuncionario()->ativos();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
