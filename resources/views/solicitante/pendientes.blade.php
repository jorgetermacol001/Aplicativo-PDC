@extends('adminlte::page')

@section('title', 'Revisión de OCs')

@section('content_header')
    <h1 class="text-orange">Órdenes de Compra Pendientes</h1>
@stop

@section('content')
    <div class="card card-orange">
        <div class="card-header">
            <h3 class="card-title">Listado de OCs para Revisión</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($productos->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No hay órdenes de compra pendientes para revisión.
                </div>
            @else
                <table class="table table-striped table-hover">
                    <thead class="bg-orange text-white">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Proyecto</th>
                            <th>Estado</th>
                            <th>Fecha de creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->proyecto }}</td>
                                <td>
                                    <span class="badge badge-warning">{{ $producto->estado_oc }}</span>
                                </td>
                                <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('oc.revisar', $producto->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-orange" onclick="return confirm('¿Deseas realizar esta acción?');">
                                            <i class="fas fa-check-circle"></i> Revisar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
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
    </style>
@stop

@section('js')
    <script>
        console.log('Vista de revisión de OCs cargada.');
    </script>
@stop
