<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\PartnerInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistroParceiroService
{
    /**
     * Cria usuário e parceiro a partir dos dados de um convite válido.
     *
     * @param  array<string, mixed>  $dados  Dados validados do formulário
     * @return array{user: User, partner: Partner}
     */
    public function registrar(PartnerInvitation $invitation, array $dados): array
    {
        $user = User::create([
            'name' => $dados['razao_social'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
            'role' => User::ROLE_PARTNER,
        ]);

        $slug = Str::slug($dados['razao_social']) ?: Str::random(8);
        $base = $slug;
        $count = 1;
        while (Partner::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        $partner = Partner::create([
            'user_id' => $user->id,
            'categoria_id' => $invitation->categoria_id,
            'razao_social' => $dados['razao_social'],
            'cnpj' => $dados['cnpj'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'email' => $invitation->email,
            'endereco' => $dados['endereco'] ?? null,
            'cidade' => $dados['cidade'] ?? null,
            'uf' => $dados['uf'] ?? null,
            'slug' => $slug,
        ]);

        $invitation->update(['usado_em' => now()]);

        return compact('user', 'partner');
    }
}
