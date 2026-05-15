<?php

namespace App\Http\Controllers\Parceiro;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Rules\ValidCnpj;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FilialController extends Controller
{
    public function index(): View
    {
        $filiais = auth()->user()->partners()->orderBy('razao_social')->get();

        return view('parceiro.filiais.index', compact('filiais'));
    }

    public function create(): View
    {
        return view('parceiro.filiais.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('cnpj')) {
            $request->merge(['cnpj' => Partner::normalizarCnpj($request->input('cnpj'))]);
        }

        $validated = $request->validate([
            'razao_social' => ['required', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'size:14', new ValidCnpj],
            'telefone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'cidade' => ['nullable', 'string', 'max:120'],
            'uf' => ['nullable', 'string', 'size:2'],
        ]);

        $user = auth()->user();
        $firstPartner = $user->partners()->first();

        $slug = Str::slug($validated['razao_social']) ?: Str::random(8);
        $baseSlug = $slug;
        $count = 1;
        while (Partner::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $filial = Partner::create([
            'user_id' => $user->id,
            'categoria_id' => $firstPartner?->categoria_id,
            'razao_social' => $validated['razao_social'],
            'cnpj' => $validated['cnpj'] ?? null,
            'telefone' => $validated['telefone'] ?? null,
            'email' => $validated['email'] ?? null,
            'endereco' => $validated['endereco'] ?? null,
            'cidade' => $validated['cidade'] ?? null,
            'uf' => $validated['uf'] ?? null,
            'slug' => $slug,
        ]);

        return redirect()
            ->route('parceiro.filiais.index')
            ->with('status', 'Filial cadastrada com sucesso.');
    }

    public function edit(Partner $partner): View
    {
        abort_if($partner->user_id !== auth()->id(), 403);

        return view('parceiro.filiais.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        abort_if($partner->user_id !== auth()->id(), 403);

        if ($request->filled('cnpj')) {
            $request->merge(['cnpj' => Partner::normalizarCnpj($request->input('cnpj'))]);
        }

        $validated = $request->validate([
            'razao_social' => ['required', 'string', 'max:255'],
            'cnpj' => ['nullable', 'string', 'size:14', new ValidCnpj],
            'telefone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'cidade' => ['nullable', 'string', 'max:120'],
            'uf' => ['nullable', 'string', 'size:2'],
        ]);

        $partner->update($validated);

        return redirect()
            ->route('parceiro.filiais.index')
            ->with('status', 'Filial atualizada com sucesso.');
    }

    public function switch(Partner $partner): RedirectResponse
    {
        abort_if($partner->user_id !== auth()->id(), 403);

        session(['current_partner_id' => $partner->id]);

        return redirect()->back()->with('status', 'Filial ativa alterada para ' . $partner->razao_social . '.');
    }
}
