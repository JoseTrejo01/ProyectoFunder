@extends('adminlte::page')

@section('title', 'Lista de Criterios')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Lista de Criterios</h1>
@stop

@section('content')

    {{-- ALERTA DE ÉXITO --}}
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- BOTÓN CREAR --}}
    <a href="{{ route('criterio.create') }}" 
       class="btn btn-primary mb-3 font-weight-bold"
       aria-label="Crear nuevo criterio">
        Nuevo Criterio
    </a>

    {{-- TABLA DE CRITERIOS --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow-sm" 
               role="table" 
               aria-label="Tabla de criterios registrados">

            <thead class="thead-dark">
                <tr>
                    <th scope="col">Variable</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Subíndice</th>
                    <th scope="col" style="width: 130px;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($criterios as $criterio)
                    <tr>
                        <td>{{ $criterio->variable }}</td>
                        <td>{{ $criterio->descripcion }}</td>
                        <td>{{ $criterio->subindice }}</td>

                        <td class="text-center">

                            {{-- BOTÓN EDITAR --}}
                            <a href="{{ route('criterio.edit', $criterio) }}"
                               class="btn btn-warning btn-sm"
                               aria-label="Editar criterio {{ $criterio->variable }}">
                                Editar
                            </a>

                            {{-- BOTÓN ELIMINAR --}}
                            <form action="{{ route('criterio.destroy', $criterio) }}" 
                                  method="POST"
                                  class="d-inline-block delete-form"
                                  aria-label="Formulario para eliminar criterio">

                                @csrf
                                @method('DELETE')

                                <button type="submit" 
                                    class="btn btn-danger btn-sm delete-btn"
                                    aria-label="Eliminar criterio {{ $criterio->variable }}">
                                    Eliminar
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No hay criterios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@stop

{{-- ======================================
     CONFIRMACIÓN ACCESIBLE (Opcional)
   ====================================== --}}
@section('js')
<script>
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (confirm('¿Estás seguro de eliminar este criterio?')) {
            this.submit();
        }
    });
});
</script>
@endsection
