<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PerfilController extends Controller
{
    public function editProfile()
    {
        $user = auth()->user(); // Obtener el usuario autenticado
        return view('perfil.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user(); // Obtener el usuario autenticado

        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        // Actualizar los datos
        $user->name = $request->name;
        $user->email = $request->email;

        // Si se incluye la contraseña, se actualiza
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save(); // Guardar cambios

        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado correctamente.');
    }
}
