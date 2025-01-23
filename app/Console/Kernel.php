<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Programar el comando para que se ejecute todos los lunes a las 8:00 AM
        $schedule->command('notificar:almacenista')
                ->mondays()
                ->at('08:00')
                ->when(function () {
                    // Condiciones personalizadas antes de ejecutar el comando
                    return \App\Models\Productos::where('estado_entrega', 'entrega parcial')
                                                ->where('estado_proyecto', 'vigente')
                                                ->exists();
                });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
