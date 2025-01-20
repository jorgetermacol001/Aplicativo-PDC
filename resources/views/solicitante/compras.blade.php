@extends('adminlte::page')

@section('title', 'Mis Compras')

@section('content_header')
    <h1 class="text-orange"><i class="fas fa-shopping-cart"></i> Mis Compras</h1>
@stop

@section('content')
    <div class="card card-orange">
        <div class="card-header">
            <h3 class="card-title">Listado de Órdenes de Compra</h3>
        </div>
        <div class="card-body">
            {{-- Mensajes de éxito --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Verificar si hay compras --}}
            @if($productos->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No tienes órdenes de compra registradas.
                </div>
            @else
                <table class="table table-bordered table-hover">
                    <thead class="bg-orange text-white">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Proyecto</th>
                            <th>Tipo de Pago</th>
                            <th>Estado OC</th>
                            <th>Estado CP</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->proyecto->nombre }}</td>
                                <td>{{ ucfirst($producto->tipo_de_pago) }}</td>
                                <td>
                                    <span class="badge {{ $producto->estado_oc === 'OC creada' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $producto->estado_oc }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $producto->estado_cp === 'Liberado' ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ $producto->estado_cp }}
                                    </span>
                                </td>
                                <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{-- Mostrar acciones solo si el estado es producto aprobada --}}
                                    @if($producto->estado_oc === 'OC creada')
                                        <form action="{{ route('solicitante.pendientes', $producto->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-orange" onclick="return confirm('¿Deseas realizar esta acción?');">
                                                <i class="fas fa-play-circle"></i> Revisar OC
                                            </button>
                                        </form>
                                    {{-- Accion de compra corregida --}}
                                    @elseif($producto->estado_oc === 'Compra corrección')
                                    <form method="POST" action="{{ route('solicitante.compraCorreccion', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-check"></i> Compra Corregida
                                        </button>
                                    @else
                                        <span class="text-muted">Acción no disponible</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>

    </div>
                    {{-- Navegación de paginación --}}
                    <div class="d-flex justify-content-center mt-3">
                        <ul class="pagination pagination-sm">
                            {{ $productos->links('pagination::bootstrap-4') }}
                        </ul>
                    </div>
@stop

@section('css')
    <style>
        .text-orange {
            color: #FF851B !important;
        }
        .btn-orange {
            background-color: #FF851B;
            color: white;
        }
        .btn-orange:hover {
            background-color: #FF7700;
            color: white;
        }
        .bg-orange {
            background-color: #FF851B !important;
        }
        .badge-orange {
            background-color: #FF851B;
            color: white;
        }
        .card-orange {
            border-top: 3px solid #FF851B;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Vista de Mis Compras cargada.');
    </script>
@stop
