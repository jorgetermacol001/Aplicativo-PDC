<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Productos;// Modelo de productos
use App\Models\User; // Modelo de usuarios
use Illuminate\Support\Facades\Notification;
use App\Notifications\RecordatorioEntregaParcial; // Tu notificación personalizada

class NotificarAlmacenista extends Command
{
    protected $signature = 'notificar:almacenista';
    protected $description = 'Envía un recordatorio diario al almacenista sobre productos con entrega parcial';

    public function handle()
    {
        // Obtener productos con estado_entrega = "entrega parcial" y estado_proyecto = "vigente"
        $productos = Productos::where('estado_entrega', 'entrega parcial')
            ->where('estado_proyecto', 'vigente')
            ->get();

        if ($productos->isEmpty()) {
            $this->info('No hay productos para notificar.');
            return 0;
        }

        // Obtener almacenistas (usuarios con rol de almacenista)
        $almacenistas = User::role('almacenista')->get(); // Si usas Spatie Roles

        foreach ($almacenistas as $almacenista) {
            foreach ($productos as $producto) {
                // Enviar la notificación
                Notification::send($almacenista, new RecordatorioEntregaParcial($producto));
            }
        }

        $this->info('Notificaciones enviadas exitosamente.');
        return 0;
    }
}
