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
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FuncionarioDocumentoController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function index(Funcionario $funcionario): View
    {
        $partner = auth()->user()->partner;
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
        $partner = auth()->user()->partner;
        abort_if(
            $partner === null
            || $funcionario->parceiro_id !== $partner->id
            || $documento_funcionario->funcionario_id !== $funcionario->id,
            404
        );

        $disk = 'local';
        $file = $request->file('arquivo');
        $ext = $file->getClientOriginalExtension() ?: 'bin';
        $basename = (string) Str::uuid();
        $relativeDir = "parceiros/{$partner->id}/funcionarios/{$funcionario->id}/documentos/{$documento_funcionario->id}";

        if ($documento_funcionario->arquivo_caminho) {
            Storage::disk($documento_funcionario->arquivo_disco ?? $disk)->delete($documento_funcionario->arquivo_caminho);
        }

        $storedPath = $file->storeAs($relativeDir, "{$basename}.{$ext}", $disk);

        $documento_funcionario->update([
            'arquivo_disco' => $disk,
            'arquivo_caminho' => $storedPath,
            'arquivo_nome_original' => $file->getClientOriginalName(),
            'arquivo_mime' => $file->getClientMimeType(),
            'arquivo_tamanho' => $file->getSize(),
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
        $partner = auth()->user()->partner;
        abort_if(
            $partner === null
            || $funcionario->parceiro_id !== $partner->id
            || $documento_funcionario->funcionario_id !== $funcionario->id,
            404
        );

        $this->authorize('download', $documento_funcionario);

        abort_if($documento_funcionario->arquivo_caminho === null || $documento_funcionario->arquivo_disco === null, 404);

        return Storage::disk($documento_funcionario->arquivo_disco)->download(
            $documento_funcionario->arquivo_caminho,
            $documento_funcionario->arquivo_nome_original ?? 'documento'
        );
    }
}
