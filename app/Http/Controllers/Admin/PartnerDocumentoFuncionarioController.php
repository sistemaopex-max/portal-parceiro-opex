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
use Illuminate\View\View;

class PartnerDocumentoFuncionarioController extends Controller
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
            ->with(['tipo', 'validadoPor'])
            ->orderBy('id')
            ->get();

        return view('admin.partners.documentos-funcionario.index', compact('partner', 'funcionario', 'documentos'));
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
            ->route('admin.parceiros.funcionarios.documentos-funcionario.index', [$funcionario->parceiro, $funcionario])
            ->with('status', 'Documento atualizado.');
    }
}
