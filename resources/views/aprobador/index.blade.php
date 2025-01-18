@extends('adminlte::page')

@section('title', 'Detalle de productos')

@section('content_header')
    <h1>Detalle de productos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Compra: {{ $productos->nombre }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Solicitante:</strong> {{ $productos->solicitante->name }}</p>
            <p><strong>Estado:</strong> 
                @if($productos->estado == 'pendiente')
                    <span class="badge badge-warning">Pendiente</span>
                @elseif($productos->estado == 'aprobada')
                    <span class="badge badge-success">Aprobada</span>
                @elseif($productos->estado == 'rechazada')
                    <span class="badge badge-danger">Rechazada</span>
                @endif
            </p>
            <p><strong>Fecha de Creación:</strong> {{ $productos->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Última Actualización:</strong> {{ $productos->updated_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="card-footer">
            @if(auth()->user()->role == 'aprobador' && $productos->estado == 'pendiente')
                <form method="POST" action="{{ route('solictud.aprobar', $productos) }}" style="display:inline-block;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                </form>

                <form method="POST" action="{{ route('solictud.rechazar', $productos) }}" style="display:inline-block;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Rechazar
                    </button>
                </form>
            @else
                <p class="text-muted">No se pueden realizar acciones sobre esta productos.</p>
            @endif
        </div>
    </div>
@stop
