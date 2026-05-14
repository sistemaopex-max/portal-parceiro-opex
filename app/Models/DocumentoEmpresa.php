<?php

namespace App\Models;

use App\Enums\StatusDocumento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoEmpresa extends Model
{
    protected $table = 'documentos_empresa';

    public const CREATED_AT = 'criado_em';

    public const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'parceiro_id',
        'tipo_documento_empresa_id',
        'arquivo_disco',
        'arquivo_caminho',
        'arquivo_nome_original',
        'arquivo_mime',
        'arquivo_tamanho',
        'validade',
        'status',
        'validado_por_id',
        'validado_em',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusDocumento::class,
            'validade' => 'date',
            'validado_em' => 'datetime',
        ];
    }

    public function parceiro(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'parceiro_id');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoEmpresa::class, 'tipo_documento_empresa_id');
    }

    public function validadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validado_por_id');
    }

    public function marcarValido(User $admin, \Carbon\CarbonInterface $validade): void
    {
        $this->forceFill([
            'status' => StatusDocumento::Valido,
            'validade' => $validade,
            'validado_por_id' => $admin->id,
            'validado_em' => now(),
            'observacoes' => null,
        ])->save();
    }

    public function marcarInvalido(User $admin, string $observacoes): void
    {
        $this->forceFill([
            'status' => StatusDocumento::Invalido,
            'validado_por_id' => $admin->id,
            'validado_em' => now(),
            'observacoes' => $observacoes,
        ])->save();
    }
}
