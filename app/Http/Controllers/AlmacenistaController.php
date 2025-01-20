<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;

class AlmacenistaController extends Controller
{
    function index(Request $request)
    {
        // Obtiene el usuario autenticado
        $usuarioActual = auth()->user();
    
        // Verifica si el usuario tiene el rol de Almacenista
        if (!$usuarioActual->hasRole('almacenista')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
    
        // Filtra los productos según los estados permitidos y asignados al usuario
        $productos = Productos::where('usuario_almacenista_id', $usuarioActual->id)
            ->where('estado_solicitud', 'vigente') // Nuevo filtro para estado_proyecto
            ->where(function ($query) {
                $query->where('estado_cp', 'CP enviado')
                      ->orWhere('estado_entrega', 'entrega pendiente', 'entrega parcial');
                      //->orWhere('estado_pago', 'pago programado');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        // Retorna la vista con los productos
        return view('almacenista.index', compact('productos'));
    }
    

    function EntregaTotal($id){
        $producto = Productos::findOrFail($id);

        // Verificar si el usuario actual es el administrador de compras y si la OC está liberada
        if (auth()->id() !== $producto->usuario_almacenista_id || ($producto->estado_entrega != 'entrega pendiente' && $producto->estado_entrega != 'entrega parcial')) {
            abort(403, 'No tienes permiso para modificar esta OC.');
        }
        
        
        // Cambiar el estado de la OC
        $producto->estado_entrega = 'entrega total';
        $producto->fecha_entrega_materia = now();
        $producto->save();
    
    
        return redirect()->route('almacenista.index')->with('success', 'OC enviada con éxito.');
     }



    function EntregaParcial($id){

        $producto = Productos::findOrFail($id);

        // Verificar si el usuario actual es el administrador de compras y si la OC está liberada
        if (auth()->id() !== $producto->usuario_almacenista_id || $producto->estado_entrega != 'entrega pendiente') {
            abort(403, 'No tienes permiso para enviar esta OC.');
        }
    
        // Cambiar el estado de la OC
        $producto->estado_entrega = 'entrega parcial';
        $producto->fecha_entrega_materia = now();
        $producto->save();
    
    
        return redirect()->route('almacenista.index')->with('success', 'OC enviada con éxito.');
    
    }

    
     


}
