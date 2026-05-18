<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/perfil');

        $response->assertOk();
    }

    public function test_profile_name_can_be_updated(): void
    {
        $user = User::factory()->create([
            'name' => 'Nome Antigo',
            'email' => 'usuario@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/perfil', [
                'name' => 'Nome Novo',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/perfil');

        $user->refresh();

        $this->assertSame('Nome Novo', $user->name);
        $this->assertSame('usuario@example.com', $user->email);
    }

    public function test_profile_email_cannot_be_changed_via_form(): void
    {
        $user = User::factory()->create([
            'email' => 'original@example.com',
        ]);

        $this
            ->actingAs($user)
            ->patch('/perfil', [
                'name' => $user->name,
                'email' => 'novo@example.com',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/perfil');

        $this->assertSame('original@example.com', $user->fresh()->email);
    }
}
