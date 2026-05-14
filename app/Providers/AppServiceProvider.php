<?php

namespace App\Providers;

use App\Models\DocumentoFuncionario;
use App\Models\Funcionario;
use App\Models\Partner;
use App\Observers\DocumentoFuncionarioObserver;
use App\Observers\FuncionarioObserver;
use App\Observers\PartnerObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Partner::observe(PartnerObserver::class);
        Funcionario::observe(FuncionarioObserver::class);
        DocumentoFuncionario::observe(DocumentoFuncionarioObserver::class);
    }
}
