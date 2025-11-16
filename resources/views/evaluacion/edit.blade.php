@extends('adminlte::page')

@section('title', 'Editar Evaluación')

@section('content_header')
    <h1 class="fw-bold text-primary" aria-label="Edición de Evaluación">Editar Evaluación</h1>
@endsection

@section('content')

<form id="evaluacionEditForm"
      action="{{ route('evaluacion.update', $evaluacion->Id_Evaluacion) }}"
      method="POST"
      aria-labelledby="form-evaluacion-titulo">

    @csrf
    @method('PUT')

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="tabForm" role="tablist" aria-label="Secciones del formulario de evaluación">
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

        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $loop->first ? 'active' : '' }}"
               data-toggle="tab"
               href="#{{ $id }}"
               role="tab"
               aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                {{ $label }}
            </a>
        </li>

        @endforeach
    </ul>

    <div class="tab-content">

        {{-- GENERAL --}}
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <div class="mb-3">
                <label class="form-label fw-semibold">Organización</label>
                <input type="text" class="form-control" value="{{ $evaluacion->organizacion->Nombre_Organizacion }}" readonly>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">¿Tiene más de 6 meses de antigüedad?</label><br>
                <label class="me-3">
                    <input type="radio" name="mayor_seis_meses" value="1" {{ $evaluacion->mayor_seis_meses ? 'checked' : '' }}> Sí
                </label>
                <label>
                    <input type="radio" name="mayor_seis_meses" value="0" {{ !$evaluacion->mayor_seis_meses ? 'checked' : '' }}> No
                </label>
            </div>
        </div>

        {{-- ---------------------------
              SECCIONES GENÉRICAS
           --------------------------- --}}
        @foreach([
            'juridica' => [
                'personeria_juridica' => '¿Tiene personería jurídica con directiva actualizada?',
                'personeria_tramite' => '¿Tiene personería o junta directiva en trámite?',
                'rtn' => '¿Tiene RTN?',
            ],
            'consejo' => [
                'actas_sesion' => '¿Tiene actas de sesión?',
                'plan_trabajo' => '¿Tiene plan de trabajo?',
                'estatutos' => '¿Tiene estatuto y reglamentos?',
                'aplican_estatutos' => '¿Aplican estatutos y reglamentos?',
            ],
            'tesorero' => [
                'libros_contables' => '¿Tiene libros contables actualizados?',
                'informes_financieros' => '¿Elabora informes financieros?',
            ],
            'credito' => [
                'actas_credito' => '¿El comité de créditos se reúne y hay actas?',
                'gestion_reglamento' => '¿Gestionan con base a reglamento?',
            ],
            'fiscalizadora' => [
                'actas_fiscalizadora' => '¿La junta fiscalizadora se reúne?',
                'informes_fiscalizadora' => '¿Presentan informes a la asamblea?',
            ],
            'registros' => [
                'libro_prestamos' => 'Libro de préstamos',
                'libro_ahorros' => 'Libro de ahorros',
                'libro_caja' => 'Libro de caja',
                'libro_aportaciones' => 'Libro de aportaciones',
                'libro_ahorros_prestamos' => 'Libro de ahorros y préstamos',
                'libros_actas' => 'Libros de actas',
            ],
            'prestamos' => [
                'formulario_solicitud' => 'Formulario de solicitud',
                'exigencia_garantias' => 'Exigencia de garantías',
                'dictamen_credito' => 'Dictamen del comité de crédito',
                'pagare' => 'Pagaré',
                'letra_cambio' => 'Letra de cambio',
            ]
        ] as $tab => $items)

        <div class="tab-pane fade" id="{{ $tab }}" role="tabpanel">
            @foreach($items as $name => $label)
            <div class="mb-3">
                <label class="fw-semibold">{{ $label }}</label><br>
                <label class="me-3">
                    <input type="radio" name="{{ $name }}" value="1" {{ $evaluacion->$name ? 'checked' : '' }}> Sí
                </label>
                <label>
                    <input type="radio" name="{{ $name }}" value="0" {{ !$evaluacion->$name ? 'checked' : '' }}> No
                </label>
            </div>
            @endforeach
        </div>

        @endforeach

        {{-- FINANCIERA --}}
        <div class="tab-pane fade" id="financiera" role="tabpanel">

            <div class="mb-3">
                <label class="fw-semibold">Eficiencia financiera</label>
                <select name="eficiencia_financiera" class="form-control" required>
                    <option value="mayor" {{ $evaluacion->eficiencia_financiera == 'mayor' ? 'selected' : '' }}>Mayor a la inflación</option>
                    <option value="menor" {{ $evaluacion->eficiencia_financiera == 'menor' ? 'selected' : '' }}>Menor a la inflación</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Apalancamiento financiero</label>
                <select name="apalancamiento" class="form-control" required>
                    <option value="mayor_60" {{ $evaluacion->apalancamiento == 'mayor_60' ? 'selected' : '' }}>Mayor al 60%</option>
                    <option value="30_60" {{ $evaluacion->apalancamiento == '30_60' ? 'selected' : '' }}>Entre 30% y 60%</option>
                    <option value="menor_30" {{ $evaluacion->apalancamiento == 'menor_30' ? 'selected' : '' }}>Menor al 30%</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Sostenibilidad financiera</label>
                <select name="sostenibilidad" class="form-control" required>
                    <option value="mayor_1" {{ $evaluacion->sostenibilidad == 'mayor_1' ? 'selected' : '' }}>Mayor a 1</option>
                    <option value="igual_1" {{ $evaluacion->sostenibilidad == 'igual_1' ? 'selected' : '' }}>Igual a 1</option>
                    <option value="menor_1" {{ $evaluacion->sostenibilidad == 'menor_1' ? 'selected' : '' }}>Menor a 1</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Calidad de cartera</label>
                <select name="calidad_cartera" class="form-control" required>
                    <option value="0_3" {{ $evaluacion->calidad_cartera == '0_3' ? 'selected' : '' }}>Entre 0% y 3%</option>
                    <option value="3_5" {{ $evaluacion->calidad_cartera == '3_5' ? 'selected' : '' }}>Entre 3% y 5%</option>
                    <option value="5_8" {{ $evaluacion->calidad_cartera == '5_8' ? 'selected' : '' }}>Entre 5% y 8%</option>
                    <option value="8_10" {{ $evaluacion->calidad_cartera == '8_10' ? 'selected' : '' }}>Entre 8% y 10%</option>
                    <option value="mayor_10" {{ $evaluacion->calidad_cartera == 'mayor_10' ? 'selected' : '' }}>Mayor al 10%</option>
                </select>
            </div>

        </div>

    </div>

    {{-- BOTONES FUNDER --}}
    <div class="mt-4 d-flex gap-3">
        <button type="submit"
                class="btn fw-semibold"
                style="background-color:#0D47A1;color:white;">
            Actualizar Evaluación
        </button>

        <a href="{{ route('evaluacion.index') }}"
           class="btn"
           style="background-color:#424242;color:white;">
            Cancelar
        </a>
    </div>

</form>
@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('evaluacionEditForm').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: '¿Actualizar evaluación?',
        text: 'Esta acción modificará la información registrada.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0D47A1',
        cancelButtonColor: '#B71C1C',
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>
@endsection
