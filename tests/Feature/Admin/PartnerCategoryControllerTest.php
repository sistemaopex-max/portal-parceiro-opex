<?php

namespace Tests\Feature\Admin;

use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_cannot_access_categories_index(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_PARTNER]);

        $response = $this->actingAs($user)->get(route('admin.partner-categories.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.partner-categories.store'), [
            'name' => 'Distribuidor',
            'description' => 'Teste',
            'is_active' => '1',
        ]);

        $category = PartnerCategory::query()->where('slug', 'distribuidor')->first();
        $this->assertNotNull($category);
        $response->assertRedirect(route('admin.partner-categories.show', $category));
        $this->assertDatabaseHas('partner_categories', [
            'slug' => 'distribuidor',
            'name' => 'Distribuidor',
        ]);
    }

    public function test_admin_cannot_delete_category_with_partners(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();
        $partnerUser = User::factory()->create(['role' => User::ROLE_PARTNER]);
        Partner::factory()->create([
            'user_id' => $partnerUser->id,
            'categoria_id' => $category->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.partner-categories.destroy', $category));

        $response->assertRedirect(route('admin.partner-categories.index'));
        $response->assertSessionHasErrors([
            'delete' => 'Não posso excluir pois há empresas com a categoria associada',
        ]);
        $this->assertDatabaseHas('partner_categories', ['id' => $category->id]);
    }
}
