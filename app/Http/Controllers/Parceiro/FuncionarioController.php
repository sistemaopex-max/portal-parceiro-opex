<?php

namespace App\Http\Controllers\Parceiro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parceiro\StoreFuncionarioRequest;
use App\Http\Requests\Parceiro\UpdateFuncionarioRequest;
use App\Models\FuncaoFuncionario;
use App\Models\Funcionario;
use App\Services\Documentos\GeradorSlots;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FuncionarioController extends Controller
{
    public function __construct(
        private GeradorSlots $geradorSlots,
    ) {
        $this->authorizeResource(Funcionario::class, 'funcionario');
    }

    public function index(): View
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null, 404);

        $funcionarios = $partner->funcionarios()
            ->with('funcao')
            ->orderBy('nome')
            ->paginate(15);

        return view('parceiro.funcionarios.index', compact('partner', 'funcionarios'));
    }

    public function create(): View
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null, 404);

        $funcoes = FuncaoFuncionario::query()
            ->where('categoria_id', $partner->categoria_id)
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('parceiro.funcionarios.create', compact('partner', 'funcoes'));
    }

    public function store(StoreFuncionarioRequest $request): RedirectResponse
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null, 404);

        $funcionario = $partner->funcionarios()->create($request->validated());

        $this->geradorSlots->garantirSlotsFuncionario($funcionario);

        return redirect()
            ->route('parceiro.funcionarios.index')
            ->with('status', 'Funcionário cadastrado.');
    }

    public function edit(Funcionario $funcionario): View
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null || $funcionario->parceiro_id !== $partner->id, 404);

        $funcoes = FuncaoFuncionario::query()
            ->where('categoria_id', $partner->categoria_id)
            ->where(function ($query) use ($funcionario) {
                $query->ativos()
                    ->orWhere('id', $funcionario->funcao_funcionario_id);
            })
            ->orderBy('nome')
            ->get();

        return view('parceiro.funcionarios.edit', compact('partner', 'funcionario', 'funcoes'));
    }

    public function update(UpdateFuncionarioRequest $request, Funcionario $funcionario): RedirectResponse
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null || $funcionario->parceiro_id !== $partner->id, 404);

        $funcionario->update($request->validated());

        $this->geradorSlots->garantirSlotsFuncionario($funcionario->fresh(['funcao']));

        return redirect()
            ->route('parceiro.funcionarios.index')
            ->with('status', 'Funcionário atualizado.');
    }

    public function destroy(Funcionario $funcionario): RedirectResponse
    {
        $partner = auth()->user()->currentPartner();
        abort_if($partner === null || $funcionario->parceiro_id !== $partner->id, 404);

        $funcionario->delete();

        return redirect()
            ->route('parceiro.funcionarios.index')
            ->with('status', 'Funcionário removido.');
    }
}
