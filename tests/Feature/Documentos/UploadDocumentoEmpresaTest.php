<?php

namespace Tests\Feature\Documentos;

use App\Enums\StatusDocumento;
use App\Models\DocumentoEmpresa;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoEmpresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadDocumentoEmpresaTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_can_upload_company_document(): void
    {
        Storage::fake('local');

        $category = PartnerCategory::factory()->create();
        TipoDocumentoEmpresa::factory()->create(['partner_category_id' => $category->id]);

        $user = User::factory()->create(['role' => User::ROLE_PARTNER]);
        $partner = Partner::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $category->id,
        ]);

        /** @var DocumentoEmpresa $doc */
        $doc = $partner->documentosEmpresa()->firstOrFail();
        $file = UploadedFile::fake()->create('doc.pdf', 120, 'application/pdf');
        $validade = now()->addDays(10)->toDateString();

        $response = $this->actingAs($user)->post(
            route('parceiro.empresa.documentos.upload', $doc),
            [
                'arquivo' => $file,
                'validade' => $validade,
            ]
        );

        $response->assertRedirect(route('parceiro.empresa.documentos.index'));
        $doc->refresh();
        $this->assertSame(StatusDocumento::Pendente, $doc->status);
        $this->assertNotNull($doc->arquivo_caminho);
        Storage::disk('local')->assertExists($doc->arquivo_caminho);
    }
}
