<?php

namespace App\Support;

use App\Models\Funcionario;
use App\Models\Partner;
use App\Models\PartnerCategory;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class BreadcrumbTrail
{
    /**
     * @return list<array{label: string, url: string|null}>
     */
    public static function forCurrentRoute(): array
    {
        $name = RouteFacade::currentRouteName();

        if ($name === null) {
            return [];
        }

        $route = RouteFacade::current();

        if ($name === 'admin.dashboard') {
            return [['label' => 'Painel administrativo', 'url' => null]];
        }

        if ($name === 'parceiro.dashboard') {
            return [['label' => 'Início', 'url' => null]];
        }

        $trail = self::root($name);

        $segments = self::segmentsFor($name, $route);

        foreach ($segments as $segment) {
            $trail[] = $segment;
        }

        if ($trail !== []) {
            $last = array_key_last($trail);
            $trail[$last]['url'] = null;
        }

        return $trail;
    }

    /**
     * @return list<array{label: string, url: string|null}>
     */
    private static function root(string $name): array
    {
        if (str_starts_with($name, 'admin.')) {
            return [['label' => 'Painel administrativo', 'url' => route('admin.dashboard')]];
        }

        if (str_starts_with($name, 'parceiro.')) {
            return [['label' => 'Início', 'url' => route('parceiro.dashboard')]];
        }

        if ($name === 'perfil.edit' || $name === 'perfil.update') {
            $user = auth()->user();

            if ($user?->isBackOffice()) {
                return [['label' => 'Painel administrativo', 'url' => route('admin.dashboard')]];
            }

            return [['label' => 'Início', 'url' => route('parceiro.dashboard')]];
        }

        return [];
    }

    /**
     * @return list<array{label: string, url: string|null}>
     */
    private static function segmentsFor(string $name, ?Route $route): array
    {
        $partner = $route?->parameter('partner');
        $partner = $partner instanceof Partner ? $partner : null;

        $funcionario = $route?->parameter('funcionario');
        $funcionario = $funcionario instanceof Funcionario ? $funcionario : null;

        $category = $route?->parameter('partner_category');
        $category = $category instanceof PartnerCategory ? $category : null;

        return match ($name) {
            'admin.documentos.pendentes' => [
                ['label' => 'Documentos pendentes', 'url' => route('admin.documentos.pendentes')],
            ],

            'admin.parceiros.index' => [
                ['label' => 'Parceiros', 'url' => route('admin.parceiros.index')],
            ],
            'admin.parceiros.show' => [
                ['label' => 'Parceiros', 'url' => route('admin.parceiros.index')],
                ['label' => $partner?->razao_social ?? 'Parceiro', 'url' => $partner ? route('admin.parceiros.show', $partner) : null],
            ],
            'admin.parceiros.edit' => [
                ['label' => 'Parceiros', 'url' => route('admin.parceiros.index')],
                ['label' => $partner?->razao_social ?? 'Parceiro', 'url' => $partner ? route('admin.parceiros.show', $partner) : null],
                ['label' => 'Editar', 'url' => $partner ? route('admin.parceiros.edit', $partner) : null],
            ],
            'admin.parceiros.docs.index' => [
                ['label' => 'Parceiros', 'url' => route('admin.parceiros.index')],
                ['label' => $partner?->razao_social ?? 'Parceiro', 'url' => $partner ? route('admin.parceiros.show', $partner) : null],
                ['label' => 'Documentos da empresa', 'url' => $partner ? route('admin.parceiros.docs.index', $partner) : null],
            ],
            'admin.parceiros.funcionarios.docs.index' => [
                ['label' => 'Parceiros', 'url' => route('admin.parceiros.index')],
                ['label' => $partner?->razao_social ?? 'Parceiro', 'url' => $partner ? route('admin.parceiros.show', $partner) : null],
                ['label' => $funcionario?->nome ?? 'Funcionário', 'url' => ($partner && $funcionario) ? route('admin.parceiros.funcionarios.docs.index', [$partner, $funcionario]) : null],
                ['label' => 'Documentos do funcionário', 'url' => ($partner && $funcionario) ? route('admin.parceiros.funcionarios.docs.index', [$partner, $funcionario]) : null],
            ],

            'admin.categorias.index' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
            ],
            'admin.categorias.create' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
                ['label' => 'Nova categoria', 'url' => route('admin.categorias.create')],
            ],
            'admin.categorias.show' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
                ['label' => $category?->nome ?? 'Categoria', 'url' => $category ? route('admin.categorias.show', $category) : null],
            ],
            'admin.categorias.edit' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
                ['label' => $category?->nome ?? 'Categoria', 'url' => $category ? route('admin.categorias.show', $category) : null],
                ['label' => 'Editar', 'url' => $category ? route('admin.categorias.edit', $category) : null],
            ],
            'admin.categorias.docs.index' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
                ['label' => $category?->nome ?? 'Categoria', 'url' => $category ? route('admin.categorias.show', $category) : null],
                ['label' => 'Documentos da empresa', 'url' => $category ? route('admin.categorias.docs.index', $category) : null],
            ],
            'admin.categorias.funcoes.index' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
                ['label' => $category?->nome ?? 'Categoria', 'url' => $category ? route('admin.categorias.show', $category) : null],
                ['label' => 'Funções', 'url' => $category ? route('admin.categorias.funcoes.index', $category) : null],
            ],
            'admin.categorias.funcoes.edit' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
                ['label' => $category?->nome ?? 'Categoria', 'url' => $category ? route('admin.categorias.show', $category) : null],
                ['label' => 'Funções', 'url' => $category ? route('admin.categorias.funcoes.index', $category) : null],
                ['label' => 'Editar função', 'url' => null],
            ],
            'admin.categorias.funcoes.docs.index' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
                ['label' => $category?->nome ?? 'Categoria', 'url' => $category ? route('admin.categorias.show', $category) : null],
                ['label' => 'Funções', 'url' => $category ? route('admin.categorias.funcoes.index', $category) : null],
                ['label' => 'Documentos da função', 'url' => null],
            ],
            'admin.categorias.funcoes.docs.edit' => [
                ['label' => 'Categorias', 'url' => route('admin.categorias.index')],
                ['label' => $category?->nome ?? 'Categoria', 'url' => $category ? route('admin.categorias.show', $category) : null],
                ['label' => 'Funções', 'url' => $category ? route('admin.categorias.funcoes.index', $category) : null],
                ['label' => 'Editar documento', 'url' => null],
            ],

            'admin.invitations.index' => [
                ['label' => 'Convites', 'url' => route('admin.invitations.index')],
            ],
            'admin.invitations.create' => [
                ['label' => 'Convites', 'url' => route('admin.invitations.index')],
                ['label' => 'Novo convite', 'url' => route('admin.invitations.create')],
            ],

            'parceiro.docs.index' => [
                ['label' => 'Documentos', 'url' => route('parceiro.docs.index')],
                ['label' => 'Empresa', 'url' => route('parceiro.docs.index')],
            ],
            'parceiro.docs.funcionarios' => [
                ['label' => 'Documentos', 'url' => route('parceiro.docs.index')],
                ['label' => 'Funcionários', 'url' => route('parceiro.docs.funcionarios')],
            ],
            'parceiro.funcionarios.index' => [
                ['label' => 'Funcionários', 'url' => route('parceiro.funcionarios.index')],
            ],
            'parceiro.funcionarios.create' => [
                ['label' => 'Funcionários', 'url' => route('parceiro.funcionarios.index')],
                ['label' => 'Novo funcionário', 'url' => route('parceiro.funcionarios.create')],
            ],
            'parceiro.funcionarios.edit' => [
                ['label' => 'Funcionários', 'url' => route('parceiro.funcionarios.index')],
                ['label' => 'Editar', 'url' => null],
            ],
            'parceiro.funcionarios.docs.index' => [
                ['label' => 'Documentos', 'url' => route('parceiro.docs.index')],
                ['label' => 'Funcionários', 'url' => route('parceiro.docs.funcionarios')],
                ['label' => $funcionario?->nome ?? 'Funcionário', 'url' => $funcionario ? route('parceiro.funcionarios.docs.index', $funcionario) : null],
            ],
            'parceiro.filiais.index' => [
                ['label' => 'Filiais', 'url' => route('parceiro.filiais.index')],
            ],
            'parceiro.filiais.create' => [
                ['label' => 'Filiais', 'url' => route('parceiro.filiais.index')],
                ['label' => 'Nova filial', 'url' => route('parceiro.filiais.create')],
            ],
            'parceiro.filiais.edit' => [
                ['label' => 'Filiais', 'url' => route('parceiro.filiais.index')],
                ['label' => 'Editar filial', 'url' => null],
            ],

            'perfil.edit', 'perfil.update' => [
                ['label' => 'Perfil', 'url' => route('perfil.edit')],
            ],

            default => [],
        };
    }
}
