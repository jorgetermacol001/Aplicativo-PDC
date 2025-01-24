@extends('adminlte::page')

@section('title', 'Detalle del Producto')

@section('content_header')
    <h1 class="text-center text-orange">
        <i class="fas fa-box-open"></i> Detalle del Producto
    </h1>
@stop

@section('content')
    <div class="card shadow-lg border-top-orange">
        <div class="card-header bg-orange text-white">
            <h3 class="card-title">
                <i class="fas fa-info-circle"></i> Información del Producto: {{ $producto->nombre }}
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información del producto -->
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-light text-orange">
                            <strong><i class="fas fa-box"></i> Información Básica</strong>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-tag"></i> Nombre
                                </dt>
                                <dd class="col-sm-7">{{ $producto->nombre }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-project-diagram"></i> Proyecto
                                </dt>
                                <dd class="col-sm-7">{{ $producto->proyecto ? $producto->proyecto->nombre : 'No disponible' }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-credit-card"></i> Tipo de Pago
                                </dt>
                                <dd class="col-sm-7">{{ $producto->tipo_de_pago }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-sticky-note"></i> Observaciones
                                </dt>
                                <dd class="col-sm-7">{{ $producto->observaciones }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-phone"></i> Contacto de Proveedores
                                </dt>
                                <dd class="col-sm-7">{{ $producto->contacto_proveedores }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-light text-orange">
                            <strong><i class="fas fa-calendar-alt"></i> Fechas</strong>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-calendar-alt"></i> Fecha de Envío
                                </dt>
                                <dd class="col-sm-7">{{ $producto->fecha_envio_oc }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-calendar-check"></i> Fecha de Aprobación
                                </dt>
                                <dd class="col-sm-7">{{ $producto->fecha_aprobacion_oc }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-file-invoice"></i> Fecha Fin
                                </dt>
                                <dd class="col-sm-7">{{ $producto->fecha_fin }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-calendar-day"></i> Fecha de Entrega de Material
                                </dt>
                                <dd class="col-sm-7">{{ $producto->fecha_entrega_materia }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estados del producto -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-light text-orange">
                            <strong><i class="fas fa-info-circle"></i> Estados</strong>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-city"></i> Estado de la Solicitud
                                </dt>
                                <dd class="col-sm-7">{{ $producto->estado_solicitud }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-truck"></i> Estado de Entrega
                                </dt>
                                <dd class="col-sm-7">{{ $producto->estado_entrega }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-file-invoice"></i> Estado de OC
                                </dt>
                                <dd class="col-sm-7">{{ $producto->estado_oc }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-file-invoice"></i> Estado del CP
                                </dt>
                                <dd class="col-sm-7">{{ $producto->estado_cp }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Roles Asociados -->
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header bg-light text-orange">
                            <strong><i class="fas fa-users"></i> Roles Asociados</strong>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-user"></i> Solicitante
                                </dt>
                                <dd class="col-sm-7">{{ $producto->solicitante ? $producto->solicitante->name : 'No disponible' }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-user-shield"></i> Aprobador
                                </dt>
                                <dd class="col-sm-7">{{ $producto->aprobador ? $producto->aprobador->name : 'No disponible' }}</dd>
                                <dt class="col-sm-5 text-orange">
                                    <i class="fas fa-user-cog"></i> Admin Compra
                                </dt>
                                <dd class="col-sm-7">{{ $producto->adminCompra ? $producto->adminCompra->name : 'No disponible' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos Adjuntos -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-header bg-light text-orange">
                            <strong><i class="fas fa-paperclip"></i> Datos Adjuntos</strong>
                        </div>
                        <div class="card-body">
                            @if (!empty($estructuraEnlaces))
                                @foreach ($estructuraEnlaces as $rol => $enlaces)
                                    <div class="mb-3">
                                        <h5 class="text-primary text-capitalize">Rol: {{ $rol }}</h5>
                                        <ul class="list-group">
                                            @foreach ($enlaces as $enlace)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <strong>{{ $enlace['nombre_original'] }}</strong>
                                                    <a href="{{ $enlace['url'] }}" target="_blank" class="btn btn-sm btn-link">Abrir</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No hay enlaces de SharePoint asociados a este producto.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agregar Enlace -->
            <div class="card mt-3">
                <div class="card-header bg-light text-orange">
                    <strong><i class="fas fa-link"></i> Agregar Enlace</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('productos.agregarArchivo', $producto) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombre_original" class="text-orange">Nombre del Enlace:</label>
                            <input type="text" name="nombre_original" id="nombre_original" 
                                   class="form-control @error('nombre_original') is-invalid @enderror" 
                                   placeholder="Ejemplo: Documentación del proyecto">
                            @error('nombre_original')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="url_sharepoint" class="text-orange">URL del Enlace:</label>
                            <input type="url" name="url_sharepoint" id="url_sharepoint" 
                                   class="form-control @error('url_sharepoint') is-invalid @enderror" 
                                   placeholder="Ejemplo: https://sharepoint.com/...">
                            @error('url_sharepoint')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-link"></i> Agregar Enlace
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-center">
            <a href="{{ route('productos.index') }}" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
@stop

@section('css')
    <style>
        .text-orange {
            color: #fd7e14 !important;
        }

        .bg-orange {
            background-color: #fd7e14 !important;
        }

        .border-top-orange {
            border-top: 4px solid #fd7e14 !important;
        }

        .card-header {
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Vista de detalle cargada correctamente.');
    </script>
@stop
