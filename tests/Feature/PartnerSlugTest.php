<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_slug_is_generated_from_razao_social_and_cnpj_digits(): void
    {
        $category = PartnerCategory::factory()->create();
        $user = User::factory()->create(['role' => User::ROLE_PARTNER]);
        $partner = Partner::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $category->id,
            'razao_social' => 'Empresa Teste LTDA',
            'cnpj' => '12.345.678/0001-99',
        ]);

        $this->assertSame('empresa-teste-ltda-12345678000199', $partner->slug);
    }

    public function test_partner_slug_regenerates_when_cnpj_changes(): void
    {
        $category = PartnerCategory::factory()->create();
        $user = User::factory()->create(['role' => User::ROLE_PARTNER]);
        $partner = Partner::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $category->id,
            'razao_social' => 'ACME',
            'cnpj' => '11.111.111/0001-11',
        ]);

        $partner->update(['cnpj' => '22.222.222/0001-22']);

        $this->assertSame('acme-22222222000122', $partner->fresh()->slug);
    }
}
