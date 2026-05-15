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

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class, 'categoria_id');
    }

    public function tiposDocumentoEmpresa(): HasMany
    {
        return $this->hasMany(TipoDocumentoEmpresa::class, 'partner_category_id');
    }

    public function tiposDocumentoEmpresaAtivos(): HasMany
    {
        return $this->tiposDocumentoEmpresa()->ativos();
    }

    public function funcoesFuncionario(): HasMany
    {
        return $this->hasMany(FuncaoFuncionario::class, 'partner_category_id');
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
