<?php

namespace App\Models;

use Database\Factories\PartnerCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function tiposDocumentoExigidos(): BelongsToMany
    {
        return $this->belongsToMany(
            TipoDocumentoEmpresa::class,
            'categoria_tipos_documento_empresa',
            'partner_category_id',
            'tipo_documento_empresa_id'
        );
    }
}
