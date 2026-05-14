<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'user:admin')]
class AdminUserCreateCommand extends Command
{
    protected $signature = 'user:admin
                            {email : E-mail de login}
                            {--password=password : Senha inicial}
                            {--name=Administrador : Nome exibido}';

    protected $description = 'Cria um usuário com papel admin (equipe interna, sem vínculo de parceiro).';

    public function handle(): int
    {
        $email = (string) $this->argument('email');

        if (User::query()->where('email', $email)->exists()) {
            $this->error("Já existe usuário com e-mail [{$email}].");

            return self::FAILURE;
        }

        User::query()->create([
            'name' => (string) $this->option('name'),
            'email' => $email,
            'password' => Hash::make((string) $this->option('password')),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->info("Admin criado: {$email} (papel: ".User::ROLE_ADMIN.').');

        return self::SUCCESS;
    }
}
