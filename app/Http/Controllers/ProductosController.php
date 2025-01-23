<?php

namespace App\Http\Controllers;

use App\Notifications\NotificacionNuevaProducto;
use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\User;
use App\Models\Proyecto;
use App\Models\Enlace;
use Illuminate\Support\Facades\Storage;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Str;


class ProductosController extends Controller
{
    /**
     * Mostrar una lista de productos.
     */
    public function index(Request $request)
    {
        $proyectos = Proyecto::all();
        $estados_oc = [
            'OC creada', 'Rechazada', 'OC enviada', 'Compra aprobada',
            'Pendiente OC', 'Revisión OC', 'OC liberada', 'OC por enviar',
            'Cancelada', 'OC por confirmar', 'OC confirmada', 'Revisado OC', 'Compra corrección',
            'Compra corregida', 'Compra solicitada', 'OC corrección',
        ];
        $estados_cp = [
            'Revisión', 'Liberado', 'CP por enviar', 'CP enviado',
            'Cancelado', 'No aplica', 'CP por confirmar', 'CP confirmado',
            'CP por liberar', 'Pendiente CP',
        ];
        $estados_pago = ['pago pendiente', 'pago programado', 'pago liberado'];
        $estados_solicitud = ['vigente', 'terminado'];
    
        // Crear la consulta base
        $query = Productos::with(['solicitante', 'aprobador']);
    
        // Aplicar filtros según las entradas del formulario
        if ($request->filled('tipo_de_pago')) {
            $query->where('tipo_de_pago', $request->tipo_de_pago);

        }if($request->filled('estado_solictud')){
            $query->where('estado_solictud', $request->estado_solictud);
        }
        if ($request->filled('estado_oc')) {
            $query->where('estado_oc', $request->estado_oc);
        }
        if ($request->filled('estado_cp')) {
            $query->where('estado_cp', $request->estado_cp);
        }
        if ($request->filled('estado_entrega')) {
            $query->where('estado_entrega', $request->estado_entrega);
        }
        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }
        if ($request->filled('fecha_inicio')) {
            $query->where('created_at', [$request->fecha_inicio]);
        }
        if($request->filled('fecha_fin')){
            $query->where('fecha_fin',  $request->fecha_fin);
        }
        if ($request->filled('nombre')) {
            // Limpiar y normalizar el nombre ingresado
            $nombre = trim($request->nombre); // Eliminar espacios iniciales y finales
            $nombre = preg_replace('/\s+/', ' ', $nombre); // Reemplazar múltiples espacios con uno
            $nombre = strtolower($nombre); // Convertir a minúsculas
        
            // Filtrar en la base de datos
            $query->whereRaw("LOWER(REPLACE(nombre, ' ', '')) LIKE ?", ['%' . str_replace(' ', '', $nombre) . '%']);
        }
        
    
        // Obtener los resultados paginados
        $productos = $query->paginate(10);
    
