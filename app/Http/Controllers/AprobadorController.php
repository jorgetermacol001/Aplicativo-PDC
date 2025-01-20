<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;  
use App\Notifications\CompraAprobadaNotificacion;
use App\Notifications\CompraRechazadaNotificacion;
use App\Notifications\LiberarOcNotificacion;
use app\Notifications\revisarOCNoticacion;
use App\Notifications\CompraCorreccionNotificacion;
use App\Notifications\PagoLiberadoNotificacion;

class AprobadorController extends Controller
{
    public function pendientes()
    {
        $productos = Productos::where('usuario_aprobador_id', auth()->id())
            ->where('estado_solicitud', 'vigente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        return view('aprobador.pendientes', compact('productos'));
    }
    
    
    public function show($id)
    {
        // Encuentra el producto pendiente específico
        $producto = Productos::findOrFail($id);

        // Verifica que el producto esté pendiente y asignado al aprobador actual
        if ($producto->usuario_aprobador_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver esta compra.');
        }

        return view('aprobador.detalle', compact('producto'));
    }

    public function aprobar($id)
    {
        $producto = Productos::findOrFail($id);
    
        // Verifica que el producto esté en estado 'Compra corregida' o "Compra solicitada" o "Corrección O.C" y que esté asignado al aprobador actual
        if (
            !in_array($producto->estado_oc, ['Compra solicitada', 'OC corrección', 'Compra corregida']) ||
            $producto->usuario_aprobador_id !== auth()->id()
        ) {
            abort(403, 'No tienes permiso para aprobar esta compra.');
        }
    
        // Cambia el estado a "Compra aprobada"
        $producto->estado_oc = 'Compra aprobada';

        // Agrega la fecha de aprobacion 
        $producto->fecha_aprobacion_oc = now();

        $producto->save();
    
        // Envía una notificación al solicitante
        $producto->solicitante->notify(new CompraAprobadaNotificacion($producto));

        // Envía una notificación al solicitante
        $producto->adminCompra->notify(new CompraAprobadaNotificacion($producto));
    
        return redirect()->route('aprobador.pendientes')->with('success', 'Compra aprobada con éxito.');
    }
    

    public function rechazar($id)
    {
        $producto = Productos::findOrFail($id);

        // Verifica que el producto esté pendiente y asignado al aprobador actual
        if ($producto->estado_oc !== 'Compra solicitada' || $producto->usuario_aprobador_id !== auth()->id()) {
            abort(403, 'No tienes permiso para rechazar esta compra.');
        }

        $producto->estado_oc='Rechazada'; // Cambia el estado a "Rechazada"
        $producto->save();

        // Envía una notificación al solicitante
        $producto->solicitante->notify(new CompraRechazadaNotificacion($producto));

        return redirect()->route('aprobador.pendientes')->with('success', 'Compra rechazada con éxito.');
    }



    // Funcion para liberar OC
    public function liberarOC($id)
    {
        // Encuentra el producto por ID
        $producto = Productos::findOrFail($id);

        // Verifica que el usuario actual es el aprobador y que el estado es correcto
        if (auth()->id() !== $producto->usuario_aprobador_id || $producto->estado_oc !== 'Revisado OC') {
            abort(403, 'No tienes permiso para liberar esta OC.');
        }

        // Cambia el estado de la OC a "OC Liberada"
        $producto->estado_oc = 'OC Liberada';
        $producto->save();

    
        // Notifica al Administrador de Compra
        $producto->adminCompra->notify(new LiberarOcNotificacion($producto));

        // Redirige con mensaje de éxito
        return redirect()->route('aprobador.pendientes')->with('success', 'OC liberada con éxito.');
    }


    // Funcion para Revisar OC 
    public function revisar($id)
    {
        $producto = Productos::findOrFail($id);

        // Verifica que el producto esté pendiente y asignado al aprobador actual
        if ($producto->estado_oc !== 'OC corrección' || $producto->usuario_aprobador_id !== auth()->id()) {
            abort(403, 'No tienes permiso para aprobar esta compra.');
        }

        $producto->estado_oc = 'Compra aprobada'; // Cambia el estado a "Aprobada"
        $producto->save();

        // Envía una notificación al solicitante
        $producto->solicitante->notify(new revisarOCNoticacion($producto));

        return redirect()->route('aprobador.pendientes')->with('success', 'Compra revisada con exito con éxito.');
    }


    // Funcion para compra CompraCorreccion 
    public function compraCorreccion($id)
    {
        $producto = Productos::findOrFail($id);

        // Verifica que el producto esté pendiente y asignado al aprobador actual
        if ($producto->estado_oc !== 'Compra solicitada' || $producto->usuario_aprobador_id !== auth()->id()) {
            abort(403, 'No tienes permiso para aprobar esta compra.');
        }

        $producto->estado_oc = 'Compra corrección'; // Cambia el estado a "Aprobada"
        $producto->save();

        // Envía una notificación al solicitante
        $producto->solicitante->notify(new CompraCorreccionNotificacion($producto));

        return redirect()->route('aprobador.pendientes')->with('success', 'Compra revisada con exito con éxito.');
    }


        // Funcion para compra CompraCorreccion 
        public function PagoLiberado($id)
        {
            $producto = Productos::findOrFail($id);
    
            // Verifica que el producto esté pendiente y asignado al aprobador actual
            if ($producto->estado_pago !== 'pago programado' || $producto->usuario_aprobador_id !== auth()->id()) {
                abort(403, 'No tienes permiso para aprobar esta compra.');
            }
    
            $producto->estado_pago = 'pago liberado'; // Cambia el estado a "Aprobada"
            $producto->save();
    
            // Envía una notificación al administardor de compra
            $producto->adminCompra->notify(new PagoLiberadoNotificacion($producto));
    
            return redirect()->route('aprobador.pendientes')->with('success', 'Compra revisada con exito con éxito.');
        }
    
}

?>