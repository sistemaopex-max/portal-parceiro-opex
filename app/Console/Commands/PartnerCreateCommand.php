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
                            {--user-name= : Nome do usuário (padrão: nome fantasia ou parte do e-mail)}
                            {--legal-name= : Razão social (padrão: mesmo que user-name)}
                            {--trade-name= : Nome fantasia opcional}
                            {--cnpj= : CNPJ opcional}
                            {--phone= : Telefone opcional}';

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
        $legalName = $this->option('legal-name') ?: $userName;

        $user = User::query()->create([
            'name' => $userName,
            'email' => $email,
            'password' => Hash::make((string) $this->option('password')),
            'role' => User::ROLE_PARTNER,
            'email_verified_at' => now(),
        ]);

        Partner::query()->create([
            'user_id' => $user->id,
            'partner_category_id' => $category->id,
            'legal_name' => $legalName,
            'trade_name' => $this->option('trade-name') ?: null,
            'cnpj' => $this->option('cnpj') ?: null,
            'phone' => $this->option('phone') ?: null,
        ]);

        $this->info("Parceiro criado: {$email} → categoria {$category->name} ({$slug}).");

        return self::SUCCESS;
    }
}
