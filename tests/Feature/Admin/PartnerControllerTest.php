<?php

namespace Tests\Feature\Admin;

use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_cannot_access_partners_index(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_PARTNER]);

        $response = $this->actingAs($user)->get(route('admin.parceiros.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_partners_index(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.parceiros.index'));

        $response->assertOk();
    }

    public function test_admin_can_update_partner_without_changing_password(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create(['is_active' => true]);
        $partnerUser = User::factory()->create([
            'role' => User::ROLE_PARTNER,
            'email' => 'antes@test.com',
        ]);
        $partner = Partner::factory()->create([
            'user_id' => $partnerUser->id,
            'categoria_id' => $category->id,
            'razao_social' => 'Antes',
            'email' => 'antes@test.com',
            'ativo' => true,
        ]);
        $originalHash = $partnerUser->password;

        $response = $this->actingAs($admin)->put(route('admin.parceiros.update', $partner), [
            'name' => 'Maria Souza',
            'email' => 'depois@test.com',
            'password' => '',
            'password_confirmation' => '',
            'razao_social' => 'Depois LTDA',
            'cnpj' => null,
            'telefone' => null,
            'endereco' => 'Rua das Flores, 100',
            'cidade' => 'Campinas',
            'uf' => 'sp',
            'categoria_id' => $category->id,
            'ativo' => '1',
        ]);

        $response->assertRedirect(route('admin.parceiros.index'));
        $partnerUser->refresh();
        $this->assertSame('depois@test.com', $partnerUser->email);
        $this->assertSame($originalHash, $partnerUser->password);
        $partner->refresh();
        $this->assertSame('Depois LTDA', $partner->razao_social);
        $this->assertSame('Rua das Flores, 100', $partner->endereco);
        $this->assertSame('Campinas', $partner->cidade);
        $this->assertSame('SP', $partner->uf);
        $this->assertSame('depois@test.com', $partner->email);
        $this->assertTrue($partner->ativo);
    }

    public function test_admin_can_set_partner_inactive_via_update(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create(['is_active' => true]);
        $partnerUser = User::factory()->create(['role' => User::ROLE_PARTNER]);
        $partner = Partner::factory()->create([
            'user_id' => $partnerUser->id,
            'categoria_id' => $category->id,
            'razao_social' => 'Ativo SA',
            'email' => 'ativo@test.com',
            'ativo' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.parceiros.update', $partner), [
            'name' => $partnerUser->name,
            'email' => $partnerUser->email,
            'password' => '',
            'password_confirmation' => '',
            'razao_social' => 'Ativo SA',
            'cnpj' => null,
            'telefone' => null,
            'endereco' => null,
            'cidade' => null,
            'uf' => null,
            'categoria_id' => $category->id,
            'ativo' => '0',
        ]);

        $response->assertRedirect(route('admin.parceiros.index'));
        $partner->refresh();
        $this->assertFalse($partner->ativo);
    }

    public function test_admin_can_filter_partners_by_category(): void
    {
        $admin = User::factory()->admin()->create();
        $catA = PartnerCategory::factory()->create(['name' => 'Alpha']);
        $catB = PartnerCategory::factory()->create(['name' => 'Beta']);
        $userA = User::factory()->create(['role' => User::ROLE_PARTNER]);
        Partner::factory()->create([
            'user_id' => $userA->id,
            'categoria_id' => $catA->id,
            'razao_social' => 'Empresa Alpha',
            'email' => 'alpha@test.com',
        ]);
        $userB = User::factory()->create(['role' => User::ROLE_PARTNER]);
        Partner::factory()->create([
            'user_id' => $userB->id,
            'categoria_id' => $catB->id,
            'razao_social' => 'Empresa Beta',
            'email' => 'beta@test.com',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.parceiros.index', ['categoria' => $catA->id]));

        $response->assertOk();
        $response->assertSee('Empresa Alpha');
        $response->assertDontSee('Empresa Beta');
    }
}
