<?php

namespace App\Services;

use App\Models\PartnerCategory;
use Illuminate\Support\Str;

class CategoriaParceiroService
{
    public function criar(array $dados): PartnerCategory
    {
        return PartnerCategory::create([
            'nome' => $dados['nome'],
            'slug' => $this->gerarSlugUnico($dados['nome']),
            'descricao' => $dados['descricao'] ?? null,
            'ativo' => isset($dados['ativo']) ? (bool) $dados['ativo'] : true,
        ]);
    }

    public function atualizar(PartnerCategory $categoria, array $dados): void
    {
        $categoria->update([
            'nome' => $dados['nome'],
            'slug' => $this->gerarSlugUnico($dados['nome'], $categoria->id),
            'descricao' => $dados['descricao'] ?? $categoria->descricao,
        ]);
        $categoria->refresh();
    }

    public function gerarSlugUnico(?string $nome, ?int $ignoreId = null): string
    {
        $base = Str::slug($nome ?? '');

        if ($base === '') {
            $base = 'categoria';
        }

        $candidate = $base;
        $suffix = 1;

        while (
            PartnerCategory::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $suffix++;
            $candidate = $base . '-' . $suffix;
        }

        return $candidate;
    }
}
