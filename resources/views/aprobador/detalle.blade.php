@extends('adminlte::page')

@section('title', 'Detalle de Compra')

@section('content_header')
    <h1 class="text-center text-orange">
        <i class="fas fa-shopping-cart"></i> Detalle de Compra
    </h1>
@stop

@section('content')
    <div class="card shadow border-0">
        <div class="card-header bg-orange text-white">
            <h3 class="card-title">
                <i class="fas fa-box"></i> Compra: {{ $producto->nombre }}
            </h3>
        </div>
        <div class="card-body">
            <p><strong>Solicitante:</strong> {{ $producto->solicitante->name }}</p>
            <p><strong>Estado:</strong> 
                <span class="badge badge-warning">{{ $producto->estado_oc }}</span>
            </p>
            <p><strong>Fecha de Creación:</strong> {{ $producto->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Última Actualización:</strong> {{ $producto->updated_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <form method="POST" action="{{ route('aprobador.aprobar', $producto->id) }}" style="display:inline-block;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Aprobar
                </button>
            </form>

            <form method="POST" action="{{ route('aprobador.rechazar', $producto->id) }}" style="display:inline-block;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Rechazar
                </button>
            </form>@extends('adminlte::page')

            @section('title', 'Detalle de Compra')
            
            @section('content_header')
                <h1 class="text-center text-orange">
                    <i class="fas fa-shopping-cart"></i> Detalle de Compra
                </h1>
            @stop
            
            @section('content')
                <div class="card shadow border-0">
                    <div class="card-header bg-orange text-white">
                        <h3 class="card-title">
                            <i class="fas fa-box"></i> Compra: {{ $producto->nombre }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Solicitante:</strong> {{ $producto->solicitante->name }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge badge-warning">{{ $producto->estado_oc }}</span>
                        </p>
                        <p><strong>Fecha de Creación:</strong> {{ $producto->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Última Actualización:</strong> {{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        {{-- Botón Aprobar --}}
                        <form method="POST" action="{{ route('aprobador.aprobar', $producto->id) }}" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Aprobar
                            </button>
                        </form>
            
                        {{-- Botón Rechazar --}}
                        <form method="POST" action="{{ route('aprobador.rechazar', $producto->id) }}" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Rechazar
                            </button>
                        </form>
            
                        {{-- Botón Liberar (solo si el estado es Revisado OC) --}}
                        @if($producto->estado_oc === 'Revisado OC' && $producto->usuario_aprobador_id === auth()->id())
                            <form method="POST" action="{{ route('aprobador.liberar', $producto->id) }}" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary" onclick="return confirm('¿Estás seguro de liberar esta OC?');">
                                    <i class="fas fa-unlock"></i> Liberar
                                </button>
                            </form>
                        @endif
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
                    .btn-success {
                        background-color: #28a745;
                        border-color: #28a745;
                    }
                    .btn-success:hover {
                        background-color: #218838;
                        border-color: #1e7e34;
                    }
                    .btn-danger {
                        background-color: #dc3545;
                        border-color: #dc3545;
                    }
                    .btn-danger:hover {
                        background-color: #c82333;
                        border-color: #bd2130;
                    }
                    .btn-primary {
                        background-color: #007bff;
                        border-color: #007bff;
                    }
                    .btn-primary:hover {
                        background-color: #0056b3;
                        border-color: #004085;
                    }
                    .card {
                        border-radius: 12px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                    }
                    .card-header h3 {
                        font-size: 1.5rem;
                    }
                </style>
            @stop
            
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
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .card-header h3 {
            font-size: 1.5rem;
        }
    </style>
@stop
