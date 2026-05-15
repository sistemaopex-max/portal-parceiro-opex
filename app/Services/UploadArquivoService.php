<?php

namespace App\Services;

use App\Enums\StatusDocumento;
use App\Models\DocumentoEmpresa;
use App\Models\DocumentoFuncionario;
use App\Models\Funcionario;
use App\Models\Partner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UploadArquivoService
{
    private const DISCO = 'local';

    public function uploadEmpresa(
        UploadedFile $file,
        DocumentoEmpresa $documento,
        Partner $partner,
        ?\DateTimeInterface $validade,
    ): void {
        $documento->loadMissing('tipo');

        $ext = strtoupper($file->getClientOriginalExtension() ?: 'bin');
        $dir = "parceiros/{$partner->slug}/empresa";
        $tipoNome = $documento->tipo?->nome ?? 'DOCUMENTO';

        $this->deletarAnterior($documento->arquivo_caminho, $documento->arquivo_disco);

        $nome = $this->gerarNome(
            [$tipoNome, $partner->razao_social, now()->format('Ymd')],
            $ext,
            self::DISCO,
            $dir,
        );

        $path = $file->storeAs($dir, $nome, self::DISCO);

        $documento->update([
            'arquivo_disco' => self::DISCO,
            'arquivo_caminho' => $path,
            'arquivo_mime' => $file->getClientMimeType(),
            'validade' => $validade,
            'status' => StatusDocumento::Pendente,
            'validado_por_id' => null,
            'validado_em' => null,
            'observacoes' => null,
        ]);
    }

    public function uploadFuncionario(
        UploadedFile $file,
        DocumentoFuncionario $documento,
        Partner $partner,
        Funcionario $funcionario,
        ?\DateTimeInterface $validade,
    ): void {
        $documento->loadMissing('tipo');

        $ext = strtoupper($file->getClientOriginalExtension() ?: 'bin');
        $dir = "parceiros/{$partner->slug}/funcionarios/{$funcionario->slug}";
        $tipoNome = $documento->tipo?->nome ?? 'DOCUMENTO';

        $this->deletarAnterior($documento->arquivo_caminho, $documento->arquivo_disco);

        $nome = $this->gerarNome(
            [$tipoNome, $partner->razao_social, $funcionario->nome, now()->format('Ymd')],
            $ext,
            self::DISCO,
            $dir,
        );

        $path = $file->storeAs($dir, $nome, self::DISCO);

        $documento->update([
            'arquivo_disco' => self::DISCO,
            'arquivo_caminho' => $path,
            'arquivo_mime' => $file->getClientMimeType(),
            'validade' => $validade,
            'status' => StatusDocumento::Pendente,
            'validado_por_id' => null,
            'validado_em' => null,
            'observacoes' => null,
        ]);
    }

    public function servir(string $caminho, string $disco, ?string $mimeHint = null): StreamedResponse
    {
        $disk = Storage::disk($disco);
        $mime = $mimeHint ?: ($disk->mimeType($caminho) ?: 'application/octet-stream');

        return response()->stream(
            fn () => fpassthru($disk->readStream($caminho)),
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . basename($caminho) . '"',
            ]
        );
    }

    private function deletarAnterior(?string $caminho, ?string $disco): void
    {
        if ($caminho && $disco) {
            Storage::disk($disco)->delete($caminho);
        }
    }

    private function gerarNome(array $partes, string $ext, string $disco, string $dir): string
    {
        $base = trim(
            implode('-', array_map(
                fn ($p) => strtoupper((string) preg_replace('/[^A-Z0-9]+/i', '-', $p)),
                $partes,
            )),
            '-',
        );

        $nome = "{$base}.{$ext}";
        $i = 2;

        while (Storage::disk($disco)->exists("{$dir}/{$nome}")) {
            $nome = "{$base}-{$i}.{$ext}";
            $i++;
        }

        return $nome;
    }
}
