@extends('adminlte::page')

@section('title', 'Gestión de Compras')

@section('content_header')
    <h1 class="text-orange"><i class="fas fa-clipboard-list"></i> Gestión de Compras</h1>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-orange text-white">
            <h3 class="card-title"><i class="fas fa-list"></i> Listado de Compras</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-orange text-white">
                    <tr>
                        <th>Nombre</th>
                        <th>Solicitante</th>
                        <th>Estado Actual</th>
                        <th>Fecha de Solicitud</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->solicitante->name }}</td>
                            <td>{{ $producto->estado_oc }}</td>
                            <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-orange btn-sm">
                                    <i class="fas fa-eye"></i> Revisar
                                </a>

                                {{-- Acciones según el estado --}}
                                @if($producto->estado_oc === 'Compra aprobada')
                                    <a href="{{ route('admincompra.crearOC', $producto->id) }}" class="btn btn-primary btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                        <i class="fas fa-file-alt"></i> Crear O.C
                                    </a>
                                    <a href="{{ route('admincompra.correccionOC', $producto->id) }}" class="btn btn-danger" onclick="return confirm('¿Deseas realizar esta acción?');">
                                        <i class="fas fa-edit"></i> Correccion O.C
                                    </a>
                                @elseif($producto->estado_oc === 'OC liberada')
                                    <form method="POST" action="{{ route('admincompra.enviarOC', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-check"></i> Enviar OC
                                        </button>
                                    </form>
                                    @elseif($producto->estado_oc === 'OC enviada')
                                    <form method="POST" action="{{ route('admincompra.ComfirmadaOC', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-check"></i> Confirmar OC
                                        </button>
                                    </form>
                                @elseif($producto->estado_pago === 'pago pendiente')
                                    <form method="POST" action="{{ route('admincompra.pagoprogramado', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-calendar-alt"></i> Programar Pago
                                        </button>
                                    </form>
                                @elseif($producto->estado_pago === 'pago liberado')
                                    <form method="POST" action="{{ route('admincompra.enviarCP', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-dark btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-truck"></i> Enviar C.P
                                        </button>
                                    </form>
                                    @endif
                            </td>
                        </tr>
                    @empty   
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay compras registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
                            {{-- Navegación de paginación --}}
                            <div class="d-flex justify-content-center mt-3">
                                <ul class="pagination pagination-sm">
                                    {{ $productos->links('pagination::bootstrap-4') }}
                                </ul>
                            </div>
    </div>
@stop

@section('css')
    <style>
        .bg-orange {
            background-color: #fd7e14;
        }
        .text-orange {
            color: #fd7e14 !important;
        }
        .btn-orange {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: #fff;
        }
        .btn-orange:hover {
            background-color: #e76c10;
            border-color: #e76c10;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #005cbf;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #fff;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: #fff;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #fd7e14 !important;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            border-bottom: 2px solid #e76c10;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Vista de gestión de compras cargada correctamente.");
    </script>
@stop