        return view('productos.index', compact('productos', 'proyectos', 'estados_oc', 'estados_cp', 'estados_pago', 'estados_solicitud'));
    }
    
    
    /**
     * Mostrar el formulario para crear un nuevo producto.
     */
    public function create()
    {
        // Obtenemos la lista de usuarios solicitantes
        $usuariosSolicitantes =  auth()->id();

        // Obtenemos todos los proyectos
        $proyectos = Proyecto::where('estado_proyecto', 'vigente')->get();
    
        // Obtenemos solo los usuarios con el rol de 'aprobador'
        $usuariosAprobadores = User::role('aprobador')->get();

        // Obtenemos solo los usuarios con el rol de 'admin Compra'
        $usuariosAdminCompra = User::role('administrador_compra')->get();

        
        // Obtenemos solo los usuarios con el rol de 'almacenista'
        $usuariosAlmacenistas = User::role('almacenista')->get();
    
        return view('productos.create', [
            'tipoDePagoOptions' => Productos::getTipoDePagoOptions(),
            'usuariosSolicitantes' => $usuariosSolicitantes,
            'proyectos'=> $proyectos,
            'usuariosAprobadores' => $usuariosAprobadores,
            'usuariosAdminCompra'=> $usuariosAdminCompra,
            'usuariosAlmacenistas'=> $usuariosAlmacenistas
        ]);
    }

    /**
     * Almacenar un nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos del producto y enlaces SharePoint
        $request->validate([
            'nombre' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
            'proyecto_id' => 'required|exists:proyectos,id',
            'tipo_de_pago' => 'required|in:credito,contado,contado y credito',
            'usuario_aprobador_id' => 'required|exists:users,id',
            'usuario_admin_compra_id' => 'required|exists:users,id',
            'usuario_almacenista_id' => 'required|exists:users,id',
            'contacto_proveedores' => 'nullable|string|max:255',
            'enlaces_sharepoint.*.url' => 'required|url',
            'enlaces_sharepoint.*.nombre_original' => 'required|string|max:255',
        ]);
    
        // Crear el producto
        $producto = Productos::create([
            'nombre' => $request->nombre,
            'observaciones' => $request->observaciones,
            'proyecto_id' => $request->proyecto_id,
            'tipo_de_pago' => $request->tipo_de_pago,
            'usuario_solicitante_id' => auth()->id(),
            'usuario_aprobador_id' => $request->usuario_aprobador_id,
            'usuario_admin_compra_id' => $request->usuario_admin_compra_id,
            'usuario_almacenista_id' => $request->usuario_almacenista_id,
            'contacto_proveedores' => $request->contacto_proveedores,
        ]);
    
        // Determinar el rol del usuario autenticado
        $rolUsuario = strtolower(auth()->user()->getRoleNames()->first()); // Ejemplo: 'solicitante', 'admin_compra', 'almacenista'
    
        // Manejo de enlaces SharePoint
        if ($request->has('enlaces_sharepoint')) {
            foreach ($request->enlaces_sharepoint as $enlace) {
                $producto->enlaces()->create([
                    'nombre_original' => $enlace['nombre_original'],
                    'url_sharepoint' => $enlace['url'],
                    'rol' => $rolUsuario, // Asignar automáticamente el rol del usuario autenticado
                ]);
            }
        }
    
        // Envía una notificación al aprobador
        $producto->aprobador->notify(new InvoicePaid($producto));
    
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente con sus enlaces de SharePoint.');
    }
    
    


    /**
     * Mostrar los detalles de un producto específico.
     */
