<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusDocumento;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\View\View;

class DocumentosPendentesController extends Controller
{
    public function index(): View
    {
        $parceiros = Partner::query()
            ->ativos()
            ->where(function ($query) {
                $query->whereHas(
                    'documentosEmpresa',
                    fn ($q) => $q->where('status', StatusDocumento::Pendente)->doTipoAtivo(),
                )->orWhereHas(
                    'funcionarios.documentos',
                    fn ($q) => $q->where('status', StatusDocumento::Pendente)->doTipoAtivo(),
                );
            })
            ->with([
                'documentosEmpresa' => fn ($q) => $q
                    ->where('status', StatusDocumento::Pendente)
                    ->doTipoAtivo()
                    ->with('tipo')
                    ->orderBy('id'),
                'funcionarios' => fn ($q) => $q
                    ->whereHas(
                        'documentos',
                        fn ($d) => $d->where('status', StatusDocumento::Pendente)->doTipoAtivo(),
                    )
                    ->orderBy('nome')
                    ->with([
                        'documentos' => fn ($d) => $d
                            ->where('status', StatusDocumento::Pendente)
                            ->doTipoAtivo()
                            ->with('tipo')
                            ->orderBy('id'),
                    ]),
            ])
            ->orderBy('razao_social')
            ->get();

        return view('admin.documentos.pendentes.index', compact('parceiros'));
    }
}
