<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PartnerInvitationMail;
use App\Models\PartnerCategory;
use App\Models\PartnerInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConviteController extends Controller
{
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

        $invitation = PartnerInvitation::create([
            'criado_por' => auth()->id(),
            'categoria_id' => $validated['categoria_id'],
            'email' => $validated['email'],
            'token' => Str::random(48),
            'expira_em' => now()->addDays(7),
        ]);

        Mail::to($invitation->email)->send(new PartnerInvitationMail($invitation));

        return redirect()
            ->route('admin.invitations.index')
            ->with('status', 'Convite enviado para ' . $invitation->email . '.');
    }

    public function destroy(PartnerInvitation $invitation): RedirectResponse
    {
        $invitation->delete();

        return redirect()
            ->route('admin.invitations.index')
            ->with('status', 'Convite removido.');
    }
}
