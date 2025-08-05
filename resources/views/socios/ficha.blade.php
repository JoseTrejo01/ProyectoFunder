@extends('adminlte::page')

@section('content_header')
    <h1>Ficha del Socio</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            {{ $socio->Nombre_Beneficiario }}
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>DNI:</strong> {{ $socio->DNI }}</li>
                <li class="list-group-item"><strong>Teléfono:</strong> {{ $socio->Telefono }}</li>
                <li class="list-group-item"><strong>Género:</strong> {{ $socio->genero }}</li>
                <li class="list-group-item"><strong>Fecha de Nacimiento:</strong> {{ $socio->fecha_nacimiento }}</li>
                <li class="list-group-item"><strong>Dirección:</strong> {{ $socio->direccion }}</li>
                <li class="list-group-item">
                        <strong>Actividades Económicas:</strong>
                          @if($socio->actividades->isEmpty())
                        <span class="text-muted">Sin registrar</span>
                         @else
                       <ul>
                      @foreach($socio->actividades as $actividad)
                 <li>
                    <strong>Tipo:</strong> {{ $actividad->Tipo }} |
                    <strong>Rubro:</strong> {{ $actividad->Rubro }} |
                    <strong>Unidad:</strong> {{ $actividad->Unidad_Medida }} |
                    <strong>Cantidad:</strong> {{ $actividad->Cantidad }}
                </li>
            @endforeach
        </ul>
    @endif
</li>
        
                <li class="list-group-item"><strong>Tipo de Socio:</strong> {{ $socio->Tipo_De_Socio }}</li>
                <li class="list-group-item"><strong>Tipo de Cargo:</strong> {{ $socio->Tipo_Cargo }}</li>
                <li class="list-group-item"><strong>Estado:</strong> {{ $socio->estado == 1 ? 'Activo' : 'Inactivo' }}</li>
                <li class="list-group-item"><strong>Creado en:</strong> {{ $socio->created_at }}</li>
                <li class="list-group-item"><strong>Última Actualización:</strong> {{ $socio->updated_at }}</li>
                <li class="list-group-item"><strong>Estado Civil:</strong> {{ $socio->estado_civil }}</li>
                <li class="list-group-item"><strong>Nivel Educativo:</strong> {{ $socio->nivel_educativo }}</li>
                <li class="list-group-item"><strong>Medio de Comunicación:</strong> {{ $socio->medio_comunicacion }}</li>
                <li class="list-group-item"><strong>Departamento:</strong> {{ $socio->departamento }}</li>
                <li class="list-group-item"><strong>Municipio:</strong> {{ $socio->municipio }}</li>
                <li class="list-group-item"><strong>Comunidad:</strong> {{ $socio->comunidad }}</li>
               
            </ul>
        </div>
        <div class="card-footer">
            <a href="{{ route('socios.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@stop
