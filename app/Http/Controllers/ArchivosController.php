<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Proyecto;

class ArchivosController extends Controller
{
    public function index(Request $request)
    {
        
        $estadoProyecto = [ 'vigente','terminado'];
    
        // Construir la consulta base con relaciones
        $query = Proyecto::with(['productos.enlaces']);
    
        // Aplicar filtro por nombre si se proporciona
        if ($request->filled('nombre')) {
            // Limpiar y normalizar el nombre ingresado
            $nombre = trim($request->nombre); // Eliminar espacios iniciales y finales
            $nombre = preg_replace('/\s+/', ' ', $nombre); // Reemplazar múltiples espacios con uno
            $nombre = strtolower($nombre); // Convertir a minúsculas
        
            // Filtrar en la base de datos
            $query->whereRaw("LOWER(REPLACE(nombre, ' ', '')) LIKE ?", ['%' . str_replace(' ', '', $nombre) . '%']);
        }
        if ($request->filled('fecha_inicio')) {
            $query->where('created_at', [$request->fecha_inicio]);
        }
        if($request->filled('fecha_fin')){
            $query->where('fecha_fin',  $request->fecha_fin);
        }
        // Aplicar filtro por estado del proyecto si se proporciona
        if ($request->filled('estado_proyecto')) {
            $query->where('estado_proyecto', $request->estado_proyecto);
        }
        // Ejecutar la consulta
        $proyectos = $query->get();
    
        // Crear una estructura de enlaces organizada por carpetas
        $estructuraEnlaces = [];
    
        foreach ($proyectos as $proyecto) {
            foreach ($proyecto->productos as $producto) {
                // Obtener los enlaces asociados al producto
                $enlaces = $producto->enlaces;
    
                // Organizar los enlaces por rol
                foreach ($enlaces as $enlace) {
                    // Obtener el rol asociado al enlace
                    $rol = $enlace->rol;
    
                    // Determinar el nombre de la carpeta según el rol
                    $nombreCarpetaRol = match ($rol) {
                        'solicitante' => 'Solicitud de Compra',
                        'administrador_compra' => 'Órdenes de Compra',
                        'almacenista' => 'Recepción de Compra',
                        'admin' => 'Correcciones',
                        default => $rol, // Usar el rol original si no coincide
                    };
    
                    // Agregar a la estructura de enlaces bajo el proyecto, producto y nombre del rol
                    $estructuraEnlaces[$proyecto->nombre][$producto->nombre][$nombreCarpetaRol][] = [
                        'nombre_original' => $enlace->nombre_original,
                        'url_sharepoint' => $enlace->url_sharepoint, // URL del enlace
                    ];
                }
            }
        }
    
        // Pasar la estructura de enlaces y los filtros a la vista
        return view('archivos.index', compact('estructuraEnlaces', 'estadoProyecto'));
    }
    
    
}
