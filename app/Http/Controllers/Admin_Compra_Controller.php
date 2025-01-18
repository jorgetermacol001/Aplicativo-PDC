<?php

namespace App\Http\Controllers;

use AlmacenNotificacion as GlobalAlmacenNotificacion;
use Illuminate\Http\Request;
use App\Models\Productos;
use App\Notifications\CrearOcNotificacion;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;
use App\Notifications\EnviarOcNotificacion;
use App\Notifications\correcionOCNoticacion;
use App\Notifications\ComfirmarOcNotificacion;
use App\Notifications\PagoProgramadoNotificacion;
use App\Notifications\AlmacenistaNotificacion;

class Admin_Compra_Controller extends Controller
{

    // Funcion para enviar la OC 
    public function enviarOC($id)
    {
        $producto = Productos::findOrFail($id);
    
        // Verificar si el usuario actual es el administrador de compras y si la OC está liberada
        if (auth()->id() !== $producto->usuario_admin_compra_id || $producto->estado_oc !== 'OC liberada') {
            abort(403, 'No tienes permiso para enviar esta OC.');
        }
    
        // Cambiar el estado de la OC
        $producto->estado_oc = 'OC Enviada';
    
        // Capturar la fecha actual y guardarla en el campo fecha_envio_oc
        $producto->fecha_envio_oc = now();
    
        // Guardar los cambios
        $producto->save();
    
        // Notificar al solicitante
        $producto->solicitante->notify(new EnviarOcNotificacion($producto));
    
        // Notificar al aprobador
        $producto->aprobador->notify(new EnviarOcNotificacion($producto));
    
        return redirect()->route('admin_compra.index')->with('success', 'OC enviada con éxito.');
    }
    
public function crearOC($id)
{
    // Encuentra el producto por ID
    $producto = Productos::findOrFail($id);

    // Verifica que el usuario actual es el Administrador de Compra
    if (auth()->id() !== $producto->usuario_admin_compra_id || $producto->estado_oc !== 'Compra aprobada') {
        abort(403, 'No tienes permiso para crear esta OC.');
    }

    // Cambia el estado de la OC a "OC creada"
    $producto->estado_oc = 'OC creada';
    $producto->save();

    // Notifica al Aprobador sobre el cambio
    $producto->aprobador->notify(new CrearOcNotificacion($producto));

    // Redirige con mensaje de éxito
    return redirect()->route('admin_compra.index')->with('success', 'OC creada con éxito.');
}


public function correccionOC($id)
{
    // Encuentra el producto por ID
    $producto = Productos::findOrFail($id);

    // Verifica que el usuario actual es el Administrador de Compra
    if (auth()->id() !== $producto->usuario_admin_compra_id || $producto->estado_oc !== 'Compra aprobada') {
        abort(403, 'No tienes permiso para crear esta OC.');
    }

    // Cambia el estado de la OC a "OC creada"
    $producto->estado_oc = 'OC corrección';
    $producto->save();

    // Notifica al Aprobador sobre el cambio
    $producto->aprobador->notify(new correcionOCNoticacion($producto));

    // Redirige con mensaje de éxito
    return redirect()->route('admin_compra.index')->with('success', 'OC correcion con éxito.');
}



public function indexAdmin()
{
    // Obtiene el usuario autenticado
    $usuarioActual = auth()->user();

    // Verifica si el usuario tiene el rol de Administrador de Compras
    if (!$usuarioActual->hasRole('administrador_compra')) {
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }

    // Estados que maneja el Administrador de Compras
    $estadosPermitidos = [
        'Pendiente OC',
        'Compra aprobada',
        'OC creada',
        'OC liberada',
        'OC enviada',
        'OC confirmada',
        'pago pendiente',
        'pago programado',
        'pago liberado',
        'CP enviado',
        'contado y credito',
        'Pendiente de entrega',
        'Entrega parcial'
    ];

    // Filtra los productos según los estados permitidos y asignados al usuario
    $productos = Productos::
        where('usuario_admin_compra_id', $usuarioActual->id)
        ->where('estado_solicitud','vigente')
        ->orderBy('created_at', 'desc')
        ->get();

    // Retorna la vista con los productos
    return view('admin_compras.pendientes', compact('productos'));
}


public function show($id)
{
    // Encuentra el producto por ID
    $producto = Productos::findOrFail($id);

    // Verifica si el usuario actual tiene el rol adecuado para ver este detalle
    if (auth()->id() !== $producto->usuario_admin_compra_id && !auth()->user()->hasRole('administrador_compra')) {
        abort(403, 'No tienes permiso para ver los detalles de este producto.');
    }

    // Retorna la vista con los detalles del producto
    return view('admin_compras.show', compact('producto'));
}


