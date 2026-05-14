<?php

use App\Http\Controllers\Admin\FuncaoFuncionarioController;
use App\Http\Controllers\Admin\PartnerCategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PartnerDocumentoEmpresaController;
use App\Http\Controllers\Admin\PartnerDocumentoFuncionarioController;
use App\Http\Controllers\Admin\TipoDocumentoEmpresaController;
use App\Http\Controllers\Admin\TipoDocumentoFuncionarioController;
use App\Http\Controllers\Parceiro\EmpresaDocumentoController;
use App\Http\Controllers\Parceiro\FuncionarioController;
use App\Http\Controllers\Parceiro\FuncionarioDocumentoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to(request()->user()->dashboardUrl());
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('partner-categories', PartnerCategoryController::class)->except(['show']);

    Route::resource('tipos-documento-empresa', TipoDocumentoEmpresaController::class)
        ->parameters(['tipos-documento-empresa' => 'tipo_documento_empresa'])
        ->except(['show']);

    Route::resource('tipos-documento-funcionario', TipoDocumentoFuncionarioController::class)
        ->parameters(['tipos-documento-funcionario' => 'tipo_documento_funcionario'])
        ->except(['show']);

    Route::resource('funcoes-funcionario', FuncaoFuncionarioController::class)
        ->parameters(['funcoes-funcionario' => 'funcao_funcionario'])
        ->except(['show']);

    Route::resource('parceiros', PartnerController::class)
        ->except(['show', 'create', 'store'])
        ->parameters(['parceiros' => 'partner']);

    Route::get('parceiros/{partner}/documentos-empresa', [PartnerDocumentoEmpresaController::class, 'index'])
        ->name('parceiros.documentos-empresa.index');

    Route::post('documentos-empresa/{documento_empresa}/validar', [PartnerDocumentoEmpresaController::class, 'validar'])
        ->name('documentos-empresa.validar');

    Route::get('parceiros/{partner}/funcionarios/{funcionario}/documentos-funcionario', [PartnerDocumentoFuncionarioController::class, 'index'])
        ->name('parceiros.funcionarios.documentos-funcionario.index');

    Route::post('documentos-funcionario/{documento_funcionario}/validar', [PartnerDocumentoFuncionarioController::class, 'validar'])
        ->name('documentos-funcionario.validar');
});

Route::middleware(['auth', 'verified', 'partner'])->prefix('parceiro')->name('parceiro.')->group(function () {
    Route::get('/dashboard', function () {
        return view('parceiro.dashboard');
    })->name('dashboard');

    Route::get('empresa/documentos', [EmpresaDocumentoController::class, 'index'])->name('empresa.documentos.index');
    Route::post('empresa/documentos/{documento_empresa}/upload', [EmpresaDocumentoController::class, 'upload'])->name('empresa.documentos.upload');
    Route::get('empresa/documentos/{documento_empresa}/download', [EmpresaDocumentoController::class, 'download'])->name('empresa.documentos.download');

    Route::resource('funcionarios', FuncionarioController::class)->except(['show']);

    Route::get('funcionarios/{funcionario}/documentos', [FuncionarioDocumentoController::class, 'index'])->name('funcionarios.documentos.index');
    Route::post('funcionarios/{funcionario}/documentos/{documento_funcionario}/upload', [FuncionarioDocumentoController::class, 'upload'])->name('funcionarios.documentos.upload');
    Route::get('funcionarios/{funcionario}/documentos/{documento_funcionario}/download', [FuncionarioDocumentoController::class, 'download'])->name('funcionarios.documentos.download');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
