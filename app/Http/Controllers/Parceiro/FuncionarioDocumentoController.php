<?php

namespace App\Http\Controllers\Parceiro;

use App\Enums\StatusDocumento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Parceiro\UploadDocumentoFuncionarioRequest;
use App\Models\DocumentoFuncionario;
use App\Models\Funcionario;
use App\Services\Documentos\GeradorSlots;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FuncionarioDocumentoController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function index(Funcionario $funcionario): View
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null || $funcionario->parceiro_id !== $partner->id, 404);

        $this->geradorSlots->garantirSlotsFuncionario($funcionario);

        $documentos = $funcionario->documentos()
            ->doTipoAtivo()
            ->with('tipo')
            ->orderBy('id')
            ->get();

        return view('parceiro.funcionarios.documentos.index', compact('partner', 'funcionario', 'documentos'));
    }

    public function upload(
        UploadDocumentoFuncionarioRequest $request,
        Funcionario $funcionario,
        DocumentoFuncionario $documento_funcionario,
    ): RedirectResponse {
        $partner = auth()->user()->currentPartner();
        abort_if(
            $partner === null
            || $funcionario->parceiro_id !== $partner->id
            || $documento_funcionario->funcionario_id !== $funcionario->id,
            404
        );

        $disk = 'local';
        $file = $request->file('arquivo');
        $ext = strtoupper($file->getClientOriginalExtension() ?: 'bin');
        $dir = "parceiros/{$partner->slug}/funcionarios/{$funcionario->slug}";

        if ($documento_funcionario->arquivo_caminho) {
            Storage::disk($documento_funcionario->arquivo_disco ?? $disk)->delete($documento_funcionario->arquivo_caminho);
        }

        $documento_funcionario->load('tipo');
        $tipoNome = $documento_funcionario->tipo?->nome ?? 'DOCUMENTO';
        $empresaNome = $partner->razao_social;
        $funcionarioNome = $funcionario->nome;
        $data = now()->format('Ymd');

        $nome = $this->gerarNomeArquivo(
            [$tipoNome, $empresaNome, $funcionarioNome, $data],
            $ext,
            $disk,
            $dir
        );

        $storedPath = $file->storeAs($dir, $nome, $disk);

        $documento_funcionario->update([
            'arquivo_disco' => $disk,
            'arquivo_caminho' => $storedPath,
            'arquivo_mime' => $file->getClientMimeType(),
            'validade' => $request->date('validade'),
            'status' => StatusDocumento::Pendente,
            'validado_por_id' => null,
            'validado_em' => null,
            'observacoes' => null,
        ]);

        return redirect()
            ->route('parceiro.funcionarios.documentos.index', $funcionario)
            ->with('status', 'Documento enviado e aguardando validação.');
    }

    public function download(Funcionario $funcionario, DocumentoFuncionario $documento_funcionario): StreamedResponse
    {
        $partner = auth()->user()->currentPartner();
        abort_if(
            $partner === null
            || $funcionario->parceiro_id !== $partner->id
            || $documento_funcionario->funcionario_id !== $funcionario->id,
            404
        );

        $this->authorize('download', $documento_funcionario);

        abort_if($documento_funcionario->arquivo_caminho === null || $documento_funcionario->arquivo_disco === null, 404);

        $disk = Storage::disk($documento_funcionario->arquivo_disco);
        $mime = $documento_funcionario->arquivo_mime ?: ($disk->mimeType($documento_funcionario->arquivo_caminho) ?: 'application/octet-stream');
        $filename = basename($documento_funcionario->arquivo_caminho);

        return response()->stream(
            fn () => fpassthru($disk->readStream($documento_funcionario->arquivo_caminho)),
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    }

    private function gerarNomeArquivo(array $partes, string $ext, string $disco, string $dir): string
    {
        $base = implode('-', array_map(
            fn ($p) => strtoupper((string) preg_replace('/[^A-Z0-9]+/i', '-', $p)),
            $partes
        ));
        $base = trim($base, '-');
        $nome = "{$base}.{$ext}";
        $i = 2;

        while (Storage::disk($disco)->exists("{$dir}/{$nome}")) {
            $nome = "{$base}-{$i}.{$ext}";
            $i++;
        }

        return $nome;
    }
}
