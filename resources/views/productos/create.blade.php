@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1 class="text-center text-orange">
        <i class="fas fa-box"></i> Crear Producto
    </h1>
@stop

@section('content')
    <div class="card shadow-lg border-top-orange">
        <div class="card-header bg-orange text-white">
            <h3 class="card-title">
                <i class="fas fa-plus-circle"></i> Formulario de Creación
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre" class="font-weight-bold text-orange">Nombre del Producto</label>
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="proyecto_id" class="font-weight-bold text-orange">Proyecto</label>
                            <select name="proyecto_id" id="proyecto_id" class="form-control @error('proyecto_id') is-invalid @enderror" required>
                                <option value="">Seleccione un proyecto</option>
                                @foreach ($proyectos as $proyecto)
                                    <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>{{ $proyecto->nombre }}</option>
                                @endforeach
                            </select>
                            @error('proyecto_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tipo_de_pago" class="font-weight-bold text-orange">Tipo de Pago</label>
                            <select name="tipo_de_pago" id="tipo_de_pago" class="form-control @error('tipo_de_pago') is-invalid @enderror" required>
                                <option value="">Seleccione</option>
                                @foreach ($tipoDePagoOptions as $option)
                                    <option value="{{ $option }}" {{ old('tipo_de_pago') == $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                                @endforeach
                            </select>
                            @error('tipo_de_pago')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="usuario_solicitante_id" class="font-weight-bold text-orange">Solicitante</label>
                            <select name="usuario_solicitante_id" id="usuario_solicitante_id" class="form-control @error('usuario_solicitante_id') is-invalid @enderror" required>
                                <option value="{{ auth()->user()->id }}" selected>{{ auth()->user()->name }}</option>
                            </select>
                            @error('usuario_solicitante_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="usuario_aprobador_id" class="font-weight-bold text-orange">Aprobador</label>
                            <select name="usuario_aprobador_id" id="usuario_aprobador_id" class="form-control @error('usuario_aprobador_id') is-invalid @enderror" required>
                                <option value="">Seleccione un usuario aprobador</option>
                                @foreach ($usuariosAprobadores as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('usuario_aprobador_id') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                            @error('usuario_aprobador_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                         {{-- Almacenista --}}
                        <div class="form-group">
                            <label for="usuario_almacenista_id" class="font-weight-bold text-orange">Almacenista</label>
                            <select name="usuario_almacenista_id" id="usuario_almacenista_id" class="form-control @error('usuario_almacenista_id') is-invalid @enderror" required>
                                <option value="">Seleccione un almacenista</option>
                                @foreach ($usuariosAlmacenistas as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('usuario_almacenista_id') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                            @error('usuario_almacenista_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="usuario_admin_compra_id" class="font-weight-bold text-orange">Administrador de Compras</label>
                            <select name="usuario_admin_compra_id" id="usuario_admin_compra_id" class="form-control @error('usuario_admin_compra_id') is-invalid @enderror" required>
                                <option value="">Seleccione un administrador</option>
                                @foreach ($usuariosAdminCompra as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('usuario_admin_compra_id') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                            @error('usuario_admin_compra_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="form-group">
                            <label for="contacto_proveedores" class="font-weight-bold text-orange">Contacto con Proveedores</label>
                            <textarea name="contacto_proveedores" id="contacto_proveedores" class="form-control @error('contacto_proveedores') is-invalid @enderror" rows="3">{{ old('contacto_proveedores') }}</textarea>
                            @error('contacto_proveedores')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="observaciones" class="font-weight-bold text-orange">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" class="form-control @error('observaciones') is-invalid @enderror" rows="4">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        {{-- enlaces sharepoint --}}
                        <div class="form-group">
                            <label for="enlaces_sharepoint" class="font-weight-bold text-orange">Enlaces SharePoint</label>
                            <div id="enlaces-container">
                                <div class="input-group mb-2 enlace-item">
                                    <input 
                                        type="text" 
                                        name="enlaces_sharepoint[0][nombre_original]" 
                                        class="form-control @error('enlaces_sharepoint.*.nombre_original') is-invalid @enderror" 
                                        placeholder="Nombre del enlace" 
                                        required
                                    >
                                    <input 
                                        type="url" 
                                        name="enlaces_sharepoint[0][url]" 
                                        class="form-control ml-2 @error('enlaces_sharepoint.*.url') is-invalid @enderror" 
                                        placeholder="URL de SharePoint" 
                                        required
                                    >
                                    <button type="button" class="btn btn-danger ml-2 remove-enlace">Eliminar</button>
                                </div>
                            </div>
                            <button type="button" id="add-enlace" class="btn btn-primary mt-2">Agregar enlace</button>
                            <small class="form-text text-muted">Agrega los enlaces de SharePoint con sus nombres correspondientes.</small>
                        
                            @error('enlaces_sharepoint.*.nombre_original')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            @error('enlaces_sharepoint.*.url')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-orange btn-lg">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-header {
            font-size: 1.2rem;
        }
        .form-group label {
            color: #e67e22; /* Naranja */
        }
        .btn-lg {
            font-size: 1.1rem;
            padding: 10px 20px;
        }
        .card-body {
            background-color: #f9f9f9;
        }
        .form-group input, .form-group select, .form-group textarea {
            border-radius: 8px;
        }
        .invalid-feedback {
            font-size: 0.9rem;
            color: #e3342f;
        }
        .btn-orange {
            background-color: #e67e22;
            border-color: #d76d0e;
        }
        .btn-orange:hover {
            background-color: #d76d0e;
            border-color: #e67e22;
        }
    </style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const enlacesContainer = document.getElementById('enlaces-container');
        const addEnlaceButton = document.getElementById('add-enlace');

        addEnlaceButton.addEventListener('click', () => {
            const index = enlacesContainer.children.length;
            const newEnlace = `
                <div class="input-group mb-2 enlace-item">
                    <input 
                        type="text" 
                        name="enlaces_sharepoint[${index}][nombre_original]" 
                        class="form-control" 
                        placeholder="Nombre del enlace" 
                        required
                    >
                    <input 
                        type="url" 
                        name="enlaces_sharepoint[${index}][url]" 
                        class="form-control ml-2" 
                        placeholder="URL de SharePoint" 
                        required
                    >
                    <button type="button" class="btn btn-danger ml-2 remove-enlace">Eliminar</button>
                </div>
            `;
            enlacesContainer.insertAdjacentHTML('beforeend', newEnlace);
        });

        enlacesContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-enlace')) {
                e.target.parentElement.remove();
            }
        });
    });
</script>

@stop
