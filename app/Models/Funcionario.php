<?php

namespace App\Models;

use App\Enums\StatusDocumento;
use Database\Factories\FuncionarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'slug',
        'cpf',
        'data_nascimento',
        'documentacao_em_dia',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function (Funcionario $f) {
            if (empty($f->slug)) {
                $f->slug = static::gerarSlugUnico($f);
            }
        });
    }

    public static function gerarSlugUnico(Funcionario $f): string
    {
        $words = preg_split('/\s+/', trim($f->nome ?? 'funcionario'));
        $abrev = count($words) > 1
            ? $words[0] . ' ' . $words[count($words) - 1]
            : $words[0];
        $base = Str::slug($abrev);
        $slug = $base;
        $i = 2;

        while (static::query()->where('slug', $slug)->where('id', '!=', $f->id ?? 0)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
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
