@extends('adminlte::page')

@section('title', 'Proyectos')

@section('content_header')
    <h1 class="text-orange">Listado de Proyectos</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-orange">
            <h3 class="card-title">
                <i class="fas fa-list-alt"></i> Proyectos
            </h3>
            <div class="card-tools">
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#filterModal">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="{{ route('proyectos.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Proyecto
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="bg-orange">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado del Proyecto</th>
                        <th>Creado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyectos as $proyecto)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $proyecto->nombre }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($proyecto->descripcion, 50, '...') }}</td>
                            <td>{{ $proyecto->estado_proyecto }}</td>
                            <td>{{ $proyecto->user->name }}</td>
                            <td>
                                <a href="{{ route('proyectos.show', $proyecto->id) }}" class="btn btn-primary btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('proyectos.edit', $proyecto->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form action="{{ route('proyectos.terminar', $proyecto->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Terminar" onclick="return confirm('¿Estás seguro de terminar este proyecto?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('proyectos.destroy', $proyecto->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este proyecto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay proyectos disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

        <!-- Modal para filtros -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-orange">
                        <h5 class="modal-title" id="filterModalLabel">Filtrar Proyectos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="GET" action="{{ route('proyectos.index') }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingrese el nombre" value="{{ request('nombre') }}">
                            </div>
                            <div class="form-group">
                                <label for="estado_proyecto">Estado del Proyecto</label>
                                <select name="estado_proyecto" id="estado_proyecto" class="form-control">
                                    @foreach ($estadoProyecto as $estado)
                                        <option value="{{ $estado }}" {{ request('estado_proyecto') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                            <a href="{{ route('proyectos.index') }}" class="btn btn-danger">Quitar Filtros</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@stop

@section('css')
    <style>
        .bg-orange {
            background-color: #ff851b !important;
            color: white !important;
        }
        .text-orange {
            color: #ff851b !important;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Vista de listado de proyectos cargada correctamente.');
    </script>
@stop
