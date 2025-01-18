@extends('adminlte::page')

@section('content')
<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header bg-orange text-white">
            <h3 class="card-title"><i class="fas fa-user-plus"></i> Registrar Usuario</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.register') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Nombre:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Ingrese el nombre del usuario" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Ingrese el correo electrónico" required>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Ingrese una contraseña segura" required>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-orange text-white">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .bg-orange {
        background-color: #ff7700 !important;
    }
    .btn-orange {
        background-color: #ff7700;
        border-color: #ff7700;
    }
    .btn-orange:hover {
        background-color: #e06600;
        border-color: #e06600;
    }
</style>
@endpush
