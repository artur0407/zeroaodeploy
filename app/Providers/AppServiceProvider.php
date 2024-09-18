<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

/**
 * Services são usados para acesso a um serviço externo
 */

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
        // criando função customizada que será usada no PunkApiService.php
        Http::macro('punkapi', function() {
            //tudo que esta dentro da pasta config
            return Http::acceptJson()
                ->baseUrl(config('punkapi.url'))
                ->retry(3, 100); // busca arquivo punkiapi.php dentro de config
        });
    }
}
