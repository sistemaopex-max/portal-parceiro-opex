<?php

namespace Tests\Feature\Documentos;

use App\Enums\StatusDocumento;
use App\Models\DocumentoFuncionario;
use App\Models\FuncaoFuncionario;
use App\Models\Funcionario;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoFuncionario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentacaoEmDiaTest extends TestCase
{
    use RefreshDatabase;

    public function test_documentacao_em_dia_true_when_all_required_documents_are_valid(): void
    {
        $category = PartnerCategory::factory()->create();
        $funcao = FuncaoFuncionario::factory()->create(['partner_category_id' => $category->id]);
        TipoDocumentoFuncionario::factory()->create(['funcao_funcionario_id' => $funcao->id]);

        $user = User::factory()->create(['role' => User::ROLE_PARTNER]);
        $partner = Partner::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $category->id,
        ]);

        $funcionario = Funcionario::factory()->create([
            'parceiro_id' => $partner->id,
            'funcao_funcionario_id' => $funcao->id,
        ]);

        /** @var DocumentoFuncionario $doc */
        $doc = $funcionario->documentos()->firstOrFail();
        $doc->update([
            'arquivo_disco' => 'local',
            'arquivo_caminho' => 'path/x.pdf',
            'arquivo_nome_original' => 'x.pdf',
            'arquivo_mime' => 'application/pdf',
            'arquivo_tamanho' => 10,
            'status' => StatusDocumento::Valido,
            'validade' => now()->addMonth()->toDateString(),
            'validado_por_id' => User::factory()->admin()->create()->id,
            'validado_em' => now(),
        ]);

        $this->assertTrue($funcionario->fresh()->documentacao_em_dia);
    }
}
