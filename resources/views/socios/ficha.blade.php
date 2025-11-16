@extends('adminlte::page')

@section('title', 'Ficha del Socio')

@section('content_header')
<h1 id="titulo-ficha-socio" class="fw-bold">Ficha del Socio</h1>
@stop

@section('content')
<div role="main" aria-labelledby="titulo-ficha-socio">

    <div class="card shadow-sm border-0">

        {{-- ENCABEZADO --}}
        <div class="card-header text-white fw-semibold" 
             style="background-color:#0D47A1;"
             role="heading"
             aria-level="2">
            {{ $socio->Nombre_Beneficiario }}
        </div>

        {{-- CUERPO --}}
        <div class="card-body">

            <ul class="list-group" role="list">

                <li class="list-group-item">
                    <strong>DNI:</strong> {{ $socio->DNI }}
                </li>

                <li class="list-group-item">
                    <strong>Teléfono:</strong> {{ $socio->Telefono }}
                </li>

                <li class="list-group-item">
                    <strong>Género:</strong> {{ $socio->genero }}
                </li>

                <li class="list-group-item">
                    <strong>Fecha de Nacimiento:</strong> {{ $socio->fecha_nacimiento }}
                </li>

                <li class="list-group-item">
                    <strong>Dirección:</strong> {{ $socio->direccion }}
                </li>

                {{-- ACTIVIDADES ECONÓMICAS --}}
                <li class="list-group-item">
                    <strong>Actividades Económicas:</strong>

                    @if($socio->actividades->isEmpty())
                        <span class="text-muted d-block mt-1">Sin registrar</span>
                    @else
                        <ul class="mt-2" role="list">
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

                <li class="list-group-item">
                    <strong>Tipo de Socio:</strong> {{ $socio->Tipo_De_Socio }}
                </li>

                <li class="list-group-item">
                    <strong>Tipo de Cargo:</strong> {{ $socio->Tipo_Cargo ?? 'N/A' }}
                </li>

                <li class="list-group-item">
                    <strong>Estado:</strong>
                    {{ $socio->estado == 1 ? 'Activo' : 'Inactivo' }}
                </li>

                <li class="list-group-item">
                    <strong>Creado en:</strong> {{ $socio->created_at }}
                </li>

                <li class="list-group-item">
                    <strong>Última Actualización:</strong> {{ $socio->updated_at }}
                </li>

                <li class="list-group-item">
                    <strong>Estado Civil:</strong> {{ $socio->estado_civil }}
                </li>

                <li class="list-group-item">
                    <strong>Nivel Educativo:</strong> {{ $socio->nivel_educativo }}
                </li>

                <li class="list-group-item">
                    <strong>Medio de Comunicación:</strong> {{ $socio->medio_comunicacion }}
                </li>

                <li class="list-group-item">
                    <strong>Departamento:</strong> {{ $socio->departamento }}
                </li>

                <li class="list-group-item">
                    <strong>Municipio:</strong> {{ $socio->municipio }}
                </li>

                <li class="list-group-item">
                    <strong>Comunidad:</strong> {{ $socio->comunidad }}
                </li>

            </ul>
        </div>

        {{-- FOOTER --}}
        <div class="card-footer text-end">

            <a href="{{ route('socios.index') }}"
               class="btn fw-semibold"
               style="background-color:#424242; color:white;"
               aria-label="Volver al listado de socios">
                Volver
            </a>

        </div>

    </div>

</div>
@stop
