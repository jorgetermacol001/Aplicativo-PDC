<?php

use App\Http\Controllers\NotificacionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\AprobadorController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\SolicitanteController;
use App\Http\Controllers\Admin_Compra_Controller;
use App\Http\Controllers\AlmacenistaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ArchivosController;

Route::get('/', function () {
    return view('welcome');
});

Route::group([ 'middleware'=> 'auth', 'role:admin'], function () {
        // Rutas de Admin
        

        // Rutas para gestionar User
        Route::get('/gestionUser', [AdminUserController::class, 'listActiveUsers'])->name('admin.user');
        Route::get('edit/{id}', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::patch('users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::patch('users/{id}/deactivate', [AdminUserController::class, 'deactivate'])->name('users.deactivate');
        Route::delete('user/delete/{id}', [AdminUserController::class, 'eliminarUser'])->name('user.eliminar');

        // Rutas para gestionar roles 
        Route::get('{id}/roles/add', [AdminUserController::class, 'addRoles'])->name('users.roles.add');
        Route::post('users/{id}/roles/store', [AdminUserController::class, 'storeRoles'])->name('users.roles.store');
        
        // Rutas para gestionar proyectos
        Route::get('proyectos', [ProyectosController::class, 'index'])->name('proyectos.index');
        Route::get('proyectos/create', [ProyectosController::class, 'create'])->name('proyectos.create');
        Route::post('proyectos', [ProyectosController::class, 'store'])->name('proyectos.store');
        Route::get('proyectos/{id}', [ProyectosController::class, 'show'])->name('proyectos.show');
        Route::post('proyectos/edit/{id}', [ProyectosController::class, 'edit'])->name('proyectos.edit');
        Route::put('proyectos/update/{id}', [ProyectosController::class, 'update'])->name('proyectos.update');
        Route::post('proyectos/terminar/{id}', [ProyectosController::class, 'TerminarProyecto'])->name('proyectos.terminar');
        Route::delete('proyectos/delete/{id}', [ProyectosController::class, 'eliminarProyecto'])->name('proyectos.destroy');
        Route::get('admin/proyectos', [ProyectosController::class, 'index']);
    
        // Rutas para registar 
        Route::get('admin/registro', [AdminUserController::class, 'showRegisterForm'])->name('admin.showRegisterForm');
        Route::post('/admin/registro', [AdminUserController::class, 'register'])->name('admin.register');

        // Rutas para los archivos
        Route::get('/archivos', [ArchivosController::class,'index'])->name('archivos.index');

        // Rutas para los productos
        Route::post('productos/destroy/{id}', [ProductosController::class, 'destroy'])->name('productos.destroy');
        Route::get('productos/edit/{id}', [ProductosController::class, 'edit'])->name('productos.edit');
        Route::patch('/productos/update/{id}', [ProductosController::class, 'update'])->name('productos.update');
        Route::put('productos/terminar/{id}', [ProductosController::class, 'TerminarSolicitud'])->name('productos.terminar');
        Route::delete('/productos/{producto}/enlaces/{enlace}', [ProductosController::class, 'deleteEnlace'])
    ->name('productos.enlaces.delete');

       
    
});  


//Ruta para el Aprobadir 
Route::group(['middleware' => ['role:aprobador','auth']], function () { 

    Route::get('/aprobador/pendientes', [AprobadorController::class, 'pendientes'])->name('aprobador.pendientes');
    Route::get('/aprobador/{id}', [AprobadorController::class, 'show'])->name('aprobador.show');
    Route::patch('/aprobador/{id}/aprobar', [AprobadorController::class, 'aprobar'])->name('aprobador.aprobar');
    Route::patch('/aprobador/{id}/rechazar', [AprobadorController::class, 'rechazar'])->name('aprobador.rechazar');
    Route::patch('/aprobador/liberar-oc/{id}', [AprobadorController::class, 'liberarOC'])->name('aprobador.liberar');
    Route::patch('/aprobador/correccion/{id}', [AprobadorController::class, 'compraCorreccion'])->name('aprobador.correcion');
    Route::patch('/aprobador/pagoLiberado/{id}', [AprobadorController::class, 'PagoLiberado'])->name('aprobador.pagoliberado');
});



// Rutas para el solicitante
Route::group(['middleware'=> ['role:solicitante', 'auth']], function () {

    Route::post('/solicitante/revisar-oc/{id}', [SolicitanteController::class, 'revisarOC'])->name('solicitante.pendientes');
    Route::patch('/solicitante/corregir-oc/{id}', [SolicitanteController::class, 'compraCorreccion'])->name('solicitante.compraCorreccion');
    Route::get('/solicitante/compras', [SolicitanteController::class, 'misCompras'])->name('solicitante.compras');
});


// Rutas para el admin compras
Route::group(['middleware'=> ['role:administrador_compra', 'auth']], function () {
    Route::get('/admin_compras', [Admin_Compra_Controller::class, 'indexAdmin'])->name('admin_compra.index');
    Route::get('/admincompra/{id}', [Admin_Compra_Controller::class, 'show'])->name('admincompra.show');
    Route::get('/admincompra/crear/{id}', [Admin_Compra_Controller::class, 'crearOC'])->name('admincompra.crearOC');
    Route::patch('admincompra/enviar/{id}', [Admin_Compra_Controller::class, 'enviarOC'])->name('admincompra.enviarOC');
    Route::get('/admincompra/correccion/{id}', [Admin_Compra_Controller::class, 'correccionOC'])->name('admincompra.correccionOC');
    Route::patch('/admincompra/comfirmar/{id}', [Admin_Compra_Controller::class, 'ComfirmadaOC'])->name('admincompra.ComfirmadaOC');
    Route::patch('/admincompra/pagoProgramado/{id}', [Admin_Compra_Controller::class, 'PagoProgramado'])->name('admincompra.pagoprogramado');
    Route::patch('/admincompra/enviarCP/{id}', [Admin_Compra_Controller::class, 'EnviarCP'])->name('admincompra.enviarCP');
});

// Rutas para el almacenista
Route::group(['middleware'=> ['role:almacenista','auth']], function () {
    Route::get('/almacenista', [AlmacenistaController::class, 'index'])->name('almacenista.index');
    Route::patch('/almacenista/entregaTotal/{id}', [AlmacenistaController::class,'EntregaTotal'])->name('almacenista.EntregaTotal');
    Route::patch('/almacenista/entregaParcial/{id}', [AlmacenistaController::class,'EntregaParcial'])->name('almacenista.EntregaParcial');

});

// Rutas Normales 
Route::group(['middleware'=> ['auth',]], function () {

    Route::get('/home', [ ProductosController::class, 'index'])->name('home');
    
    // Rutas para el perfil
    Route::get('/profile/edit', [PerfilController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [PerfilController::class, 'updateProfile'])->name('profile.update');

    // Ruta para las notificaciones
    Route::get('/notificaciones', [NotificacionController::class,'index'])->name('notificacion.index');
    Route::patch('/notificaciones/{id}/marcar-como-leida', [NotificacionController::class, 'marcarComoLeida'])->name('notificaciones.marcarComoLeida');
    // Ruta para obtener las notificaciones pendientes
    Route::get('notifications/get', [NotificacionController::class, 'getNotificationsData'])->name('notifications.get');

    
    // Rutas para producto
    Route::get('/productos', [ProductosController::class,'index'])->name('productos.index');
    Route::get('/productos/show/{id}', [ProductosController::class,'show'])->name('productos.show');
    Route::get('/productos/create', [ProductosController::class,'create'])->name('productos.create');
    Route::post('productos', [ProductosController::class, 'store'])->name('productos.store');
    Route::post('/productos/{producto}/agregar-enlace', [ProductosController::class, 'agregarEnlace'])->name('productos.agregarArchivo');

    // Rutas para manejar la password 
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


});


Auth::routes();





