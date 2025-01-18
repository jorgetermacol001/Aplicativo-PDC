@extends('adminlte::page')

@section('content')
<div class="container mt-4">
    <h1 class="text-orange"><i class="fas fa-bell"></i> Tus Notificaciones</h1>
    
    @if($notificaciones->isEmpty())
        <p class="text-muted">No tienes notificaciones en este momento.</p>
    @else
        <div class="row">
            @foreach ($notificaciones as $notificacion)
                <div class="col-md-6 mb-4">
                    <div class="card 
                                {{ $notificacion->estado == 'Pendiente' ? 'bg-orange text-white' : 'bg-light' }} 
                                shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $notificacion->data['mensaje'] }}</h5>
                            <p class="card-text">
                                <small class="{{ $notificacion->read_at ? 'text-muted' : 'text-white-50' }}">
                                    {{ $notificacion->created_at->diffForHumans() }}
                                </small>
                            </p>
                            
                            @if(isset($notificacion->data['producto_id']))
                                <a href="{{ route('productos.show', $notificacion->data['producto_id']) }}" 
                                   class="btn btn-outline-light btn-sm">
                                    Ver Detalles
                                </a>
                            @endif
                            
                            @if(!$notificacion->read_at && $notificacion->estado == 'Pendiente')
                                <!-- Botón para marcar como leída -->
                                <form action="{{ route('notificaciones.marcarComoLeida', $notificacion->id) }}" 
                                      method="POST" 
                                      style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-light btn-sm">
                                        Marcar como leída
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('css')
    <style>
        /* Estilo para las cartas */
        .card {
            border-radius: 12px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .bg-orange {
            background-color: #fd7e14 !important;
        }
        .btn-outline-light {
            border-color: #fff;
            color: #fff;
        }
        .btn-outline-light:hover {
            background-color: #fff;
            color: #fd7e14;
        }
        .text-orange {
            color: #fd7e14 !important;
        }
        .text-white-50 {
            color: rgba(255, 255, 255, 0.6) !important;
        }
    </style>
@endsection

@section('js')
    <script>
        console.log("Notificaciones estilizadas con diseño de cartas.");
    </script>
@endsection
