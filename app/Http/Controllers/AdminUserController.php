<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserRegisteredNotification;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
class AdminUserController extends Controller
{



        // Función para eliminar usuarios
        public function delete($id)
        {
            $user = User::findOrFail($id);
    
            // Eliminar el usuario de la base de datos
            $user->delete();
    
            return redirect()->back()->with('success', 'El usuario ha sido eliminado correctamente.');
        }
           
    
        //Funciones para editar un  usuario
        public function edit($id)
        {
            $user = User::findOrFail($id);
            $roles = Role::all(); // Obtener todos los roles
        
            return view('admin.editUsers', compact('user', 'roles'));
        }
        
        public function update(Request $request, $id)
        {
            $user = User::findOrFail($id);
        
            // Validar datos
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
        
            // Actualizar nombre y correo
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        
            // Sincronizar roles
            $user->syncRoles($request->roles ?? []);
        
            return redirect()->route('admin.user')->with('success', 'Usuario actualizado correctamente.');
        }


        //Funcion para desactivar un usuario
        public function deactivate($id){
            $user = User::findOrFail($id);

            // Cambiar el estado del usuario
            $user->update(['estado' => false]);

            return redirect()->route('admin.user')->with('success', 'El usuario ha sido desactivado.');
        }


        //Funcion para obtener usuarios activos
        public function listActiveUsers(){
            // Obtener todos los usuarios con estado activo
            $activeUsers = User::where('estado', true)->paginate(10);

            // Retornar una vista con los usuarios activos
            return view('admin.gestionUser', compact('activeUsers'));
        }

       
        //Funcion para obtener Roles.
        public function addRoles($id){
            $user = User::findOrFail($id);
            $roles = Role::all(); // Obtén todos los roles disponibles
            
            return view('admin.add_roles', compact('user', 'roles'));
        }

        //Funciones para guardar los roles de un usuario.
        public function storeRoles(Request $request, $id){
            $user = User::findOrFail($id);

            // Validar roles seleccionados
            $request->validate([
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,name',
            ]);

            // Sincronizar roles seleccionados
            $user->syncRoles($request->roles);

            return redirect()->route('admin.user')->with('success', 'Roles actualizados correctamente.');
        }

        // Funciones para Registrar Usuarios
        public function showRegisterForm()
        {
            return view('admin.register');
        }
    
        public function register(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
    
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'is_pending' => true,
                'password' => Hash::make($request->password),
            ]);
    
            // Asignar un rol si estás utilizando Spatie
            $user->assignRole('solicitante'); // Cambia 'user' según tu configuración
    
            // Enviar notificación
            Notification::send($user, new UserRegisteredNotification($user, $request->password));
    
            return redirect()->route('admin.showRegisterForm')->with('success', 'Usuario registrado y notificado exitosamente.');
        }

        public function eliminarUser($id){
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('admin.user')->with('success', 'Usuario eliminado correctamente.');
        }

        
}



