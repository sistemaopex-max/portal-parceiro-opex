<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerInvitation;
use App\Models\User;
use App\Rules\ValidCnpj;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegistroConviteController extends Controller
{
    public function show(string $token): View|RedirectResponse
    {
        $invitation = PartnerInvitation::with('category')->where('token', $token)->firstOrFail();

        if (! $invitation->isValid()) {
            return redirect()->route('login')->withErrors([
                'invitation' => $invitation->isUsed()
                    ? 'Este convite já foi utilizado.'
                    : 'Este convite expirou. Solicite um novo convite ao administrador.',
            ]);
        }

        return view('auth.invitation-register', compact('invitation', 'token'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $invitation = PartnerInvitation::with('category')->where('token', $token)->firstOrFail();

        if (! $invitation->isValid()) {
            return redirect()->route('login')->withErrors([
                'invitation' => 'Este convite não é mais válido.',
            ]);
        }

        if ($request->filled('cnpj')) {
            $request->merge(['cnpj' => Partner::normalizarCnpj($request->input('cnpj'))]);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'razao_social' => ['required', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'size:14', new ValidCnpj],
            'telefone' => ['nullable', 'string', 'max:32'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'cidade' => ['nullable', 'string', 'max:120'],
            'uf' => ['nullable', 'string', 'size:2'],
        ]);

        $user = User::create([
            'name' => $validated['razao_social'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_PARTNER,
        ]);

        $razaoSocial = $validated['razao_social'];
        $slug = Str::slug($razaoSocial) ?: Str::random(8);
        $baseSlug = $slug;
        $count = 1;
        while (Partner::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $partner = Partner::create([
            'user_id' => $user->id,
            'categoria_id' => $invitation->categoria_id,
            'razao_social' => $razaoSocial,
            'cnpj' => $validated['cnpj'] ?? null,
            'telefone' => $validated['telefone'] ?? null,
            'email' => $invitation->email,
            'endereco' => $validated['endereco'] ?? null,
            'cidade' => $validated['cidade'] ?? null,
            'uf' => $validated['uf'] ?? null,
            'slug' => $slug,
        ]);

        $invitation->update(['usado_em' => now()]);

        Auth::login($user);

        session(['current_partner_id' => $partner->id]);

        return redirect()->route('parceiro.dashboard')
            ->with('status', 'Conta criada com sucesso! Bem-vindo(a) ao Portal Opex.');
    }
}
