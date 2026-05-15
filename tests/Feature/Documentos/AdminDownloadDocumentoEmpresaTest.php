<?php

namespace Tests\Feature\Documentos;

use App\Enums\StatusDocumento;
use App\Models\DocumentoEmpresa;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoEmpresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDownloadDocumentoEmpresaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_partner_company_document(): void
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
        $path = "parceiros/{$partner->id}/empresa/{$doc->id}/test.pdf";
        Storage::disk('local')->put($path, 'conteudo-teste');

        $doc->update([
            'arquivo_disco' => 'local',
            'arquivo_caminho' => $path,
            'arquivo_nome_original' => 'contrato.pdf',
            'arquivo_mime' => 'application/pdf',
            'arquivo_tamanho' => 14,
            'status' => StatusDocumento::Pendente,
        ]);

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.documentos-empresa.download', $doc));

        $response->assertOk();
        $response->assertDownload('contrato.pdf');
    }
}
