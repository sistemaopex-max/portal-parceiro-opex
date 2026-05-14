<?php

namespace Tests\Feature\Documentos;

use App\Enums\StatusDocumento;
use App\Models\DocumentoEmpresa;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoEmpresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidarDocumentoEmpresaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_mark_document_as_valid(): void
    {
        $tipo = TipoDocumentoEmpresa::factory()->create();
        $category = PartnerCategory::factory()->create();
        $category->tiposDocumentoExigidos()->attach($tipo->id);

        $user = User::factory()->create(['role' => User::ROLE_PARTNER]);
        $partner = Partner::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $category->id,
        ]);

        /** @var DocumentoEmpresa $doc */
        $doc = $partner->documentosEmpresa()->firstOrFail();
        $doc->update([
            'arquivo_disco' => 'local',
            'arquivo_caminho' => 'parceiros/'.$partner->id.'/empresa/'.$doc->id.'/x.pdf',
            'arquivo_nome_original' => 'x.pdf',
            'arquivo_mime' => 'application/pdf',
            'arquivo_tamanho' => 100,
            'status' => StatusDocumento::Pendente,
        ]);

        $admin = User::factory()->admin()->create();
        $validade = now()->addMonth()->toDateString();

        $response = $this->actingAs($admin)->post(route('admin.documentos-empresa.validar', $doc), [
            'decisao' => 'valido',
            'validade' => $validade,
        ]);

        $response->assertRedirect(route('admin.parceiros.documentos-empresa.index', $partner));
        $doc->refresh();
        $this->assertSame(StatusDocumento::Valido, $doc->status);
        $this->assertSame($validade, $doc->validade->toDateString());
        $this->assertSame($admin->id, $doc->validado_por_id);
    }
}
