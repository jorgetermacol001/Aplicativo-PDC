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
            <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- Nombre del Producto --}}
                <div class="form-group">
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                </div>

                {{-- Proyecto --}}
                <div class="form-group">
                    <label for="proyecto_id">Proyecto:</label>
                    <select name="proyecto_id" id="proyecto_id" class="form-control">
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" {{ old('proyecto_id', $producto->proyecto_id) == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipo de Pago --}}
                <div class="form-group">
                    <label for="tipo_de_pago">Tipo de Pago:</label>
                    <select name="tipo_de_pago" id="tipo_de_pago" class="form-control">
                        <option value="contado" {{ old('tipo_de_pago', $producto->tipo_de_pago) == 'contado' ? 'selected' : '' }}>Contado</option>
                        <option value="credito" {{ old('tipo_de_pago', $producto->tipo_de_pago) == 'credito' ? 'selected' : '' }}>Crédito</option>
                        <option value="contado y credito" {{ old('tipo_de_pago', $producto->tipo_de_pago) == 'contado y credito' ? 'selected' : '' }}>Contado y Crédito</option>
                    </select>
                </div>

                {{-- Solicitante --}}
                <div class="form-group">
                    <label for="usuario_solicitante_id">Solicitante:</label>
                    <select name="usuario_solicitante_id" id="usuario_solicitante_id" class="form-control">
                        @foreach($usuariosSolicitantes as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_solicitante_id', $producto->usuario_solicitante_id) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Aprobador --}}
                <div class="form-group">
                    <label for="usuario_aprobador_id">Aprobador:</label>
                    <select name="usuario_aprobador_id" id="usuario_aprobador_id" class="form-control">
                        @foreach($usuariosAprobadores as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_aprobador_id', $producto->usuario_aprobador_id) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Administrador de Compra --}}
                <div class="form-group">
                    <label for="usuario_admin_compra_id">Administrador de Compra:</label>
                    <select name="usuario_admin_compra_id" id="usuario_admin_compra_id" class="form-control">
                        @foreach($administradoresCompra as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_admin_compra_id', $producto->usuario_admin_compra_id) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Almacenista --}}
                <div class="form-group">
                    <label for="usuario_almacenista_id">Almacenista:</label>
                    <select name="usuario_almacenista_id" id="usuario_almacenista_id" class="form-control">
                        @foreach($usuariosAlmacenistas as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_almacenista_id', $producto->usuario_almacenista_id) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Contacto de Proveedores --}}
                <div class="form-group">
                    <label for="contacto_proveedores">Contacto de Proveedores:</label>
                    <input type="text" name="contacto_proveedores" id="contacto_proveedores" class="form-control" value="{{ old('contacto_proveedores', $producto->contacto_proveedores) }}">
                </div>

                {{-- Observaciones --}}
                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <textarea name="observaciones" id="observaciones" class="form-control">{{ old('observaciones', $producto->observaciones) }}</textarea>
                </div>

                {{-- Enlaces SharePoint --}}
                <div class="form-group">
                    <label for="url_sharepoint">Enlaces de SharePoint:</label>
                    <div id="sharepoint-links-container">
                        @if($producto->enlaces)
                            @foreach($producto->enlaces as $enlace)
                                <div class="input-group mb-2">
                                    <input type="text" name="enlaces_sharepoint[{{ $loop->index }}][url]" class="form-control" value="{{ $enlace->url_sharepoint }}" required>
                                    <input type="text" name="enlaces_sharepoint[{{ $loop->index }}][nombre_original]" class="form-control" value="{{ $enlace->nombre_original }}" required>
                                    <button type="button" class="btn btn-danger remove-link"><i class="fas fa-times"></i></button>
                                </div>
                            @endforeach
                        @endif
                        <div class="input-group mb-2">
                            <input type="text" name="enlaces_sharepoint[][url]" class="form-control" placeholder="Añadir enlace de SharePoint" required>
                            <input type="text" name="enlaces_sharepoint[][nombre_original]" class="form-control" placeholder="Nombre del archivo" required>
                            <button type="button" class="btn btn-danger remove-link"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" id="add-link"><i class="fas fa-plus"></i> Añadir Enlace</button>
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
        .card-header { background-color: #fd7e14 !important; border-color: #fd7e14; }
        .card-body { background-color: #f7f7f7; }
    </style>
@stop

@section('js')
<script>
    document.getElementById('add-link').addEventListener('click', function () {
        const container = document.getElementById('sharepoint-links-container');
        const newInput = `
            <div class="input-group mb-2">
                <input type="text" name="enlaces_sharepoint[][url]" class="form-control" placeholder="Añadir enlace de SharePoint" required>
                <input type="text" name="enlaces_sharepoint[][nombre_original]" class="form-control" placeholder="Nombre del enlace" required>
                <button type="button" class="btn btn-danger remove-link"><i class="fas fa-times"></i></button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newInput);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-link')) {
            e.target.closest('.input-group').remove();
        }
    });
</script>
@stop
