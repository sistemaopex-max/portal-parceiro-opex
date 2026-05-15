<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ValidarDocumentoFuncionarioRequest;
use App\Models\DocumentoFuncionario;
use App\Models\Funcionario;
use App\Models\Partner;
use App\Services\Documentos\GeradorSlots;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParceiroDocumentoFuncionarioController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {}

    public function index(Partner $partner, Funcionario $funcionario): View
    {
        abort_if($funcionario->parceiro_id !== $partner->id, 404);

        $this->authorize('view', $partner);

        $this->geradorSlots->garantirSlotsFuncionario($funcionario);

        $documentos = $funcionario->documentos()
            ->doTipoAtivo()
            ->with(['tipo', 'validadoPor'])
            ->orderBy('id')
            ->get();

        return view('admin.partners.documentos-funcionario.index', compact('partner', 'funcionario', 'documentos'));
    }

    public function download(DocumentoFuncionario $documento_funcionario): StreamedResponse
    {
        $this->authorize('download', $documento_funcionario);

        abort_if($documento_funcionario->arquivo_caminho === null || $documento_funcionario->arquivo_disco === null, 404);

        abort_unless(
            Storage::disk($documento_funcionario->arquivo_disco)->exists($documento_funcionario->arquivo_caminho),
            404,
            'Arquivo não encontrado no armazenamento.'
        );

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

    public function validar(ValidarDocumentoFuncionarioRequest $request, DocumentoFuncionario $documento_funcionario): RedirectResponse
    {
        $data = $request->validated();

        if ($data['decisao'] === 'valido') {
            $documento_funcionario->marcarValido($request->user(), Carbon::parse($data['validade']));
        } else {
            $documento_funcionario->marcarInvalido($request->user(), (string) $data['observacoes']);
        }

        $funcionario = $documento_funcionario->funcionario()->with('parceiro')->firstOrFail();

        return redirect()
            ->route('admin.parceiros.funcionarios.documentos.index', [$funcionario->parceiro, $funcionario])
            ->with('status', 'Documento atualizado.');
    }
}
