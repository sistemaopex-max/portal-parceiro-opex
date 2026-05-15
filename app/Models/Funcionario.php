<?php

namespace App\Models;

use App\Enums\StatusDocumento;
use Database\Factories\FuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Funcionario extends Model
{
    /** @use HasFactory<FuncionarioFactory> */
    use HasFactory;
    protected $table = 'funcionarios';

    public const CREATED_AT = 'criado_em';

    public const UPDATED_AT = 'modificado_em';

    protected $fillable = [
        'parceiro_id',
        'funcao_funcionario_id',
        'nome',
        'cpf',
        'documentacao_em_dia',
    ];

    protected function casts(): array
    {
        return [
            'documentacao_em_dia' => 'boolean',
        ];
    }

    public function parceiro(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'parceiro_id');
    }

    public function funcao(): BelongsTo
    {
        return $this->belongsTo(FuncaoFuncionario::class, 'funcao_funcionario_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoFuncionario::class, 'funcionario_id');
    }

    public static function normalizarCpf(string $cpf): string
    {
        return preg_replace('/\D/', '', $cpf) ?? '';
    }

    public function refreshDocumentacaoEmDia(): void
    {
        $tipoIds = $this->funcao
            ->tiposDocumentoFuncionario()
            ->where('ativo', true)
            ->pluck('id');

        if ($tipoIds->isEmpty()) {
            $this->forceFill(['documentacao_em_dia' => true])->saveQuietly();

            return;
        }

        $validos = $this->documentos()
            ->whereIn('tipo_documento_funcionario_id', $tipoIds)
            ->where('status', StatusDocumento::Valido)
            ->count();

        $this->forceFill([
            'documentacao_em_dia' => $validos === $tipoIds->count(),
        ])->saveQuietly();
    }
}
