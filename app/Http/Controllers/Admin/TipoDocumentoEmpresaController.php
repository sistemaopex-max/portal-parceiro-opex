<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumentoEmpresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipoDocumentoEmpresaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TipoDocumentoEmpresa::class, 'tipo_documento_empresa');
    }

    public function index(): View
    {
        $tipos = TipoDocumentoEmpresa::query()->orderBy('nome')->paginate(20);

        return view('admin.tipos-documento-empresa.index', compact('tipos'));
    }

    public function create(): View
    {
        return view('admin.tipos-documento-empresa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $data['ativo'] = $request->boolean('ativo', true);

        TipoDocumentoEmpresa::query()->create($data);

        return redirect()
            ->route('admin.tipos-documento-empresa.index')
            ->with('status', 'Tipo de documento criado.');
    }

    public function edit(TipoDocumentoEmpresa $tipo_documento_empresa): View
    {
        return view('admin.tipos-documento-empresa.edit', ['tipo' => $tipo_documento_empresa]);
    }

    public function update(Request $request, TipoDocumentoEmpresa $tipo_documento_empresa): RedirectResponse
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $data['ativo'] = $request->boolean('ativo');

        $tipo_documento_empresa->update($data);

        return redirect()
            ->route('admin.tipos-documento-empresa.index')
            ->with('status', 'Tipo de documento atualizado.');
    }

    public function destroy(TipoDocumentoEmpresa $tipo_documento_empresa): RedirectResponse
    {
        $tipo_documento_empresa->delete();

        return redirect()
            ->route('admin.tipos-documento-empresa.index')
            ->with('status', 'Tipo de documento excluído.');
    }
}
