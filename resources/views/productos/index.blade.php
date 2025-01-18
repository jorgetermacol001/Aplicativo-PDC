@extends('adminlte::page')

@section('title', 'Listado de Productos')

@section('content_header')
    <h1 class="text-center text-orange font-weight-bold">
        <i class="fas fa-boxes"></i> Listado de Productos
    </h1>
@stop

@section('content')
    <div class="card shadow">
        {{-- Encabezado con botones --}}
        <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-list"></i> Productos Registrados
            </h3>
            <div class="d-flex">
                {{-- Botón de Filtro --}}
                <button type="button" class="btn btn-outline-light btn-sm mr-2" data-toggle="modal" data-target="#filterModal">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                {{-- Botón de Crear Producto --}}
                <a href="{{ route('productos.create') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-plus-circle"></i> Crear Producto
                </a>

            </div>
        </div>

        {{-- Modal de Filtros --}}
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-orange text-white">
                        <h5 class="modal-title" id="filterModalLabel"><i class="fas fa-filter"></i> Filtrar Productos</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="GET" action="{{ route('productos.index') }}">
                        <div class="modal-body">
                            <div class="row">
                                {{-- Filtros de Tipo de Pago --}}
                                <div class="col-md-6 mb-3">
                                    <label for="tipo_de_pago" class="form-label">Tipo de Pago</label>
                                    <select name="tipo_de_pago" id="tipo_de_pago" class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        <option value="credito" {{ request('tipo_de_pago') == 'credito' ? 'selected' : '' }}>Crédito</option>
                                        <option value="contado" {{ request('tipo_de_pago') == 'contado' ? 'selected' : '' }}>Contado</option>
                                    </select>
                                </div>
                                {{-- Filtro por Nombre --}}
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input 
                                        type="text" 
                                        name="nombre" 
                                        id="nombre" 
                                        class="form-control form-control-sm" 
                                        placeholder="Buscar por nombre"
                                        value="{{ request('nombre') }}"
                                    >
                                </div>
                                {{-- Filtro de Estado O.C --}}
                                <div class="col-md-6 mb-3">
                                    <label for="estado_oc" class="form-label">Estado O.C</label>
                                    <select name="estado_oc" id="estado_oc" class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        @foreach ($estados_oc as $estado)
                                            <option value="{{ $estado }}" {{ request('estado_oc') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Filtro de Estado C.P --}}
                                <div class="col-md-6 mb-3">
                                    <label for="estado_cp" class="form-label">Estado C.P</label>
                                    <select name="estado_cp" id="estado_cp" class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        @foreach ($estados_cp as $estado)
                                            <option value="{{ $estado }}" {{ request('estado_cp') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Filtro de Estado de pago --}}
                                <div class="col-md-6 mb-3">
                                    <label for="estados_pago" class="form-label">Estado de Pago</label>
                                    <select name="estados_pago" id="estados_pago" class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        @foreach ($estados_pago as $estado)
                                            <option value="{{ $estado }}" {{ request('estado_pago') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Filtro de Estado de proyecto --}}
                                <div class="col-md-6 mb-3">
                                    <label for="estado_solicitud" class="form-label">Estado de la solicitud</label>
                                    <select name="estado_solicitud" id="estado_solicitud" class="form-control form-control-sm">
                                        <option value="">Todos</option>
                                        @foreach ($estados_solicitud as $estado)
                                            <option value="{{ $estado }}" {{ request('estado_solicitud') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                        @endforeach
                                    </select>
                                </div>                                
                                {{-- Filtro de Fecha de Inicio --}}
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control form-control-sm" value="{{ request('fecha_inicio') }}">
                                </div>
                                {{-- Filtro de Fecha de Fin --}}
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control form-control-sm" value="{{ request('fecha_fin') }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-orange btn-sm">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabla de Productos --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="bg-orange text-white">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo de Pago</th>
                        <th>Estado O.C</th>
                        <th>Estado C.P</th>
                        <th>Estado de pago</th>
                        <th>Estado de Entrega</th>
                        <th>Solicitante</th>
                        <th>Aprobador</th>
                        <th>Creación</th>
                        <th>Actualización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        @php
                            // Asignación del color del badge para estado_oc
                            $badgeColorOC = match ($producto->estado_oc) {
                                'Compra aprobada' => 'success',
                                'Compra solicitada' => 'btn btn-warning',
                                'Pendiente OC' => 'warning',
                                'Revisión OC' => 'info',
                                'OC liberada' => 'primary',
                                'OC por enviar' => 'secondary',
                                'OC enviada' => 'primary',
                                'Cancelada' => 'danger',
                                'Rechazada' => 'dark',
                                'OC creada' => 'light',
                                'OC por confirmar' => 'warning',
                                'OC confirmada' => 'dark',
                                'Compra corrección' => 'danger',
                                'OC corrección' => 'danger',
                                'Compra corregida' => '>Warning',
                                default => 'info',
                            };

                            // Asignación del color del badge para estado_pago
                            $badgeColorpago = match ($producto->estado_pago) {
                                'pago pendiente' => 'warning',
                                'pago programado' => 'info',
                                'pago liberado' => 'primary',
                                default => 'info',
                                 
                            };

                            // Asignación del color del badge para estado_cp
                            $badgeColorCP = match ($producto->estado_cp) {
                                'Liberado' => 'success',
                                'Pendiente CP' => 'warning',
                                'Cancelado' => 'danger',
                                'CP por liberar' => 'purple',
                                'CP confirmado' => 'success-light',
                                default => 'secondary',
                            };

                            // Asignación del color del badge para estado_entrega
                            $badgeColorEntrega = match ($producto->estado_entrega) {
                                'Entrega confirmada' => 'success',
                                'Entrega parcial' => 'yellow',
                                'Pendiente de entrega' => 'lightgray',
                                default => 'info',
                            };
                        @endphp
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>
                                <span class="badge badge-{{ $producto->tipo_de_pago === 'credito' ? 'warning' : 'success' }}">
                                    {{ ucfirst($producto->tipo_de_pago) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $badgeColorOC }}">
                                    {{ $producto->estado_oc }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $badgeColorCP }}">
                                    {{ $producto->estado_cp }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $badgeColorpago }}">
                                    {{ $producto->estado_pago }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $badgeColorEntrega }}">
                                    {{ $producto->estado_entrega }}
                                </span>
                            </td>
                            <td>{{ $producto->solicitante->name ?? 'No asignado' }}</td>
                            <td>{{ $producto->aprobador->name ?? 'No asignado' }}</td>
                            <td>{{ $producto->created_at->format('d/m/Y') }}</td>
                            <td>{{ $producto->updated_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-info btn-sm d-inline">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                
                                @can('compras.edit')
                                    <form action="{{ route('productos.edit', $producto->id) }}" method="GET" class="d-inline">
                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </form>
                                @endcan
                                
                                @can('compras.delete')
                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta solicitud?')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                @endcan

                                @can('compras.edit')
                                {{-- Botón para terminar el proyecto --}}
                                   <form action="{{ route('productos.terminar', $producto->id) }}" method="POST" class="d-inline" >
                                       @csrf
                                       @method('PUT')
                                       <button type="submit" class="btn btn-dark btn-sm" onclick="return confirm('¿terminar esta solicitud?')">
                                           <i class="fas fa-check"></i> Terminar Solicitud
                                       </button>
                                   </form>
                               @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">No hay productos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="card-footer d-flex justify-content-center">
            {{ $productos->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
@stop

@section('css')
    <style>
        .text-orange {
            color: #e67e22 !important;
        }
        .bg-orange {
            background-color: #e67e22 !important;
            border-color: #e67e22 !important;
        }
        .btn-orange {
            background-color: #e67e22 !important;
            color: #fff;
        }
        .btn-orange:hover {
            background-color: #cf6b19 !important;
            color: #fff;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
@stop
