<?php

namespace App\Http\Controllers\Parceiro;

use App\Enums\StatusDocumento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Parceiro\UploadDocumentoEmpresaRequest;
use App\Models\DocumentoEmpresa;
use App\Services\Documentos\GeradorSlots;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmpresaDocumentoController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function index(): View
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null, 404);

        $this->geradorSlots->garantirSlotsEmpresa($partner);

        $documentos = $partner->documentosEmpresa()
            ->doTipoAtivo()
            ->with('tipo')
            ->orderBy('id')
            ->get();

        return view('parceiro.empresa.documentos.index', compact('partner', 'documentos'));
    }

    public function upload(UploadDocumentoEmpresaRequest $request, DocumentoEmpresa $documento_empresa): RedirectResponse
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null || $documento_empresa->parceiro_id !== $partner->id, 404);

        $disk = 'local';
        $file = $request->file('arquivo');
        $ext = strtoupper($file->getClientOriginalExtension() ?: 'bin');
        $dir = "parceiros/{$partner->slug}/empresa";

        if ($documento_empresa->arquivo_caminho) {
            Storage::disk($documento_empresa->arquivo_disco ?? $disk)->delete($documento_empresa->arquivo_caminho);
        }

        $documento_empresa->load('tipo');
        $tipoNome = $documento_empresa->tipo?->nome ?? 'DOCUMENTO';
        $empresaNome = $partner->razao_social;
        $data = now()->format('Ymd');

        $nome = $this->gerarNomeArquivo(
            [$tipoNome, $empresaNome, $data],
            $ext,
            $disk,
            $dir
        );

        $storedPath = $file->storeAs($dir, $nome, $disk);

        $documento_empresa->update([
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
            ->route('parceiro.empresa.documentos.index')
            ->with('status', 'Documento enviado e aguardando validação.');
    }

    public function download(DocumentoEmpresa $documento_empresa): StreamedResponse
    {
        $this->authorize('download', $documento_empresa);

        abort_if($documento_empresa->arquivo_caminho === null || $documento_empresa->arquivo_disco === null, 404);

        $disk = Storage::disk($documento_empresa->arquivo_disco);
        $mime = $documento_empresa->arquivo_mime ?: ($disk->mimeType($documento_empresa->arquivo_caminho) ?: 'application/octet-stream');
        $filename = basename($documento_empresa->arquivo_caminho);

        return response()->stream(
            fn () => fpassthru($disk->readStream($documento_empresa->arquivo_caminho)),
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
