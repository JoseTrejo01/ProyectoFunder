{{-- resources/views/evaluacion/edit.blade.php --}}
@extends('adminlte::page')

@section('title', 'Editar Evaluación')

@section('content_header')
    <h1>Editar Evaluación</h1>
@endsection

@section('content')
<form action="{{ route('evaluacion.update', $evaluacion->Id_Evaluacion) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Organización (readonly) --}}
    <div class="mb-3">
        <label class="form-label">Organización</label>
        <input type="text" class="form-control" value="{{ $evaluacion->organizacion->Nombre_Organizacion }}" readonly>
    </div>

    {{-- Antigüedad --}}
    <div class="mb-3">
        <label>¿Tiene más de 6 meses de antigüedad?</label><br>
        <label><input type="radio" name="mayor_seis_meses" value="1" {{ $evaluacion->mayor_seis_meses ? 'checked' : '' }}> Sí</label>
        <label class="ms-3"><input type="radio" name="mayor_seis_meses" value="0" {{ !$evaluacion->mayor_seis_meses ? 'checked' : '' }}> No</label>
    </div>

    {{-- Naturaleza Jurídica --}}
    <h4 class="mt-4">Naturaleza Jurídica</h4>
    @foreach([
        'personeria_juridica' => '¿Tiene personería jurídica con directiva actualizada?',
        'personeria_tramite' => '¿Tiene personería o junta directiva en trámite?',
        'rtn' => '¿Tiene RTN?'
    ] as $name => $label)
    <div class="mb-3">
        <label>{{ $label }}</label><br>
        <input type="radio" name="{{ $name }}" value="1" {{ $evaluacion->$name ? 'checked' : '' }}> Sí
        <input type="radio" name="{{ $name }}" value="0" {{ !$evaluacion->$name ? 'checked' : '' }}> No
    </div>
    @endforeach

    {{-- Consejo de Administración --}}
    <h4 class="mt-4">Consejo de Administración</h4>
    <div class="mb-3">
        <label>Frecuencia de reunión del consejo:</label><br>
        <select name="frecuencia_reunion" class="form-control" required>
            @foreach(['quincenal' => 'Quincenal', 'mensual' => 'Mensual', 'mas_mes' => 'Más de 1 mes'] as $value => $label)
                <option value="{{ $value }}" {{ $evaluacion->frecuencia_reunion == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @foreach([
        'actas_sesion' => '¿Tiene actas de sesión?',
        'plan_trabajo' => '¿Tiene plan de trabajo (plan financiero o de negocios)?',
        'estatutos' => '¿Tiene estatuto y reglamentos?',
        'aplican_estatutos' => '¿Los miembros aplican estatutos y reglamentos?'
    ] as $name => $label)
    <div class="mb-3">
        <label>{{ $label }}</label><br>
        <input type="radio" name="{{ $name }}" value="1" {{ $evaluacion->$name ? 'checked' : '' }}> Sí
        <input type="radio" name="{{ $name }}" value="0" {{ !$evaluacion->$name ? 'checked' : '' }}> No
    </div>
    @endforeach

    {{-- Tesorero --}}
    <h4 class="mt-4">Tesorero</h4>
    @foreach([
        'libros_contables' => '¿Tiene libros contables actualizados?',
        'informes_financieros' => '¿Elabora informes financieros?'
    ] as $name => $label)
    <div class="mb-3">
        <label>{{ $label }}</label><br>
        <input type="radio" name="{{ $name }}" value="1" {{ $evaluacion->$name ? 'checked' : '' }}> Sí
        <input type="radio" name="{{ $name }}" value="0" {{ !$evaluacion->$name ? 'checked' : '' }}> No
    </div>
    @endforeach

    {{-- Comité de Crédito y Cobro --}}
    <h4 class="mt-4">Comité de Crédito y Cobro</h4>
    @foreach([
        'actas_credito' => '¿El comité de créditos se reúne y hay actas de sesión?',
        'gestion_reglamento' => '¿Gestionan en base a reglamento de préstamo?'
    ] as $name => $label)
    <div class="mb-3">
        <label>{{ $label }}</label><br>
        <input type="radio" name="{{ $name }}" value="1" {{ $evaluacion->$name ? 'checked' : '' }}> Sí
        <input type="radio" name="{{ $name }}" value="0" {{ !$evaluacion->$name ? 'checked' : '' }}> No
    </div>
    @endforeach

    {{-- Junta Fiscalizadora --}}
    <h4 class="mt-4">Junta Fiscalizadora</h4>
    @foreach([
        'actas_fiscalizadora' => '¿La junta fiscalizadora se reúne y hay actas?',
        'informes_fiscalizadora' => '¿La junta presenta informes a la asamblea?'
    ] as $name => $label)
    <div class="mb-3">
        <label>{{ $label }}</label><br>
        <input type="radio" name="{{ $name }}" value="1" {{ $evaluacion->$name ? 'checked' : '' }}> Sí
        <input type="radio" name="{{ $name }}" value="0" {{ !$evaluacion->$name ? 'checked' : '' }}> No
    </div>
    @endforeach

    {{-- Registros contables --}}
    <h4 class="mt-4">Registros Contables y Organizativos</h4>
    @foreach([
        'libro_prestamos' => 'Libro de préstamos',
        'libro_ahorros' => 'Libro de ahorros',
        'libro_caja' => 'Libro de caja',
        'libro_aportaciones' => 'Libro de aportaciones',
        'libro_ahorros_prestamos' => 'Libro de ahorros y préstamos',
        'libros_actas' => 'Libros de actas'
    ] as $name => $label)
    <div class="mb-3">
        <label>¿Tiene {{ strtolower($label) }}?</label><br>
        <input type="radio" name="{{ $name }}" value="1" {{ $evaluacion->$name ? 'checked' : '' }}> Sí
        <input type="radio" name="{{ $name }}" value="0" {{ !$evaluacion->$name ? 'checked' : '' }}> No
    </div>
    @endforeach

    {{-- Administración de Préstamos --}}
    <h4 class="mt-4">Administración de Préstamos</h4>
    @foreach([
        'formulario_solicitud' => 'Formulario de solicitud',
        'exigencia_garantias' => 'Exigencia de garantías',
        'dictamen_credito' => 'Dictamen del comité de crédito',
        'pagare' => 'Pagaré',
        'letra_cambio' => 'Letra de cambio'
    ] as $name => $label)
    <div class="mb-3">
        <label>¿Tiene {{ strtolower($label) }}?</label><br>
        <input type="radio" name="{{ $name }}" value="1" {{ $evaluacion->$name ? 'checked' : '' }}> Sí
        <input type="radio" name="{{ $name }}" value="0" {{ !$evaluacion->$name ? 'checked' : '' }}> No
    </div>
    @endforeach

   {{-- Evaluación Financiera --}}
<h4 class="mt-4">Evaluación Financiera</h4>

<div class="mb-3">
    <label>Eficiencia financiera</label>
    <select name="eficiencia_financiera" class="form-control" required>
        <option value="mayor" {{ $evaluacion->eficiencia_financiera == 'mayor' ? 'selected' : '' }}>Mayor a la inflación</option>
        <option value="menor" {{ $evaluacion->eficiencia_financiera == 'menor' ? 'selected' : '' }}>Menor a la inflación</option>
    </select>
</div>

<div class="mb-3">
    <label>Apalancamiento financiero</label>
    <select name="apalancamiento" class="form-control" required>
        <option value="mayor_60" {{ $evaluacion->apalancamiento == 'mayor_60' ? 'selected' : '' }}>Mayor al 60%</option>
        <option value="30_60" {{ $evaluacion->apalancamiento == '30_60' ? 'selected' : '' }}>Entre 30% y 60%</option>
        <option value="menor_30" {{ $evaluacion->apalancamiento == 'menor_30' ? 'selected' : '' }}>Menor al 30%</option>
    </select>
</div>

<div class="mb-3">
    <label>Sostenibilidad financiera</label>
    <select name="sostenibilidad" class="form-control" required>
        <option value="mayor_1" {{ $evaluacion->sostenibilidad == 'mayor_1' ? 'selected' : '' }}>Mayor a 1</option>
        <option value="igual_1" {{ $evaluacion->sostenibilidad == 'igual_1' ? 'selected' : '' }}>Igual a 1</option>
        <option value="menor_1" {{ $evaluacion->sostenibilidad == 'menor_1' ? 'selected' : '' }}>Menor a 1</option>
    </select>
</div>

<div class="mb-3">
    <label>Calidad de cartera</label>
    <select name="calidad_cartera" class="form-control" required>
        <option value="0_3" {{ $evaluacion->calidad_cartera == '0_3' ? 'selected' : '' }}>Entre 0% y 3%</option>
        <option value="3_5" {{ $evaluacion->calidad_cartera == '3_5' ? 'selected' : '' }}>Entre 3% y 5%</option>
        <option value="5_8" {{ $evaluacion->calidad_cartera == '5_8' ? 'selected' : '' }}>Entre 5% y 8%</option>
        <option value="8_10" {{ $evaluacion->calidad_cartera == '8_10' ? 'selected' : '' }}>Entre 8% y 10%</option>
        <option value="mayor_10" {{ $evaluacion->calidad_cartera == 'mayor_10' ? 'selected' : '' }}>Mayor al 10%</option>
    </select>
</div>

    {{-- Mostrar Evaluación Actualizada solo si updated_at != created_at --}}
    @if ($evaluacion->updated_at != $evaluacion->created_at)
        <div class="alert alert-info mt-4">
            <strong>Evaluación Actualizada:</strong> Esta evaluación ha sido modificada desde su creación el {{ $evaluacion->created_at->format('d/m/Y H:i') }}.<br>
            Última actualización: {{ $evaluacion->updated_at->format('d/m/Y H:i') }}
        </div>
    @endif

    {{-- Botones --}}
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Actualizar Evaluación</button>
        <a href="{{ route('evaluacion.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection
