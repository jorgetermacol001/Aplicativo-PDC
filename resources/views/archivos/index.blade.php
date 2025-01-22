@extends('adminlte::page')

@section('content')
    <div class="container my-4">
        <h1 class="text-center text-orange mb-4"><i class="fas fa-sitemap"></i> Gestión de Enlaces</h1>

        {{-- Botón para abrir el modal de filtros --}}
        <div class="mb-4 text-right">
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </div>

        {{-- Renderizar la estructura jerárquica --}}
        <ul class="list-unstyled">
            @foreach ($estructuraEnlaces as $proyecto => $productos)
                <li class="mb-3">
                    <i class="fas fa-folder text-orange toggle-folder fs-4" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#proyecto-{{ Str::slug($proyecto) }}"></i>
                    <strong class="fs-5">{{ $proyecto }}</strong>
                    <ul class="collapse mt-2" id="proyecto-{{ Str::slug($proyecto) }}">
                        @foreach ($productos as $producto => $roles)
                            <li class="mb-3">
                                <i class="fas fa-folder text-blue toggle-folder fs-5" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#producto-{{ Str::slug($producto) }}"></i>
                                <strong class="fs-5">{{ $producto }}</strong>
                                <ul class="collapse mt-2" id="producto-{{ Str::slug($producto) }}">
                                    @foreach ($roles as $rol => $enlaces)
                                        <li class="mb-3">
                                            <i class="fas fa-folder text-secondary toggle-folder fs-6" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#rol-{{ Str::slug($rol) }}"></i>
                                            <strong class="fs-6">{{ $rol }}</strong>
                                            <ul class="collapse mt-2" id="rol-{{ Str::slug($rol) }}">
                                                @foreach ($enlaces as $enlace)
                                                    <li>
                                                        <a href="{{ $enlace['url_sharepoint'] }}" target="_blank">
                                                            {{ $enlace['nombre_original'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Modal para filtros --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-orange">
                    <h5 class="modal-title" id="filterModalLabel">Filtrar Enlaces</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="GET" action="{{ route('archivos.index') }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingrese el nombre">
                        </div>
                        <div class="form-group mt-3">
                            <label for="estado_proyecto">Estado del Proyecto</label>
                                <select name="estado_proyecto" id="estado_proyecto" class="form-control">
                                @foreach ($estadoProyecto as $estado)
                                    <option value="{{ $estado }}" {{ request('estado_oc') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                        <a href="{{ route('archivos.index') }}" class="btn btn-danger">Quitar Filtros</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Colores personalizados */
    .text-orange {
        color: #ff8800; /* Color naranja para los elementos principales */
    }

    .text-blue {
        color: #007bff; /* Azul para las subcarpetas */
    }

    .text-secondary {
        color: #6c757d; /* Gris para los archivos */
    }

    /* Estilo general */
    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    ul ul {
        margin-left: 20px;
    }

    li {
        margin: 5px 0;
    }

    li strong {
        font-weight: bold;
    }

    .toggle-folder {
        margin-right: 10px;
        transition: transform 0.2s ease;
    }

    .toggle-folder:hover {
        transform: scale(1.2);
        color: #ff4500;
    }

    .toggle-folder:active {
        transform: rotate(15deg);
    }

    h1 {
        font-family: 'Arial', sans-serif;
        text-transform: uppercase;
        font-weight: bold;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inicializar collapses de Bootstrap
        const collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach(collapse => {
            new bootstrap.Collapse(collapse, { toggle: false });
        });
    });
</script>
@endsection

@section('css')
    <!-- Agregar CSS personalizado -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <!-- Agregar JS personalizado -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@stop
