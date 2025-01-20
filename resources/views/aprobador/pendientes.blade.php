@extends('adminlte::page')

@section('title', 'Solicitudes Pendientes')

@section('content_header')
    <h1 class="text-orange"><i class="fas fa-shopping-cart"></i> Solicitudes Pendientes</h1>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-orange text-white">
            <h3 class="card-title"><i class="fas fa-list"></i> Listado de Solicitudes Pendientes</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-orange text-white">
                    <tr>
                        <th>Nombre</th>
                        <th>Solicitante</th>
                        <th>Fecha de Solicitud</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->solicitante->name }}</td>
                            <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                {{-- Botón Revisar --}}
                                <a href="{{ route('aprobador.show', $producto->id) }}" class="btn btn-orange btn-sm">
                                    <i class="fas fa-eye"></i> Revisar
                                </a>

                                {{-- Botón Aprobar --}}
                                @if($producto->estado_oc === 'Compra solicitada' || $producto->estado_oc === 'OC corrección' || $producto->estado_oc === 'Compra corregida')
                                    <form method="POST" action="{{ route('aprobador.aprobar', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                    </form>
                                @endif

                                {{-- Boton Correcion --}}
                                @if($producto->estado_oc === 'Compra solicitada')
                                    <form method="POST" action="{{ route('aprobador.correcion', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-exclamation-circle"></i> corrección
                                        </button>
                                    </form>
                                @endif                             

                                {{-- Botón Liberar --}}
                                @if($producto->estado_oc === 'Revisado OC')
                                    <form method="POST" action="{{ route('aprobador.liberar', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-unlock"></i> Liberar OC
                                        </button>
                                    </form>
                                @endif

                                {{-- Botón Liberar Pago --}}
                                @if($producto->estado_pago === 'pago programado')
                                    <form method="POST" action="{{ route('aprobador.pagoliberado', $producto->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sucess btn-sm" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-unlock"></i> Liberar pago
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No hay solicitudes pendientes.</td>
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
        /* Colores personalizados */
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

        /* Tabla estilizada */
        .table-bordered th, .table-bordered td {
            border: 1px solid #fd7e14 !important;
        }

        /* Sombra para tarjetas */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Estilo para el encabezado */
        .card-header {
            border-bottom: 2px solid #e76c10;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Vista estilizada con colores naranjas.");
    </script>
@stop
