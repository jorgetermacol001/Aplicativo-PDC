@extends('adminlte::page')

@section('title', 'Asignar Roles')

@section('content_header')
    <h1 class="text-orange text-center">
        <i class="fas fa-user-tag"></i> Asignar Roles a <strong>{{ $user->name }}</strong>
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h4>
                <i class="fas fa-user-plus"></i> Asignar Roles a {{ $user->name }}
            </h4>
        </div>

        <div class="card-body">
            <p><strong>Correo:</strong> {{ $user->email }}</p>

            <form action="{{ route('users.roles.store', $user->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="roles">Selecciona los Roles:</label>
                    <select name="roles[]" id="roles" class="form-control" multiple>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" 
                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">
                        Puedes seleccionar múltiples roles manteniendo presionada la tecla CTRL (o CMD en Mac).
                    </small>
                </div>

                <div class="form-group mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-orange">
                        <i class="fas fa-save"></i> Guardar Roles
                    </button>
                    <a href="{{ route('admin.user') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .text-orange {
            color: #fd7e14 !important;
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
        .card-header {
            background-color: #fd7e14 !important;
            border-color: #fd7e14;
        }
        .card {
            border-radius: 8px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-text {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Página de asignación de roles cargada correctamente.");
    </script>
@stop
