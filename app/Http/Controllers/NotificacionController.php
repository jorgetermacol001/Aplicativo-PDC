<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        // Filtra las notificaciones con estado 'Pendiente'
        $notificaciones = auth()->user()->notifications()->where('estado', 'Pendiente')->get();
    
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarComoLeida($id)
    {
        // Encuentra la notificación por su ID y verifica que pertenezca al usuario autenticado
        $notificacion = auth()->user()->notifications()->findOrFail($id);
    
        // Marca la notificación como leída
        $notificacion->markAsRead();
    
        // Cambia el estado de la notificación
        $notificacion->estado = 'Leída';
        $notificacion->save();
    
        // Redirecciona a la vista de notificaciones con un mensaje de éxito
        return redirect()->route('notificacion.index')->with('success', 'Notificación marcada como leída.');
    }
    

    public function getNotificationsData(Request $request)
    {
        // Filtra notificaciones con estado 'Pendiente' o sin leer
        $notifications = auth()->user()->notifications()
            ->whereNull('read_at') // O ajusta según tu lógica
            ->latest()
            ->get();
    
        // Si no hay notificaciones recientes
        if ($notifications->isEmpty()) {
            return [
                'label' => 0, // Sin notificaciones
                'label_color' => 'success',
                'icon_color' => 'dark',
                'dropdown' => '<a href="#" class="dropdown-item">No new notifications</a>',
            ];
        }
    
        // Genera el HTML para mostrar las notificaciones
        $dropdownHtml = '';
        foreach ($notifications as $notification) {
            $data = $notification->data; // Obtén los datos desde la base de datos
            $icon = "<i class='mr-2 fas fa-bell'></i>"; // Puedes ajustar este ícono si lo necesitas
            $time = "<span class='float-right text-muted text-sm'>{$notification->created_at->diffForHumans()}</span>";

            // Usa las claves existentes como 'mensaje' para generar el HTML
            $dropdownHtml .= "<a href='#' class='dropdown-item'>{$icon} {$data['mensaje']} {$time}</a>";
            $dropdownHtml .= "<div class='dropdown-divider'></div>";
        }

        // Retorna la cantidad de notificaciones y el contenido para el menú
        return [
            'label' => $notifications->count(),
            'label_color' => 'danger',
            'icon_color' => 'dark',
            'dropdown' => $dropdownHtml,
        ];
    }
    
    
}
