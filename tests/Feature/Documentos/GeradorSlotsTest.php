<?php

namespace Tests\Feature\Documentos;

use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoEmpresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeradorSlotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_slots_empresa_are_created_when_partner_is_created_with_category_requirements(): void
    {
        $category = PartnerCategory::factory()->create();
        $tipo = TipoDocumentoEmpresa::factory()->create([
            'partner_category_id' => $category->id,
            'nome' => 'Contrato',
        ]);

        $user = User::factory()->create(['role' => User::ROLE_PARTNER]);
        $partner = Partner::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $category->id,
        ]);

        $this->assertCount(1, $partner->documentosEmpresa);
        $this->assertSame($tipo->id, $partner->documentosEmpresa->first()->tipo_documento_empresa_id);
    }
}
