<?php

namespace Tests\Feature\Admin;

use App\Models\PartnerCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSidebarMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_does_not_auto_open_partners_dropdown(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('x-init="partnersOpen = true"', false);
    }

    public function test_partner_categories_page_auto_opens_partners_dropdown(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.partner-categories.index'))
            ->assertOk()
            ->assertSee('x-init="partnersOpen = true"', false);
    }

    public function test_categories_link_is_nested_under_partners_menu(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee(route('admin.partner-categories.index'), false);
        $response->assertSee('>Categorias</a>', false);
        $response->assertSee('>Parceiros</span>', false);
        $response->assertSee('>Gerenciar</a>', false);

        $content = $response->getContent();
        $parceirosPos = strpos($content, '>Parceiros</span>');
        $categoriasPos = strpos($content, '>Categorias</a>');
        $inicioPos = strpos($content, '>Início</span>');

        $this->assertNotFalse($parceirosPos);
        $this->assertNotFalse($categoriasPos);
        $this->assertNotFalse($inicioPos);
        $this->assertGreaterThan($inicioPos, $parceirosPos);
        $this->assertGreaterThan($parceirosPos, $categoriasPos);
    }
}
