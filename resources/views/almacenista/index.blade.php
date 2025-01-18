@extends('adminlte::page')

@section('title', 'Productos Almacenista')

@section('content_header')
    <h1 style="color: #FF8C00;">Productos Asignados al Almacenista</h1>
@stop

@section('content')
    <!-- Card to display the products -->
    <div class="card border border-orange">
        <div class="card-header bg-orange text-white">
            <h3 class="card-title">Lista de Productos</h3>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <table id="productosTable" class="table table-bordered table-striped">
                <thead class="bg-orange text-white">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Proyecto</th>
                        <th>Estado Entrega</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->proyecto ? $producto->proyecto->nombre : 'No asignado' }}</td>
                            <td>{{ $producto->estado_entrega }}</td>
                            <td>{{ $producto->created_at->format('d/m/Y') }}</td>
                            <td>
                                <!-- Show button for details -->
                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <!-- Entrega Parcial button -->
                                <form action="{{ route('almacenista.EntregaParcial', $producto->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de realizar la entrega parcial?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-warning" title="Entrega parcial">
                                        <i class="fas fa-truck-loading"></i> Parcial
                                    </button>
                                </form> 
                                <!-- Entrega Total button -->
                                <form action="{{ route('almacenista.EntregaTotal', $producto->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de realizar la entrega total?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-orange text-white" title="Entrega total">
                                        <i class="fas fa-check-circle"></i> Total
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <!-- AdminLTE Styles -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <style>
        .bg-orange {
            background-color: #FF8C00 !important;
        }
        .btn-orange {
            background-color: #FF8C00;
            border-color: #FF8C00;
        }
        .btn-orange:hover {
            background-color: #FF7F00;
            border-color: #FF7F00;
        }
        .border-orange {
            border-color: #FF8C00 !important;
        }
    </style>
@stop

@section('js')
    <!-- AdminLTE Scripts -->
    <script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#productosTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                }
            });
        });
    </script>
@stop
