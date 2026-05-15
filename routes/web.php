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
        Route::get('documentos-empresa', [CategoriaDocumentoEmpresaController::class, 'index'])
            ->name('documentos-empresa.index');
        Route::post('documentos-empresa', [CategoriaDocumentoEmpresaController::class, 'store'])
            ->name('documentos-empresa.store');
        Route::put('documentos-empresa/{tipo_documento_empresa}', [CategoriaDocumentoEmpresaController::class, 'update'])
            ->name('documentos-empresa.update');
        Route::delete('documentos-empresa/{tipo_documento_empresa}', [CategoriaDocumentoEmpresaController::class, 'destroy'])
            ->name('documentos-empresa.destroy');

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

        Route::get('funcoes/{funcao}/documentos', [CategoriaDocumentoFuncionarioController::class, 'index'])
            ->name('funcoes.documentos.index');
        Route::post('funcoes/{funcao}/documentos', [CategoriaDocumentoFuncionarioController::class, 'store'])
            ->name('funcoes.documentos.store');
        Route::put('funcoes/{funcao}/documentos/{tipo_documento_funcionario}', [CategoriaDocumentoFuncionarioController::class, 'update'])
            ->name('funcoes.documentos.update');
        Route::delete('funcoes/{funcao}/documentos/{tipo_documento_funcionario}', [CategoriaDocumentoFuncionarioController::class, 'destroy'])
            ->name('funcoes.documentos.destroy');
    });

    Route::resource('parceiros', ParceiroController::class)
        ->except(['create', 'store'])
        ->parameters(['parceiros' => 'partner']);

    Route::get('parceiros/{partner}/documentos-empresa', [ParceiroDocumentoEmpresaController::class, 'index'])
        ->name('parceiros.documentos-empresa.index');

    Route::get('documentos-empresa/{documento_empresa}/visualizar', [ParceiroDocumentoEmpresaController::class, 'download'])
        ->name('documentos-empresa.download');

    Route::post('documentos-empresa/{documento_empresa}/validar', [ParceiroDocumentoEmpresaController::class, 'validar'])
        ->name('documentos-empresa.validar');

    Route::get('parceiros/{partner}/funcionarios/{funcionario}/documentos', [ParceiroDocumentoFuncionarioController::class, 'index'])
        ->name('parceiros.funcionarios.documentos.index');

    Route::get('documentos-funcionario/{documento_funcionario}/visualizar', [ParceiroDocumentoFuncionarioController::class, 'download'])
        ->name('documentos-funcionario.download');

    Route::post('documentos-funcionario/{documento_funcionario}/validar', [ParceiroDocumentoFuncionarioController::class, 'validar'])
        ->name('documentos-funcionario.validar');

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

    Route::get('empresa/documentos', [EmpresaDocumentoController::class, 'index'])->name('empresa.documentos.index');
    Route::post('empresa/documentos/{documento_empresa}/upload', [EmpresaDocumentoController::class, 'upload'])->name('empresa.documentos.upload');
    Route::get('empresa/documentos/{documento_empresa}/visualizar', [EmpresaDocumentoController::class, 'download'])->name('empresa.documentos.download');

    Route::resource('funcionarios', FuncionarioController::class)->except(['show']);

    Route::get('funcionarios/{funcionario}/documentos', [FuncionarioDocumentoController::class, 'index'])->name('funcionarios.documentos.index');
    Route::post('funcionarios/{funcionario}/documentos/{documento_funcionario}/upload', [FuncionarioDocumentoController::class, 'upload'])->name('funcionarios.documentos.upload');
    Route::get('funcionarios/{funcionario}/documentos/{documento_funcionario}/visualizar', [FuncionarioDocumentoController::class, 'download'])->name('funcionarios.documentos.download');

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
