@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
    <h1 class="text-center text-orange">
        <i class="fas fa-edit"></i> Editar Producto
    </h1>
@stop

@section('content')
    <div class="card shadow-lg">
        <div class="card-header bg-orange text-white">
            <h3 class="card-title">
                <i class="fas fa-pen"></i> Formulario de Edición de Producto
            </h3>
        </div>
        <div class="card-body">
            {{-- Formulario de Edición --}}
            <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- Información Básica --}}
                <div class="mb-4">
                    <h4><i class="fas fa-info-circle"></i> Información Básica</h4>
                    <div class="form-row">
                        {{-- Nombre del Producto --}}
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre del Producto:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" 
                                   value="{{ old('nombre', $producto->nombre) }}" required>
                        </div>

                        {{-- Proyecto --}}
                        <div class="form-group col-md-6">
                            <label for="proyecto_id">Proyecto:</label>
                            <select name="proyecto_id" id="proyecto_id" class="form-control">
                                @foreach($proyectos as $proyecto)
                                    <option value="{{ $proyecto->id }}" 
                                            {{ old('proyecto_id', $producto->proyecto_id) == $proyecto->id ? 'selected' : '' }}>
                                        {{ $proyecto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        {{-- Descripción --}}
                        <div class="form-group col-md-6">
                            <label for="descripcion">Descripción:</label>
                            <textarea name="descripcion" id="descripcion" class="form-control">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>

                        {{-- Observaciones --}}
                        <div class="form-group col-md-6">
                            <label for="observaciones">Observaciones:</label>
                            <textarea name="observaciones" id="observaciones" class="form-control">{{ old('observaciones', $producto->observaciones) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Sección de Fechas --}}
                <div class="mb-4">
                    <h4><i class="fas fa-calendar-alt"></i> Fechas</h4>
                    <div class="form-row">
                        {{-- Fecha de Creación --}}
                        <div class="form-group col-md-6">
                            <label for="fecha_creacion">Fecha de Creación:</label>
                            <input type="date" name="fecha_creacion" id="fecha_creacion" class="form-control" 
                                   value="{{ old('fecha_creacion', $producto->fecha_creacion) }}">
                        </div>

                        {{-- Fecha de Modificación --}}
                        <div class="form-group col-md-6">
                            <label for="fecha_modificacion">Fecha de Modificación:</label>
                            <input type="date" name="fecha_modificacion" id="fecha_modificacion" class="form-control" 
                                   value="{{ old('fecha_modificacion', $producto->fecha_modificacion) }}">
                        </div>
                    </div>
                </div>

                {{-- Roles y Enlaces de SharePoint --}}
                <div class="mb-4">
                    <h4><i class="fas fa-users"></i> Roles y Enlaces</h4>
                    <div class="form-row">
                        {{-- Solicitante --}}
                        <div class="form-group col-md-4">
                            <label for="usuario_solicitante_id">Solicitante:</label>
                            <select name="usuario_solicitante_id" id="usuario_solicitante_id" class="form-control">
                                @foreach($usuariosSolicitantes as $usuario)
                                    <option value="{{ $usuario->id }}" 
                                            {{ old('usuario_solicitante_id', $producto->usuario_solicitante_id) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Aprobador --}}
                        <div class="form-group col-md-4">
                            <label for="usuario_aprobador_id">Aprobador:</label>
                            <select name="usuario_aprobador_id" id="usuario_aprobador_id" class="form-control">
                                @foreach($usuariosAprobadores as $usuario)
                                    <option value="{{ $usuario->id }}" 
                                            {{ old('usuario_aprobador_id', $producto->usuario_aprobador_id) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Administrador de Compra --}}
                        <div class="form-group col-md-4">
                            <label for="usuario_admin_compra_id">Administrador de Compra:</label>
                            <select name="usuario_admin_compra_id" id="usuario_admin_compra_id" class="form-control">
                                @foreach($administradoresCompra as $usuario)
                                    <option value="{{ $usuario->id }}" 
                                            {{ old('usuario_admin_compra_id', $producto->usuario_admin_compra_id) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Enlaces de SharePoint --}}
                    <div class="mt-4">
                        <h5><i class="fas fa-link"></i> Enlaces de SharePoint</h5>
                        <ul class="list-group">
                            @if($producto->enlaces)
                                @foreach($producto->enlaces as $enlace)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-alt text-orange mr-2"></i>
                                            <a href="{{ $enlace->url_sharepoint }}" target="_blank" class="text-dark">
                                                {{ $enlace->nombre_original }}
                                            </a>
                                        </div>
                                        <form action="{{ route('productos.enlaces.delete', ['producto' => $producto->id, 'enlace' => $enlace->id]) }}" 
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este enlace?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-item text-muted">
                                    <i class="fas fa-exclamation-circle"></i> No hay enlaces de SharePoint disponibles.
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('productos.index') }}" class="btn btn-warning">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .text-orange { color: #fd7e14 !important; }
        .bg-orange { background-color: #fd7e14 !important; }
        .list-group-item { display: flex; align-items: center; justify-content: space-between; }
        .card-body { background-color: #f7f7f7; }
        .btn-sm { font-size: 0.875rem; padding: 0.25rem 0.5rem; }
        textarea { resize: none; }
    </style>
@stop
