@extends('adminlte::page')

@section('title', 'Detalle del Proyecto')

@section('content_header')
    <h1><i class="fas fa-folder-open text-orange"></i> Detalle del Proyecto</h1>
@stop

@section('content')
    <div class="card card-outline card-orange">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-project-diagram text-orange"></i> {{ $proyecto->nombre }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('proyectos.index') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p><strong><i class="fas fa-align-left text-orange"></i> Descripción:</strong></p>
                    <p>{{ $proyecto->descripcion }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong><i class="fas fa-calendar-alt text-orange"></i> Fecha de creación:</strong></p>
                    <p>{{ $proyecto->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong><i class="fas fa-clock text-orange"></i> Última actualización:</strong></p>
                    <p>{{ $proyecto->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <style>
        .text-orange {
            color: #fd7e14 !important;
        }
        .btn-warning {
            background-color: #fd7e14 !important;
            border-color: #fd7e14 !important;
        }
        .btn-warning:hover {
            background-color: #e56b0c !important;
            border-color: #e56b0c !important;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Detalle del proyecto cargado correctamente.');
    </script>
@stop