// En tu controlador, asegúrate de cargar las relaciones con eager loading.
public function show($id)
{
    // Obtener el producto
    $producto = Productos::find($id);

    if (!$producto) {
        abort(404, 'Producto no encontrado');
    }

    // Obtener los enlaces relacionados al producto, organizados por rol
    $enlaces = $producto->enlaces()->get();

    // Inicializar la estructura de enlaces por roles
    $estructuraEnlaces = [];

    foreach ($enlaces as $enlace) {
        $estructuraEnlaces[$enlace->rol][] = [
            'url' => $enlace->url_sharepoint,
            'nombre_original' => $enlace->nombre_original,
        ];
    }

    // Si no hay enlaces relacionados, inicializar como array vacío
    if (empty($estructuraEnlaces)) {
        $estructuraEnlaces = [];
    }

    return view('productos.show', compact('producto', 'estructuraEnlaces'));
}



    /**
     * Mostrar el formulario para editar un producto.
     */
    public function edit($id)
    {
        $producto = Productos::findOrFail($id);
    
        // Obtener los usuarios para las relaciones, filtrando por rol
        $usuariosSolicitantes = User::role('solicitante')->get();
        $usuariosAprobadores = User::role('aprobador')->get();
        $administradoresCompra = User::role('administrador_compra')->get();
        $usuariosAlmacenistas = User::role('almacenista')->get();
    
        // Obtener los proyectos
        $proyectos = Proyecto::all(); // Si tienes un modelo Proyecto
    
        return view('productos.edit', [
            'producto' => $producto,
            'tipoDePagoOptions' => Productos::getTipoDePagoOptions(),
            'estadoEntregaOptions' => Productos::getEstadoEntregaOptions(),
            'estadoOCOptions' => Productos::getEstadoOCOptions(),
            'estadoCPOptions' => Productos::getEstadoCPOptions(),
            'usuariosSolicitantes' => $usuariosSolicitantes,
            'usuariosAprobadores' => $usuariosAprobadores,
            'administradoresCompra' => $administradoresCompra,
            'usuariosAlmacenistas'=> $usuariosAlmacenistas,
            'proyectos' => $proyectos // Aquí pasas los proyectos a la vista
        ]);
    }
    

    /**
     * Actualizar un producto en la base de datos.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos del producto y enlaces SharePoint
        $request->validate([
            'nombre' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
            'proyecto_id' => 'required|exists:proyectos,id',
            'tipo_de_pago' => 'required|in:credito,contado,contado y credito',
            'usuario_aprobador_id' => 'required|exists:users,id',
            'usuario_admin_compra_id' => 'required|exists:users,id',
            'usuario_almacenista_id' => 'required|exists:users,id',
            'contacto_proveedores' => 'nullable|string|max:255',
            'enlaces_sharepoint.*.url' => 'required|url',
            'enlaces_sharepoint.*.nombre_original' => 'required|string|max:255',
        ]);
    
        $producto = Productos::findOrFail($id);
    
        // Actualizar los campos del producto
        $producto->update([
            'nombre' => $request->nombre,
            'observaciones' => $request->observaciones,
            'proyecto_id' => $request->proyecto_id,
            'tipo_de_pago' => $request->tipo_de_pago,
            'usuario_aprobador_id' => $request->usuario_aprobador_id,
            'usuario_admin_compra_id' => $request->usuario_admin_compra_id,
            'usuario_almacenista_id' => $request->usuario_almacenista_id,
            'contacto_proveedores' => $request->contacto_proveedores,
        ]);
    
        // Actualizar los enlaces existentes
        foreach ($request->enlaces_sharepoint as $enlace) {
            if (isset($enlace['id'])) {
                // Obtener el enlace existente
                $enlaceExistente = $producto->enlaces()->find($enlace['id']);
                if ($enlaceExistente) {
                    // Actualizar solo los campos permitidos
                    $enlaceExistente->update([
                        'nombre_original' => $enlace['nombre_original'],
                        'url_sharepoint' => $enlace['url'],
                    ]);
                }
            }
        }
    
        // Redirigir con mensaje de éxito
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }
    
    
    
    /**
     * Eliminar un producto de la base de datos.
     */
    public function destroy(Productos $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado con éxito.');
    }
 

    public function agregarEnlace(Request $request, Productos $producto)
    {
        // Validar datos de los enlaces SharePoint
        $request->validate([
            'nombre_original' => 'required|string|max:255', // Validar el nombre del enlace
            'url_sharepoint' => 'required|url', // Validar que sea una URL válida
        ]);
    
        // Obtener el rol del usuario autenticado
        $roleFolder = strtolower(auth()->user()->getRoleNames()->first());
    
        // Guardar el enlace en la base de datos
        $producto->enlaces()->create([
            'rol' => $roleFolder,
            'url_sharepoint' => $request->url_sharepoint, // URL del enlace de SharePoint
            'nombre_original' => $request->nombre_original, // Nombre del enlace
        ]);
    
        return redirect()->route('productos.show', $producto)->with('success', 'Enlace agregado correctamente.');
    }

    public function TerminarSolicitud($id){

        $producto = Productos::findOrFail($id);

        // Verificar si el usuario actual es el solicitante y si la OC está en el estado adecuado
        if ($producto->estado_solictud === 'vigente') {
            abort(403, 'No tienes permiso para terminar este proyecto.');
        }
        $producto->estado_solicitud = 'terminado';
        $producto->fecha_fin = now();
        $producto->save();
        return redirect()->route('productos.index')->with('success', 'Proyecto marcado como terminado exitosamente.');
    }

    public function deleteEnlace($productoId, $enlaceId){
    // Buscar el producto y el enlace relacionado
    $producto = Productos::findOrFail($productoId);
    $enlace = $producto->enlaces()->findOrFail($enlaceId);

    // Eliminar el enlace
    $enlace->delete();

    // Redirigir con un mensaje de éxito
    return redirect()->route('productos.edit', $productoId)
        ->with('success', 'Enlace eliminado correctamente.');
    }


}    
