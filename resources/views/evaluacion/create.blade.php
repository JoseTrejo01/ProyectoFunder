@extends('adminlte::page')

@section('title', 'Nueva Evaluación')

@section('content_header')
    <h1 class="fw-bold text-primary" aria-label="Crear nueva evaluación">
        Nueva Evaluación
    </h1>
@endsection

@section('content')

<form id="evaluacionForm"
      action="{{ route('evaluacion.store') }}"
      method="POST"
      role="form"
      aria-labelledby="titulo-evaluacion">

    @csrf

    {{-- AVISO INICIAL --}}
    <div class="alert alert-warning alert-dismissible fade show mt-2"
         role="alert"
         aria-live="polite">

        <strong>Importante:</strong> La primera evaluación será <b>única</b>
        y <b>no podrá ser modificada</b> posteriormente.

        <button type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Cerrar aviso">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    {{-- TABS --}}
    <ul class="nav nav-tabs mb-3"
        id="tabForm"
        role="tablist"
        aria-label="Secciones del formulario de evaluación">

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
                <label for="organizacion_id" class="form-label fw-semibold">
                    Organización
                </label>

                <select id="organizacion_id"
                        name="organizacion_id"
                        class="form-control"
                        aria-required="true"
                        required>

                    <option value="">Seleccione una organización</option>

                    @foreach($organizaciones as $org)
                        <option value="{{ $org->Id_Organizacion }}">
                            {{ $org->Nombre_Organizacion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">
                    ¿Tiene más de 6 meses de antigüedad?
                </label><br>

                <label class="me-3">
                    <input type="radio" name="mayor_seis_meses" value="1" required> Sí
                </label>

                <label>
                    <input type="radio" name="mayor_seis_meses" value="0" required> No
                </label>
            </div>

        </div>

        {{-- =====================================================================
            BLOQUES DE PREGUNTAS REPETIDAS – CON ESTILO FUNDER Y ARIA
           ===================================================================== --}}
        @foreach([
            'juridica' => [
                'personeria_juridica' => '¿Tiene personería jurídica con directiva actualizada?',
                'personeria_tramite'  => '¿Tiene personería o junta directiva en trámite?',
                'rtn'                 => '¿Tiene RTN?',
            ],
            'consejo' => [
                'actas_sesion'     => '¿Tiene actas de sesión?',
                'plan_trabajo'     => '¿Tiene plan de trabajo?',
                'estatutos'        => '¿Tiene estatuto y reglamentos?',
                'aplican_estatutos'=> '¿Aplican los miembros los estatutos?',
            ],
            'tesorero' => [
                'libros_contables'     => '¿Tiene libros contables actualizados?',
                'informes_financieros' => '¿Elabora informes financieros?',
            ],
            'credito' => [
                'actas_credito'       => '¿El comité se reúne y hay actas?',
                'gestion_reglamento'  => '¿Gestionan con reglamento?',
            ],
            'fiscalizadora' => [
                'actas_fiscalizadora'     => '¿La junta se reúne y tiene actas?',
                'informes_fiscalizadora'  => '¿Presentan informes a la asamblea?',
            ],
            'registros' => [
                'libro_prestamos'         => 'Libro de préstamos',
                'libro_ahorros'           => 'Libro de ahorros',
                'libro_caja'              => 'Libro de caja',
                'libro_aportaciones'      => 'Libro de aportaciones',
                'libro_ahorros_prestamos' => 'Libro de ahorros y préstamos',
                'libros_actas'            => 'Libros de actas',
            ],
            'prestamos' => [
                'formulario_solicitud' => 'Formulario de solicitud',
                'exigencia_garantias'  => 'Exigencia de garantías',
                'dictamen_credito'     => 'Dictamen del comité de crédito',
                'pagare'               => 'Pagaré',
                'letra_cambio'         => 'Letra de cambio',
            ]
        ] as $tab => $items)

        <div class="tab-pane fade" id="{{ $tab }}" role="tabpanel">
            @foreach($items as $name => $label)
            <div class="mb-3">
                <label class="fw-semibold">{{ $label }}</label><br>

                <label class="me-3">
                    <input type="radio"
                           name="{{ $name }}"
                           value="1"
                           required> Sí
                </label>

                <label>
                    <input type="radio"
                           name="{{ $name }}"
                           value="0"
                           required> No
                </label>
            </div>
            @endforeach
        </div>

        @endforeach

        {{-- FINANCIERA --}}
        <div class="tab-pane fade" id="financiera" role="tabpanel">

            @foreach([
                'eficiencia_financiera' => [
                    'label' => 'Eficiencia financiera',
                    'options' => [
                        'mayor' => 'Mayor a la inflación',
                        'menor' => 'Menor a la inflación'
                    ]
                ],
                'apalancamiento' => [
                    'label' => 'Apalancamiento',
                    'options' => [
                        'mayor_60' => 'Mayor al 60%',
                        '30_60'    => 'Entre 30% y 60%',
                        'menor_30' => 'Menor al 30%'
                    ]
                ],
                'sostenibilidad' => [
                    'label' => 'Sostenibilidad',
                    'options' => [
                        'mayor_1' => 'Mayor a 1',
                        'igual_1' => 'Igual a 1',
                        'menor_1' => 'Menor a 1'
                    ]
                ],
                'calidad_cartera' => [
                    'label' => 'Calidad de cartera',
                    'options' => [
                        '0_3'      => 'Entre 0% y 3%',
                        '3_5'      => 'Entre 3% y 5%',
                        '5_8'      => 'Entre 5% y 8%',
                        '8_10'     => 'Entre 8% y 10%',
                        'mayor_10' => 'Mayor al 10%'
                    ]
                ]
            ] as $name => $group)

            <div class="mb-3">
                <label>{{ $group['label'] }}</label>
                <select name="{{ $name }}" class="form-control" required>
                    <option value="">Seleccione…</option>
                    @foreach($group['options'] as $value => $text)
                        <option value="{{ $value }}">{{ $text }}</option>
                    @endforeach
                </select>
            </div>

            @endforeach

        </div>

    </div>

    {{-- BOTONES ESTILO FUNDER --}}
    <div class="mt-4 d-flex gap-3">
        
        <button type="submit"
                class="btn fw-semibold"
                style="background-color:#0D47A1; color:white;">
            Guardar Evaluación
        </button>

        <a href="{{ route('evaluacion.index') }}"
           class="btn fw-semibold"
           style="background-color:#424242; color:white;">
            Cancelar
        </a>

    </div>

</form>

@endsection

@section('js')

{{-- ALERTA FUNDER --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('evaluacionForm').addEventListener('submit', function (e) {
    e.preventDefault();

    Swal.fire({
        title: '¿Guardar evaluación?',
        html: `<b>Esta será la primera evaluación registrada.</b><br> 
               <span style="color:#B71C1C;">NO podrá ser modificada después.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0D47A1',
        cancelButtonColor: '#B71C1C'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});
</script>

@endsection
