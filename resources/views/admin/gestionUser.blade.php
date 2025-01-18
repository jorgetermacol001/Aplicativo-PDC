@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')

<div class="container">
    <h1 class="text-orange"><i class="fas fa-users"></i> Usuarios Activos</h1>

    @if($activeUsers->isEmpty())
        <p class="text-muted">No hay usuarios activos en este momento.</p>
    @else
        <table class="table table-hover">
            <thead class="bg-orange text-white">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeUsers as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->roles->isNotEmpty())
                                <ul class="list-unstyled mb-0">
                                    @foreach($user->roles as $role)
                                        <li><span class="badge badge-orange">{{ $role->name }}</span></li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Sin roles asignados</span>
                            @endif
                        </td>
                        <td>
                            <!-- Botón para editar -->
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            <!-- Botón para desactivar -->
                            <form action="{{ route('user.eliminar', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-ban"></i> Eliminar
                                </button>
                            </form>

                            <!-- Botón para agregar roles -->
                            <a href="{{ route('users.roles.add', $user->id) }}" class="btn btn-orange btn-sm">
                                <i class="fas fa-user-plus"></i> Agregar Roles
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@stop

@section('css')
    <style>
        /* Tono naranja */
        .text-orange {
            color: #fd7e14 !important;
        }
        .bg-orange {
            background-color: #fd7e14 !important;
        }
        .btn-orange {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }
        .btn-orange:hover {
            background-color: #e76b00;
            border-color: #e76b00;
        }
        .badge-orange {
            background-color: #fd7e14;
            color: white;
        }
        /* Tabla con borde suave */
        .table-hover tbody tr:hover {
            background-color: #ffe5d5;
        }
    </style>
@stop

@section('js')
    <script> 
        console.log("Tabla de usuarios activos con colores naranjas aplicada.");
    </script>
@stop
