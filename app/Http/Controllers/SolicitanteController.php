<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Notifications\CompraCorreccionNotificacion;
use App\Notifications\RevisadaNotificacion;
Use App\Notifications\compraCorregidaNoticacion;

class SolicitanteController extends Controller
{
    // Funcion para revisar OC
    public function revisarOC($id)
{
    $producto = Productos::findOrFail($id);

    // Verificar si el usuario actual es el solicitante y si la OC está en el estado adecuado
    if (auth()->id() !== $producto->usuario_solicitante_id || $producto->estado_oc !== 'OC creada') {
        abort(403, 'No tienes permiso para revisar esta OC.');
    }

    // Cambiar el estado de la OC
    $producto->estado_oc = 'Revisado OC';
    $producto->save();

    // Notificar al aprobador
    $producto->aprobador->notify(new RevisadaNotificacion($producto));

    return redirect()->route('solicitante.compras')->with('success', 'OC marcada como revisada.');
}

// Funcion para traer las compras del solicitante
public function misCompras()
{
        // Obtener el usuario autenticado
        $usuarioActual = auth()->user();

    // Obtener todas las compras del usuario solicitante donde estado_proyecto sea 'VIGENTE'
    $productos = Productos::where('usuario_solicitante_id', $usuarioActual->id)
        ->where('estado_solicitud', 'vigente') // Filtrar productos con estado_proyecto 'VIGENTE'
        ->orderBy('created_at', 'desc')
        ->get();

    return view('solicitante.compras', compact('productos'));
}


// Funcion para compra corregida
public function compraCorreccion($id)
{
    $producto = Productos::findOrFail($id);

    // Verifica que el usuario actual sea el solicitante y que el estado sea "Compra corrección"
    if (auth()->id() !== $producto->usuario_solicitante_id || $producto->estado_oc !== 'Compra corrección') {
        abort(403, 'No tienes permiso para realizar esta acción.');
    }

    // Cambia el estado a "Compra corregida"
    $producto->estado_oc = 'Compra corregida';
    $producto->save();

    // Envía una notificación al aprobador
    $producto->aprobador->notify(new compraCorregidaNoticacion($producto));

    return redirect()->route('solicitante.compras')->with('success', 'La corrección de la compra se ha realizado con éxito.');
}



}
