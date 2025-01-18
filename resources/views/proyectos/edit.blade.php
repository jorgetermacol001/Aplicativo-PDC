@extends('adminlte::page')

@section('title', 'Editar Proyecto')

@section('content_header')
    <h1 class="text-orange">Editar Proyecto</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-orange">
            <h3 class="card-title">
                <i class="fas fa-edit"></i> Editar Proyecto
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('proyectos.update', $proyecto->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombre" class="text-orange">Nombre del Proyecto</label>
                    <input type="text" name="nombre" id="nombre" 
                        class="form-control @error('nombre') is-invalid @enderror" 
                        value="{{ old('nombre', $proyecto->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion" class="text-orange">Descripción del Proyecto</label>
                    <textarea name="descripcion" id="descripcion" 
                        class="form-control @error('descripcion') is-invalid @enderror" 
                        rows="4" required>{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .bg-orange {
            background-color: #ff851b !important;
            color: white !important;
        }
        .text-orange {
            color: #ff851b !important;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Vista de edición de proyecto cargada correctamente.');
    </script>
@stop
