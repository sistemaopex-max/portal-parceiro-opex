<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FuncaoFuncionario;
use App\Models\TipoDocumentoFuncionario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FuncaoFuncionarioController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(FuncaoFuncionario::class, 'funcao_funcionario');
    }

    public function index(): View
    {
        $funcoes = FuncaoFuncionario::query()->orderBy('nome')->paginate(20);

        return view('admin.funcoes-funcionario.index', compact('funcoes'));
    }

    public function create(): View
    {
        $tipos = TipoDocumentoFuncionario::query()->where('ativo', true)->orderBy('nome')->get();

        return view('admin.funcoes-funcionario.create', compact('tipos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ativo' => ['nullable', 'boolean'],
            'tipo_documento_funcionario_ids' => ['nullable', 'array'],
            'tipo_documento_funcionario_ids.*' => ['integer', Rule::exists('tipos_documento_funcionario', 'id')],
        ]);

        $tipos = $data['tipo_documento_funcionario_ids'] ?? [];
        unset($data['tipo_documento_funcionario_ids']);

        $data['ativo'] = $request->boolean('ativo', true);

        $funcao = FuncaoFuncionario::query()->create($data);
        $funcao->tiposDocumentoExigidos()->sync($tipos);

        return redirect()
            ->route('admin.funcoes-funcionario.index')
            ->with('status', 'Função criada.');
    }

    public function edit(FuncaoFuncionario $funcao_funcionario): View
    {
        $funcao_funcionario->load('tiposDocumentoExigidos');
        $tipos = TipoDocumentoFuncionario::query()->where('ativo', true)->orderBy('nome')->get();

        return view('admin.funcoes-funcionario.edit', compact('funcao_funcionario', 'tipos'));
    }

    public function update(Request $request, FuncaoFuncionario $funcao_funcionario): RedirectResponse
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ativo' => ['nullable', 'boolean'],
            'tipo_documento_funcionario_ids' => ['nullable', 'array'],
            'tipo_documento_funcionario_ids.*' => ['integer', Rule::exists('tipos_documento_funcionario', 'id')],
        ]);

        $tipos = $data['tipo_documento_funcionario_ids'] ?? [];
        unset($data['tipo_documento_funcionario_ids']);

        $data['ativo'] = $request->boolean('ativo');

        $funcao_funcionario->update($data);
        $funcao_funcionario->tiposDocumentoExigidos()->sync($tipos);

        return redirect()
            ->route('admin.funcoes-funcionario.index')
            ->with('status', 'Função atualizada.');
    }

    public function destroy(FuncaoFuncionario $funcao_funcionario): RedirectResponse
    {
        if ($funcao_funcionario->funcionarios()->exists()) {
            return redirect()
                ->route('admin.funcoes-funcionario.index')
                ->withErrors(['delete' => 'Não é possível excluir: existem funcionários vinculados a esta função.']);
        }

        $funcao_funcionario->tiposDocumentoExigidos()->detach();
        $funcao_funcionario->delete();

        return redirect()
            ->route('admin.funcoes-funcionario.index')
            ->with('status', 'Função excluída.');
    }
}
