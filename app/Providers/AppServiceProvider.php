<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $notificacionesPendientes = Auth::user()->unreadNotifications->count();
                // Configurar en tiempo de ejecución
                Config::set('adminlte.notificacionesPendientes', $notificacionesPendientes);
            }
        });
    }
}
