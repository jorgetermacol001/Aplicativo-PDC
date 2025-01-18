@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar Usuario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-orange">
            <h3 class="card-title"><i class="fas fa-user-edit"></i> Editar Información del Usuario</h3>
            <div class="card-tools">
                <a href="{{ route('admin.user') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </div>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="card-body">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="form-group">
                    <label for="roles"><i class="fas fa-user-tag"></i> Roles</label>
                    <div>
                        @foreach ($roles as $role)
                            <div class="form-check">
                                <input 
                                    type="checkbox" 
                                    name="roles[]" 
                                    id="role_{{ $role->id }}" 
                                    class="form-check-input" 
                                    value="{{ $role->name }}"
                                    {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}>
                                <label for="role_{{ $role->id }}" class="form-check-label">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .bg-orange {
            background-color: #fd7e14 !important;
            color: white;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Vista de edición de usuario cargada correctamente.');
    </script>
@stop
