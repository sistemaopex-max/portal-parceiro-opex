<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerCategory;
use App\Models\PartnerInvitation;
use App\Services\ConviteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConviteController extends Controller
{
    public function __construct(
        private ConviteService $service,
    ) {}

    public function index(): View
    {
        $invitations = PartnerInvitation::query()
            ->with(['category', 'creator'])
            ->orderByDesc('criado_em')
            ->paginate(20);

        return view('admin.invitations.index', compact('invitations'));
    }

    public function create(): View
    {
        $categories = PartnerCategory::query()->orderBy('nome')->get();

        return view('admin.invitations.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'categoria_id' => ['required', 'exists:categorias_parceiro,id'],
        ]);

        $invitation = $this->service->criar(
            criadoPor: auth()->id(),
            categoriaId: (int) $validated['categoria_id'],
            email: $validated['email'],
        );

        return redirect()
            ->route('admin.invitations.index')
            ->with('status', 'Convite enviado para ' . $invitation->email . '.');
    }

    public function destroy(PartnerInvitation $invitation): RedirectResponse
    {
        $this->service->cancelar($invitation);

        return redirect()
            ->route('admin.invitations.index')
            ->with('status', 'Convite removido.');
    }
}
