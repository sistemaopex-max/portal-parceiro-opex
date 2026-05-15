<?php

namespace Tests\Feature\Admin;

use App\Models\FuncaoFuncionario;
use App\Models\Funcionario;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\TipoDocumentoEmpresa;
use App\Models\TipoDocumentoFuncionario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryConfigPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_category_edit_page_with_slug_in_url(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create(['name' => 'Escolta', 'slug' => 'escolta']);

        $this->actingAs($admin)
            ->get(route('admin.partner-categories.edit', $category))
            ->assertOk()
            ->assertSee('Escolta');

        $this->actingAs($admin)
            ->get('/admin/partner-categories/slug-invalido/edit')
            ->assertNotFound();
    }

    public function test_admin_can_access_category_show_page(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.partner-categories.show', $category))
            ->assertOk()
            ->assertSee($category->name);
    }

    public function test_category_show_page_hides_inactive_document_types_and_functions(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();

        TipoDocumentoEmpresa::factory()->create([
            'partner_category_id' => $category->id,
            'nome' => 'Documento ativo',
            'ativo' => true,
        ]);
        TipoDocumentoEmpresa::factory()->create([
            'partner_category_id' => $category->id,
            'nome' => 'Documento inativo',
            'ativo' => false,
        ]);
        FuncaoFuncionario::factory()->create([
            'partner_category_id' => $category->id,
            'nome' => 'Função ativa',
            'ativo' => true,
        ]);
        FuncaoFuncionario::factory()->create([
            'partner_category_id' => $category->id,
            'nome' => 'Função inativa',
            'ativo' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.partner-categories.show', $category))
            ->assertOk()
            ->assertSee('Documento ativo')
            ->assertSee('Função ativa')
            ->assertDontSee('Documento inativo')
            ->assertDontSee('Função inativa')
            ->assertDontSee('(inativo)')
            ->assertDontSee('(inativa)');
    }

    public function test_admin_can_access_category_document_and_function_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.partner-categories.documentos-empresa.index', $category))
            ->assertOk()
            ->assertSee('Novo documento')
            ->assertDontSee('Salvar nome')
            ->assertDontSee('alternar');

        $this->actingAs($admin)
            ->get(route('admin.partner-categories.funcoes.index', $category))
            ->assertOk()
            ->assertSee('Nova função');
    }

    public function test_tipo_empresa_from_other_category_returns_404_on_update(): void
    {
        $admin = User::factory()->admin()->create();
        $categoryA = PartnerCategory::factory()->create();
        $categoryB = PartnerCategory::factory()->create();
        $tipo = TipoDocumentoEmpresa::factory()->create(['partner_category_id' => $categoryB->id]);

        $this->actingAs($admin)
            ->put(route('admin.partner-categories.documentos-empresa.update', [$categoryA, $tipo]), [
                'nome' => 'Outro nome',
                'ativo' => true,
            ])
            ->assertNotFound();
    }

    public function test_admin_can_create_documento_empresa_for_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.partner-categories.documentos-empresa.store', $category), [
                'nome' => 'Contrato social',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tipos_documento_empresa', [
            'partner_category_id' => $category->id,
            'nome' => 'Contrato social',
        ]);
    }

    public function test_admin_can_access_function_documents_page(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();
        $funcao = FuncaoFuncionario::factory()->create(['partner_category_id' => $category->id]);

        $this->actingAs($admin)
            ->get(route('admin.partner-categories.funcoes.documentos.index', [$category, $funcao]))
            ->assertOk();
    }

    public function test_tipo_funcionario_from_other_function_returns_404(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();
        $funcaoA = FuncaoFuncionario::factory()->create(['partner_category_id' => $category->id]);
        $funcaoB = FuncaoFuncionario::factory()->create(['partner_category_id' => $category->id]);
        $tipo = TipoDocumentoFuncionario::factory()->create(['funcao_funcionario_id' => $funcaoB->id]);

        $this->actingAs($admin)
            ->put(route('admin.partner-categories.funcoes.documentos.update', [$category, $funcaoA, $tipo]), [
                'nome' => 'X',
                'ativo' => true,
            ])
            ->assertNotFound();
    }

    public function test_admin_cannot_delete_tipo_empresa_when_slots_exist(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();
        $tipo = TipoDocumentoEmpresa::factory()->create(['partner_category_id' => $category->id]);
        Partner::factory()->create(['categoria_id' => $category->id]);

        $this->actingAs($admin)
            ->delete(route('admin.partner-categories.documentos-empresa.destroy', [$category, $tipo]))
            ->assertRedirect()
            ->assertSessionHasErrors([
                'delete' => 'Não é possível excluir: há empresas utilizando este documento.',
            ]);

        $this->assertDatabaseHas('tipos_documento_empresa', ['id' => $tipo->id]);
    }

    public function test_admin_can_delete_tipo_empresa_without_slots(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();
        $tipo = TipoDocumentoEmpresa::factory()->create(['partner_category_id' => $category->id]);

        $this->actingAs($admin)
            ->delete(route('admin.partner-categories.documentos-empresa.destroy', [$category, $tipo]))
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('tipos_documento_empresa', ['id' => $tipo->id]);
    }

    public function test_admin_cannot_delete_funcao_with_funcionarios(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();
        $funcao = FuncaoFuncionario::factory()->create(['partner_category_id' => $category->id]);
        Funcionario::factory()->create(['funcao_funcionario_id' => $funcao->id]);

        $this->actingAs($admin)
            ->delete(route('admin.partner-categories.funcoes.destroy', [$category, $funcao]))
            ->assertRedirect()
            ->assertSessionHasErrors([
                'delete' => 'Não é possível excluir: há funcionários utilizando esta função.',
            ]);

        $this->assertDatabaseHas('funcoes_funcionario', ['id' => $funcao->id]);
    }

    public function test_admin_cannot_delete_tipo_funcionario_when_slots_exist(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();
        $funcao = FuncaoFuncionario::factory()->create(['partner_category_id' => $category->id]);
        $tipo = TipoDocumentoFuncionario::factory()->create(['funcao_funcionario_id' => $funcao->id]);
        Funcionario::factory()->create(['funcao_funcionario_id' => $funcao->id]);

        $this->actingAs($admin)
            ->delete(route('admin.partner-categories.funcoes.documentos.destroy', [$category, $funcao, $tipo]))
            ->assertRedirect()
            ->assertSessionHasErrors([
                'delete' => 'Não é possível excluir: há funcionários utilizando este documento.',
            ]);

        $this->assertDatabaseHas('tipos_documento_funcionario', ['id' => $tipo->id]);
    }

    public function test_admin_can_delete_tipo_funcionario_without_slots(): void
    {
        $admin = User::factory()->admin()->create();
        $category = PartnerCategory::factory()->create();
        $funcao = FuncaoFuncionario::factory()->create(['partner_category_id' => $category->id]);
        $tipo = TipoDocumentoFuncionario::factory()->create(['funcao_funcionario_id' => $funcao->id]);

        $this->actingAs($admin)
            ->delete(route('admin.partner-categories.funcoes.documentos.destroy', [$category, $funcao, $tipo]))
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('tipos_documento_funcionario', ['id' => $tipo->id]);
    }
}