    // Funcion para Comfirmada la OC 
public function ComfirmadaOC($id)
{
    $producto = Productos::findOrFail($id);

    // Verificar si el usuario actual es el administrador de compras y si la OC está liberada
    if (auth()->id() !== $producto->usuario_admin_compra_id || $producto->estado_oc !== 'OC enviada') {
        abort(403, 'No tienes permiso para enviar esta OC.');
    }

    if($producto->tipo_de_pago === 'contado'|| $producto->estado_oc !== 'OC enviada' ){

            // Cambiar el estado de la OC
            $producto->estado_oc = 'OC confirmada';

            $producto->estado_entrega = 'entrega pendiente'; // Cambiar el estado de entrega
            $producto->save();

            // Notificar al almacenista
            $producto->almacenista->notify(new AlmacenistaNotificacion($producto));

            // Notificar al aprobador
            $producto->aprobador->notify(new ComfirmarOcNotificacion($producto));

            $producto->solicitante->notify(new AlmacenistaNotificacion($producto));

            return redirect()->route('admin_compra.index')->with('success', 'OC enviada con éxito.');

    }elseif($producto->tipo_de_pago === 'contado y credito' || $producto->estado_oc !== 'OC enviada'){

        $producto->estado_oc = 'OC confirmada';

        $producto->estado_pago = 'pago pendiente'; // Cambia el esatdo del pago
        $producto->save();

        // Notificar al aprobador
        $producto->aprobador->notify(new ComfirmarOcNotificacion($producto));

        $producto->solicitante->notify(new AlmacenistaNotificacion($producto));

    }elseif($producto->tipo_de_pago === 'credito'|| $producto->estado_oc !== 'OC enviada'){
        
            // Cambiar el estado de la OC
            $producto->estado_oc = 'OC confirmada';

            $producto->estado_pago = 'pago pendiente'; // Cambia el esatdo del pago
            $producto->save();

            // Notificar al aprobador
            $producto->aprobador->notify(new ComfirmarOcNotificacion($producto));

            $producto->solicitante->notify(new AlmacenistaNotificacion($producto));

            return redirect()->route('admin_compra.index')->with('success', 'OC enviada con éxito.');       
    }else {

        abort(403, 'No tienes permiso para enviar esta OC.');
        
    }

}

public function  PagoProgramado($id)
{
    $producto = Productos::findOrFail($id);

    // Verificar si el usuario actual es el administrador de compras y si la OC está liberada
    if (auth()->id() !== $producto->usuario_admin_compra_id || $producto->estado_pago !== 'pago pendiente') {
        abort(403, 'No tienes permiso para enviar esta OC.');
    }

    // Cambiar el estado de la OC
    $producto->estado_pago = 'pago programado';
    $producto->save();

    // Notifica al Aprobador sobre el cambio
    $producto->aprobador->notify(new PagoProgramadoNotificacion($producto));

    return redirect()->route('admin_compra.index')->with('success', 'OC enviada con éxito.');
}

public function  EnviarCP($id)
{
    $producto = Productos::findOrFail($id);

    // Verificar si el usuario actual es el administrador de compras y si la OC está liberada
    if (auth()->id() !== $producto->usuario_admin_compra_id || $producto->estado_pago !== 'pago liberado') {
        abort(403, 'No tienes permiso para enviar esta OC.');
    }

    // Cambiar el estado de la OC
    $producto->estado_cp = 'CP enviado';
    $producto->estado_entrega = 'entrega pendiente';
    $producto->save();

    $producto->almacenista->notify(new AlmacenistaNotificacion($producto));


    return redirect()->route('admin_compra.index')->with('success', 'OC enviada con éxito.');
}


}
