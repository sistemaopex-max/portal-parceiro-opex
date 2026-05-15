<?php

namespace App\Http\Controllers\Parceiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parceiro\UploadDocumentoFuncionarioRequest;
use App\Models\DocumentoFuncionario;
use App\Models\Funcionario;
use App\Services\Documentos\GeradorSlots;
use App\Services\UploadArquivoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FuncionarioDocumentoController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
        private UploadArquivoService $upload,
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
            404,
        );

        $this->upload->uploadFuncionario(
            file: $request->file('arquivo'),
            documento: $documento_funcionario,
            partner: $partner,
            funcionario: $funcionario,
            validade: $request->date('validade'),
        );

        return redirect()
            ->route('parceiro.funcionarios.docs.index', $funcionario)
            ->with('status', 'Documento enviado e aguardando validação.');
    }

    public function download(Funcionario $funcionario, DocumentoFuncionario $documento_funcionario): StreamedResponse
    {
        $partner = auth()->user()->currentPartner();
        abort_if(
            $partner === null
            || $funcionario->parceiro_id !== $partner->id
            || $documento_funcionario->funcionario_id !== $funcionario->id,
            404,
        );

        $this->authorize('download', $documento_funcionario);

        abort_if($documento_funcionario->arquivo_caminho === null || $documento_funcionario->arquivo_disco === null, 404);

        return $this->upload->servir(
            caminho: $documento_funcionario->arquivo_caminho,
            disco: $documento_funcionario->arquivo_disco,
            mimeHint: $documento_funcionario->arquivo_mime,
        );
    }
}
