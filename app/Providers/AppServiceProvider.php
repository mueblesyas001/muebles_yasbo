<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        View::composer('calendario.index', function ($view) {
            $view->with('helpers', new class {
                public function getBadgeColor($estado)
                {
                    return match(strtolower($estado)) {
                        'pendiente' => 'warning',
                        'confirmado' => 'info',
                        'en proceso', 'en_proceso' => 'primary',
                        'completado', 'entregado' => 'success',
                        'cancelado' => 'danger',
                        default => 'secondary'
                    };
                }

                public function getColorPrioridad($prioridad)
                {
                    return match($prioridad) {
                        'alta' => '#dc3545',
                        'media' => '#ffc107',
                        'baja' => '#28a745',
                        default => '#007bff'
                    };
                }
            });
        });
    }
}