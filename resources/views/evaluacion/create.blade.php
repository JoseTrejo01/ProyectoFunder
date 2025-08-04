@extends('adminlte::page')

@section('title', 'Nueva Evaluación')

@section('content_header')
    <h1>Nueva Evaluación</h1>
@endsection

@section('content')
<form action="{{ route('evaluacion.store') }}" method="POST">
    @csrf

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="tabForm" role="tablist">
        @foreach([
            'general' => 'Información General',
            'juridica' => 'Naturaleza Jurídica',
            'consejo' => 'Consejo de Administración',
            'tesorero' => 'Tesorero',
            'credito' => 'Comité de Crédito',
            'fiscalizadora' => 'Junta Fiscalizadora',
            'registros' => 'Registros',
            'prestamos' => 'Préstamos',
            'financiera' => 'Evaluación Financiera'
        ] as $id => $label)
        <li class="nav-item">
            <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab" href="#{{ $id }}">{{ $label }}</a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">

        {{-- General --}}
        <div class="tab-pane fade show active" id="general">
            <div class="mb-3">
                <label for="organizacion_id" class="form-label">Organización</label>
                <select name="organizacion_id" class="form-control" required>
                    @foreach($organizaciones as $org)
                        <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>¿Tiene más de 6 meses de antigüedad?</label><br>
                <label><input type="radio" name="mayor_seis_meses" value="1" required> Sí</label>
                <label class="ms-3"><input type="radio" name="mayor_seis_meses" value="0"> No</label>
            </div>
        </div>

        {{-- Jurídica --}}
        <div class="tab-pane fade" id="juridica">
            <div class="mb-3">
                <label>¿Tiene personería jurídica con directiva actualizada?</label><br>
                <input type="radio" name="personeria_juridica" value="1" required> Sí
                <input type="radio" name="personeria_juridica" value="0"> No
            </div>
            <div class="mb-3">
                <label>¿Tiene personería o junta directiva en trámite?</label><br>
                <input type="radio" name="personeria_tramite" value="1" required> Sí
                <input type="radio" name="personeria_tramite" value="0"> No
            </div>
            <div class="mb-3">
                <label>¿Tiene RTN?</label><br>
                <input type="radio" name="rtn" value="1" required> Sí
                <input type="radio" name="rtn" value="0"> No
            </div>
        </div>

        {{-- Consejo --}}
        <div class="tab-pane fade" id="consejo">
            <div class="mb-3">
                <label>Frecuencia de reunión del consejo:</label>
                <select name="frecuencia_reunion" class="form-control" required>
                    <option value="quincenal">Quincenal</option>
                    <option value="mensual">Mensual</option>
                    <option value="mas_mes">Más de 1 mes</option>
                </select>
            </div>
            <div class="mb-3">
                <label>¿Tiene actas de sesión?</label><br>
                <input type="radio" name="actas_sesion" value="1" required> Sí
                <input type="radio" name="actas_sesion" value="0"> No
            </div>
            <div class="mb-3">
                <label>¿Tiene plan de trabajo (plan financiero o de negocios)?</label><br>
                <input type="radio" name="plan_trabajo" value="1" required> Sí
                <input type="radio" name="plan_trabajo" value="0"> No
            </div>
            <div class="mb-3">
                <label>¿Tiene estatuto y reglamentos?</label><br>
                <input type="radio" name="estatutos" value="1" required> Sí
                <input type="radio" name="estatutos" value="0"> No
            </div>
            <div class="mb-3">
                <label>¿Los miembros aplican estatutos y reglamentos?</label><br>
                <input type="radio" name="aplican_estatutos" value="1" required> Sí
                <input type="radio" name="aplican_estatutos" value="0"> No
            </div>
        </div>

        {{-- Tesorero --}}
        <div class="tab-pane fade" id="tesorero">
            <div class="mb-3">
                <label>¿Tiene libros contables actualizados?</label><br>
                <input type="radio" name="libros_contables" value="1" required> Sí
                <input type="radio" name="libros_contables" value="0"> No
            </div>
            <div class="mb-3">
                <label>¿Elabora informes financieros?</label><br>
                <input type="radio" name="informes_financieros" value="1" required> Sí
                <input type="radio" name="informes_financieros" value="0"> No
            </div>
        </div>

        {{-- Crédito --}}
        <div class="tab-pane fade" id="credito">
            <div class="mb-3">
                <label>¿El comité de créditos se reúne y hay actas de sesión?</label><br>
                <input type="radio" name="actas_credito" value="1" required> Sí
                <input type="radio" name="actas_credito" value="0"> No
            </div>
            <div class="mb-3">
                <label>¿Gestionan en base a reglamento de préstamo?</label><br>
                <input type="radio" name="gestion_reglamento" value="1" required> Sí
                <input type="radio" name="gestion_reglamento" value="0"> No
            </div>
        </div>

        {{-- Fiscalizadora --}}
        <div class="tab-pane fade" id="fiscalizadora">
            <div class="mb-3">
                <label>¿La junta fiscalizadora se reúne y hay actas?</label><br>
                <input type="radio" name="actas_fiscalizadora" value="1" required> Sí
                <input type="radio" name="actas_fiscalizadora" value="0"> No
            </div>
            <div class="mb-3">
                <label>¿La junta presenta informes a la asamblea?</label><br>
                <input type="radio" name="informes_fiscalizadora" value="1" required> Sí
                <input type="radio" name="informes_fiscalizadora" value="0"> No
            </div>
        </div>

        {{-- Registros --}}
        <div class="tab-pane fade" id="registros">
            @foreach ([
                'libro_prestamos' => 'Libro de préstamos',
                'libro_ahorros' => 'Libro de ahorros',
                'libro_caja' => 'Libro de caja',
                'libro_aportaciones' => 'Libro de aportaciones',
                'libro_ahorros_prestamos' => 'Libro de ahorros y préstamos',
                'libros_actas' => 'Libros de actas'
            ] as $name => $label)
            <div class="mb-3">
                <label>¿Tiene {{ strtolower($label) }}?</label><br>
                <input type="radio" name="{{ $name }}" value="1" required> Sí
                <input type="radio" name="{{ $name }}" value="0"> No
            </div>
            @endforeach
        </div>

        {{-- Préstamos --}}
        <div class="tab-pane fade" id="prestamos">
            @foreach ([
                'formulario_solicitud' => 'Formulario de solicitud',
                'exigencia_garantias' => 'Exigencia de garantías',
                'dictamen_credito' => 'Dictamen del comité de crédito',
                'pagare' => 'Pagaré',
                'letra_cambio' => 'Letra de cambio'
            ] as $name => $label)
            <div class="mb-3">
                <label>¿Tiene {{ strtolower($label) }}?</label><br>
                <input type="radio" name="{{ $name }}" value="1" required> Sí
                <input type="radio" name="{{ $name }}" value="0"> No
            </div>
            @endforeach
        </div>

        {{-- Financiera --}}
        <div class="tab-pane fade" id="financiera">
            <div class="mb-3">
                <label>Eficiencia financiera</label>
                <select name="eficiencia_financiera" class="form-control" required>
                    <option value="mayor">Mayor a la inflación</option>
                    <option value="menor">Menor a la inflación</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Apalancamiento financiero</label>
                <select name="apalancamiento" class="form-control" required>
                    <option value="mayor_60">Mayor al 60%</option>
                    <option value="30_60">Entre 30% y 60%</option>
                    <option value="menor_30">Menor al 30%</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Sostenibilidad financiera</label>
                <select name="sostenibilidad" class="form-control" required>
                    <option value="mayor_1">Mayor a 1</option>
                    <option value="igual_1">Igual a 1</option>
                    <option value="menor_1">Menor a 1</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Calidad de cartera</label>
                <select name="calidad_cartera" class="form-control" required>
                    <option value="0_3">Entre 0% y 3%</option>
                    <option value="3_5">Entre 3% y 5%</option>
                    <option value="5_8">Entre 5% y 8%</option>
                    <option value="8_10">Entre 8% y 10%</option>
                    <option value="mayor_10">Mayor al 10%</option>
                </select>
            </div>
        </div>

    </div>

    {{-- Botones --}}
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Guardar Evaluación</button>
        <a href="{{ route('evaluacion.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection
