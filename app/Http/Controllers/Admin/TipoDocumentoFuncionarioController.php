<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumentoFuncionario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipoDocumentoFuncionarioController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TipoDocumentoFuncionario::class, 'tipo_documento_funcionario');
    }

    public function index(): View
    {
        $tipos = TipoDocumentoFuncionario::query()->orderBy('nome')->paginate(20);

        return view('admin.tipos-documento-funcionario.index', compact('tipos'));
    }

    public function create(): View
    {
        return view('admin.tipos-documento-funcionario.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $data['ativo'] = $request->boolean('ativo', true);

        TipoDocumentoFuncionario::query()->create($data);

        return redirect()
            ->route('admin.tipos-documento-funcionario.index')
            ->with('status', 'Tipo de documento criado.');
    }

    public function edit(TipoDocumentoFuncionario $tipo_documento_funcionario): View
    {
        return view('admin.tipos-documento-funcionario.edit', ['tipo' => $tipo_documento_funcionario]);
    }

    public function update(Request $request, TipoDocumentoFuncionario $tipo_documento_funcionario): RedirectResponse
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $data['ativo'] = $request->boolean('ativo');

        $tipo_documento_funcionario->update($data);

        return redirect()
            ->route('admin.tipos-documento-funcionario.index')
            ->with('status', 'Tipo de documento atualizado.');
    }

    public function destroy(TipoDocumentoFuncionario $tipo_documento_funcionario): RedirectResponse
    {
        $tipo_documento_funcionario->delete();

        return redirect()
            ->route('admin.tipos-documento-funcionario.index')
            ->with('status', 'Tipo de documento excluído.');
    }
}
