@extends('adminlte::page')

@section('title', 'Editar Perfil')

@section('content_header')
    <h1>Editar Perfil</h1>
@endsection

@section('content')
<div class="card card-orange">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-edit"></i> Actualizar Información del Perfil</h3>
    </div>
    <form action="{{ route('profile.update') }}" method="POST" class="form-horizontal">
        @csrf
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Campo Nombre -->
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">
                    <i class="fas fa-user"></i> Nombre
                </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                           value="{{ old('name', $user->name) }}" placeholder="Ingresa tu nombre" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Campo Correo -->
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">
                    <i class="fas fa-envelope"></i> Correo Electrónico
                </label>
                <div class="col-sm-10">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                           value="{{ old('email', $user->email) }}" placeholder="Ingresa tu correo electrónico" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Campo Contraseña -->
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">
                    <i class="fas fa-lock"></i> Nueva Contraseña
                </label>
                <div class="col-sm-10">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" 
                           placeholder="Dejar vacío si no deseas cambiarla">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Campo Confirmación Contraseña -->
            <div class="form-group row">
                <label for="password_confirmation" class="col-sm-2 col-form-label">
                    <i class="fas fa-lock"></i> Confirmar Contraseña
                </label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                           placeholder="Confirma tu nueva contraseña">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
