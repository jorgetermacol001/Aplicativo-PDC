<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $notificacionesPendientes = Auth::user()->unreadNotifications->count();
                $menu = [
                    [
                        'text' => 'Notificaciones',
                        'url' => '/notificaciones',
                        'icon' => 'far fa-fw fa-bell',
                        'label' => $notificacionesPendientes,
                        'label_color' => 'success',
                    ],
                    // Agrega otros elementos de menú aquí si es necesario
                ];

                $view->with('menu', $menu);
            }
        });
    }
}
