<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class ProyectosController extends Controller
{
     /**
     * Mostrar una lista de proyectos.
     */
    public function index(Request $request)
    {
        $proyectos = Proyecto::where('user_id', Auth::id())->get(); // Proyectos del usuario autenticado
        $query = Proyecto::with('productos');
        $estadoProyecto = [ 'vigente','terminado'];
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
        return view('proyectos.index', compact('proyectos', 'estadoProyecto'));
    }

    /**
     * Mostrar el formulario para crear un nuevo proyecto.
     */
    public function create()
    {
        return view('proyectos.create');
    }

    /**
     * Guardar un nuevo proyecto en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los campos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
        ]);
    
        // Crear nuevo proyecto
        $project = new Proyecto();
        $project->nombre = $request->input('nombre');
        $project->descripcion = $request->input('descripcion');
        $project->estado = true; // O el valor que necesites para el estado
        $project->estado_proyecto = 'vigente';
        $project->user_id = auth()->id(); // Asocia al usuario autenticado
        $project->save();
    
        // Redirige después de guardar
        return redirect()->route('proyectos.index');
    }
    /**
     * Mostrar los detalles de un proyecto.
     */
    public function show($id)
    {
        // Obtener el proyecto por ID
        $proyecto = Proyecto::findOrFail($id);
    
        // Asegurarse de que el usuario tenga permiso para ver este proyecto (opcional)
        if (!auth()->user()){
            abort(403, 'No tienes permiso para ver este proyecto.');
        }
    
        // Retornar la vista con el proyecto
        return view('proyectos.show', compact('proyecto'));
    }
    

    /**
     * Mostrar el formulario para editar un proyecto existente.
     */
    public function edit($id)
    {
        $proyecto = Proyecto::findOrFail($id);


       ; // Asegurarse de que el usuario pueda editar este proyecto
        return view('proyectos.edit', compact('proyecto'));
    }

    /**
     * Actualizar un proyecto en la base de datos.
     */
    public function update(Request $request, $id)
    {
        // Validar los campos de entrada
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
        ]);

        // Buscar y actualizar el proyecto
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente.');
    }
    /**
     * Eliminar un proyecto de la base de datos.
     */
    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        // Cambiar el estado del proyecto a false
        $proyecto->estado = false;
        $proyecto->save();
    
        return redirect()->route('proyectos.index')->with('success', 'Proyecto marcado como eliminado exitosamente.');
    }

    public function TerminarProyecto($id){

        $proyecto = Proyecto::findOrFail($id);

        // Verificar si el usuario actual es el solicitante y si la OC está en el estado adecuado
        if (auth()->id() !== $proyecto->user_id || $proyecto->estado_proyecto !== 'vigente') {
            abort(403, 'No tienes permiso para terminar este proyecto.');
        }
        $proyecto->estado_proyecto = 'terminado';
        $proyecto->fecha_fin = date('Y-m-d')-now();
        $proyecto->save();
        return redirect()->route('proyectos.index')->with('success', 'Proyecto marcado como terminado exitosamente.');
    }

    public function eliminarProyecto($id){
        
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();
        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado exitosamente.');
    }
    
}
