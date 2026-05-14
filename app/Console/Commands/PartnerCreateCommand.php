<?php

namespace App\Console\Commands;

use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'partner:create')]
class PartnerCreateCommand extends Command
{
    protected $signature = 'partner:create
                            {email : E-mail de login do parceiro}
                            {category_slug : Slug da categoria (ex.: distribuidor)}
                            {--password=password : Senha inicial}
                            {--user-name= : Nome do usuário (padrão: parte do e-mail)}
                            {--razao-social= : Razão social (padrão: mesmo que user-name)}
                            {--cnpj= : CNPJ opcional}
                            {--telefone= : Telefone opcional}
                            {--endereco= : Endereço opcional}
                            {--cidade= : Cidade opcional}
                            {--uf= : UF (2 letras) opcional}';

    protected $description = 'Cria usuário parceiro e registro da empresa vinculado à categoria.';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $slug = (string) $this->argument('category_slug');

        $category = PartnerCategory::query()->where('slug', $slug)->where('is_active', true)->first();
        if (! $category) {
            $this->error("Categoria ativa com slug [{$slug}] não encontrada.");

            return self::FAILURE;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->error("Já existe usuário com e-mail [{$email}].");

            return self::FAILURE;
        }

        $userName = $this->option('user-name') ?: Str::before($email, '@');
        $razaoSocial = $this->option('razao-social') ?: $userName;
        $uf = $this->option('uf') ? strtoupper((string) $this->option('uf')) : null;

        $user = User::query()->create([
            'name' => $userName,
            'email' => $email,
            'password' => Hash::make((string) $this->option('password')),
            'role' => User::ROLE_PARTNER,
            'email_verified_at' => now(),
        ]);

        Partner::query()->create([
            'user_id' => $user->id,
            'categoria_id' => $category->id,
            'razao_social' => $razaoSocial,
            'cnpj' => $this->option('cnpj') ?: null,
            'telefone' => $this->option('telefone') ?: null,
            'endereco' => $this->option('endereco') ?: null,
            'email' => $email,
            'cidade' => $this->option('cidade') ?: null,
            'uf' => $uf,
            'ativo' => true,
        ]);

        $this->info("Parceiro criado: {$email} → categoria {$category->name} ({$slug}).");

        return self::SUCCESS;
    }
}
