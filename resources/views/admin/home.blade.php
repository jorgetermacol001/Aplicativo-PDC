@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="text-orange"><i class="fas fa-tasks"></i> Gestión de Aprobaciones</h1>
@stop

@section('content')

<table class="table table-bordered table-hover">
    <thead class="bg-orange text-white">
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pendingUsers as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center">
                        <!-- Botón de Aprobar -->
                        <form method="POST" action="{{ route('admin.approve', $user->id) }}" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-orange btn-sm">
                                <i class="fas fa-check"></i> Aprobar
                            </button>
                        </form>
                        
                        <!-- Botón de Rechazar -->
                        <form method="POST" action="{{ route('admin.reject', $user->id) }}">
                            @csrf
                            @method('DELETE') <!-- Esto convierte la solicitud a DELETE -->
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Rechazar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No hay usuarios pendientes.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@stop

@section('css')
    <style>
        /* Colores personalizados */
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
        .table-hover tbody tr:hover {
            background-color: #ffe5d5;
        }
        /* Ajustes de botones */
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Gestión de aprobaciones personalizada con colores naranjas.");
    </script>
@stop
