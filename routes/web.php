<?php

use App\Http\Controllers\Admin\CategoriaDocumentoEmpresaController;
use App\Http\Controllers\Admin\CategoriaDocumentoFuncionarioController;
use App\Http\Controllers\Admin\CategoriaFuncaoController;
use App\Http\Controllers\Admin\CategoriaParceiroController;
use App\Http\Controllers\Admin\ConviteController;
use App\Http\Controllers\Admin\ParceiroController;
use App\Http\Controllers\Admin\ParceiroDocumentoEmpresaController;
use App\Http\Controllers\Admin\ParceiroDocumentoFuncionarioController;
use App\Http\Controllers\Parceiro\EmpresaDocumentoController;
use App\Http\Controllers\Parceiro\FilialController;
use App\Http\Controllers\Parceiro\FuncionarioController;
use App\Http\Controllers\Parceiro\FuncionarioDocumentoController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\RegistroConviteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to(request()->user()->dashboardUrl());
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/convite/{token}', [RegistroConviteController::class, 'show'])->name('invitation.show');
Route::post('/convite/{token}', [RegistroConviteController::class, 'store'])->name('invitation.store');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categorias', CategoriaParceiroController::class)
        ->parameters(['categorias' => 'partner_category']);

    Route::prefix('categorias/{partner_category}')->name('categorias.')->group(function () {
        // Tipos de documento da empresa (na categoria)
        Route::get('docs', [CategoriaDocumentoEmpresaController::class, 'index'])
            ->name('docs.index');
        Route::post('docs', [CategoriaDocumentoEmpresaController::class, 'store'])
            ->name('docs.store');
        Route::put('docs/{tipo_documento_empresa}', [CategoriaDocumentoEmpresaController::class, 'update'])
            ->name('docs.update');
        Route::delete('docs/{tipo_documento_empresa}', [CategoriaDocumentoEmpresaController::class, 'destroy'])
            ->name('docs.destroy');

        // Funções da categoria
        Route::get('funcoes', [CategoriaFuncaoController::class, 'index'])
            ->name('funcoes.index');
        Route::post('funcoes', [CategoriaFuncaoController::class, 'store'])
            ->name('funcoes.store');
        Route::get('funcoes/{funcao}/edit', [CategoriaFuncaoController::class, 'edit'])
            ->name('funcoes.edit');
        Route::put('funcoes/{funcao}', [CategoriaFuncaoController::class, 'update'])
            ->name('funcoes.update');
        Route::delete('funcoes/{funcao}', [CategoriaFuncaoController::class, 'destroy'])
            ->name('funcoes.destroy');

        // Tipos de documento dos funcionários (na função)
        Route::get('funcoes/{funcao}/docs', [CategoriaDocumentoFuncionarioController::class, 'index'])
            ->name('funcoes.docs.index');
        Route::post('funcoes/{funcao}/docs', [CategoriaDocumentoFuncionarioController::class, 'store'])
            ->name('funcoes.docs.store');
        Route::put('funcoes/{funcao}/docs/{tipo_documento_funcionario}', [CategoriaDocumentoFuncionarioController::class, 'update'])
            ->name('funcoes.docs.update');
        Route::delete('funcoes/{funcao}/docs/{tipo_documento_funcionario}', [CategoriaDocumentoFuncionarioController::class, 'destroy'])
            ->name('funcoes.docs.destroy');
    });

    Route::resource('parceiros', ParceiroController::class)
        ->except(['create', 'store'])
        ->parameters(['parceiros' => 'partner']);

    // Documentos da empresa do parceiro
    Route::get('parceiros/{partner}/docs', [ParceiroDocumentoEmpresaController::class, 'index'])
        ->name('parceiros.docs.index');

    // Visualizar / validar documento de empresa
    Route::get('docs/{documento_empresa}', [ParceiroDocumentoEmpresaController::class, 'download'])
        ->name('docs.download');
    Route::post('docs/{documento_empresa}/validar', [ParceiroDocumentoEmpresaController::class, 'validar'])
        ->name('docs.validar');

    // Documentos dos funcionários do parceiro
    Route::get('parceiros/{partner}/funcionarios/{funcionario}/docs', [ParceiroDocumentoFuncionarioController::class, 'index'])
        ->name('parceiros.funcionarios.docs.index');

    // Visualizar / validar documento de funcionário
    Route::get('docs-func/{documento_funcionario}', [ParceiroDocumentoFuncionarioController::class, 'download'])
        ->name('docs-func.download');
    Route::post('docs-func/{documento_funcionario}/validar', [ParceiroDocumentoFuncionarioController::class, 'validar'])
        ->name('docs-func.validar');

    Route::resource('convites', ConviteController::class)
        ->only(['index', 'create', 'store', 'destroy'])
        ->parameters(['convites' => 'invitation'])
        ->names([
            'index' => 'invitations.index',
            'create' => 'invitations.create',
            'store' => 'invitations.store',
            'destroy' => 'invitations.destroy',
        ]);
});

Route::middleware(['auth', 'verified', 'partner', 'set.current.partner'])->prefix('parceiro')->name('parceiro.')->group(function () {
    Route::get('/dashboard', function () {
        return view('parceiro.dashboard');
    })->name('dashboard');

    // Documentos da empresa
    Route::get('docs', [EmpresaDocumentoController::class, 'index'])->name('docs.index');
    Route::post('docs/{documento_empresa}/upload', [EmpresaDocumentoController::class, 'upload'])->name('docs.upload');
    Route::get('docs/{documento_empresa}', [EmpresaDocumentoController::class, 'download'])->name('docs.download');

    Route::resource('funcionarios', FuncionarioController::class)->except(['show']);

    // Documentos dos funcionários
    Route::get('funcionarios/{funcionario}/docs', [FuncionarioDocumentoController::class, 'index'])->name('funcionarios.docs.index');
    Route::post('funcionarios/{funcionario}/docs/{documento_funcionario}/upload', [FuncionarioDocumentoController::class, 'upload'])->name('funcionarios.docs.upload');
    Route::get('funcionarios/{funcionario}/docs/{documento_funcionario}', [FuncionarioDocumentoController::class, 'download'])->name('funcionarios.docs.download');

    Route::resource('filiais', FilialController::class)
        ->except(['show', 'destroy'])
        ->parameters(['filiais' => 'partner']);
    Route::post('filiais/{partner}/switch', [FilialController::class, 'switch'])->name('filiais.switch');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rota de visualização de arquivos — deve ficar por último para não conflitar
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/{filename}', [ArquivoController::class, 'servir'])
        ->where('filename', '[A-Z0-9][^/]+\.[a-zA-Z0-9]+')
        ->name('arquivo.visualizar');
});

require __DIR__.'/auth.php';
