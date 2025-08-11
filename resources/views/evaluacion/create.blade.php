@extends('adminlte::page')

@section('title', 'Nueva Evaluación')

@section('content_header')
    <h1>Nueva Evaluación</h1>
@endsection

@section('content')
<form id="evaluacionForm" action="{{ route('evaluacion.store') }}" method="POST">
    @csrf

    {{-- Aviso inicial --}}
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Importante:</strong> La primera evaluación que realice será <b>única</b> 
    y <b>no podrá ser modificada</b> posteriormente.
    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

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
                <select id="organizacion_id" name="organizacion_id" class="form-control" required>
                    <option value="">-- Seleccione una organización --</option>
                    @foreach($organizaciones as $org)
                        <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Tiene más de 6 meses de antigüedad?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="mayor_seis_meses_si" name="mayor_seis_meses" value="1" required>
                    <label class="form-check-label" for="mayor_seis_meses_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="mayor_seis_meses_no" name="mayor_seis_meses" value="0" required>
                    <label class="form-check-label" for="mayor_seis_meses_no">No</label>
                </div>
            </div>
        </div>

        {{-- Jurídica --}}
        <div class="tab-pane fade" id="juridica">
            <div class="mb-3">
                <label class="form-label">¿Tiene personería jurídica con directiva actualizada?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="personeria_juridica_si" name="personeria_juridica" value="1" required>
                    <label class="form-check-label" for="personeria_juridica_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="personeria_juridica_no" name="personeria_juridica" value="0" required>
                    <label class="form-check-label" for="personeria_juridica_no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Tiene personería o junta directiva en trámite?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="personeria_tramite_si" name="personeria_tramite" value="1" required>
                    <label class="form-check-label" for="personeria_tramite_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="personeria_tramite_no" name="personeria_tramite" value="0" required>
                    <label class="form-check-label" for="personeria_tramite_no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Tiene RTN?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="rtn_si" name="rtn" value="1" required>
                    <label class="form-check-label" for="rtn_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="rtn_no" name="rtn" value="0" required>
                    <label class="form-check-label" for="rtn_no">No</label>
                </div>
            </div>
        </div>

        {{-- Consejo --}}
        <div class="tab-pane fade" id="consejo">
            <div class="mb-3">
                <label for="frecuencia_reunion" class="form-label">Frecuencia de reunión del consejo:</label>
                <select id="frecuencia_reunion" name="frecuencia_reunion" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <option value="quincenal">Quincenal</option>
                    <option value="mensual">Mensual</option>
                    <option value="mas_mes">Más de 1 mes</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Tiene actas de sesión?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="actas_sesion_si" name="actas_sesion" value="1" required>
                    <label class="form-check-label" for="actas_sesion_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="actas_sesion_no" name="actas_sesion" value="0" required>
                    <label class="form-check-label" for="actas_sesion_no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Tiene plan de trabajo (plan financiero o de negocios)?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="plan_trabajo_si" name="plan_trabajo" value="1" required>
                    <label class="form-check-label" for="plan_trabajo_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="plan_trabajo_no" name="plan_trabajo" value="0" required>
                    <label class="form-check-label" for="plan_trabajo_no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Tiene estatuto y reglamentos?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="estatutos_si" name="estatutos" value="1" required>
                    <label class="form-check-label" for="estatutos_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="estatutos_no" name="estatutos" value="0" required>
                    <label class="form-check-label" for="estatutos_no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Los miembros aplican estatutos y reglamentos?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="aplican_estatutos_si" name="aplican_estatutos" value="1" required>
                    <label class="form-check-label" for="aplican_estatutos_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="aplican_estatutos_no" name="aplican_estatutos" value="0" required>
                    <label class="form-check-label" for="aplican_estatutos_no">No</label>
                </div>
            </div>
        </div>

        {{-- Tesorero --}}
        <div class="tab-pane fade" id="tesorero">
            <div class="mb-3">
                <label class="form-label">¿Tiene libros contables actualizados?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="libros_contables_si" name="libros_contables" value="1" required>
                    <label class="form-check-label" for="libros_contables_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="libros_contables_no" name="libros_contables" value="0" required>
                    <label class="form-check-label" for="libros_contables_no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Elabora informes financieros?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="informes_financieros_si" name="informes_financieros" value="1" required>
                    <label class="form-check-label" for="informes_financieros_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="informes_financieros_no" name="informes_financieros" value="0" required>
                    <label class="form-check-label" for="informes_financieros_no">No</label>
                </div>
            </div>
        </div>

        {{-- Crédito --}}
        <div class="tab-pane fade" id="credito">
            <div class="mb-3">
                <label class="form-label">¿El comité de créditos se reúne y hay actas de sesión?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="actas_credito_si" name="actas_credito" value="1" required>
                    <label class="form-check-label" for="actas_credito_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="actas_credito_no" name="actas_credito" value="0" required>
                    <label class="form-check-label" for="actas_credito_no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">¿Gestionan en base a reglamento de préstamo?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="gestion_reglamento_si" name="gestion_reglamento" value="1" required>
                    <label class="form-check-label" for="gestion_reglamento_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="gestion_reglamento_no" name="gestion_reglamento" value="0" required>
                    <label class="form-check-label" for="gestion_reglamento_no">No</label>
                </div>
            </div>
        </div>

        {{-- Fiscalizadora --}}
        <div class="tab-pane fade" id="fiscalizadora">
            <div class="mb-3">
                <label class="form-label">¿La junta fiscalizadora se reúne y hay actas?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="actas_fiscalizadora_si" name="actas_fiscalizadora" value="1" required>
                    <label class="form-check-label" for="actas_fiscalizadora_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="actas_fiscalizadora_no" name="actas_fiscalizadora" value="0" required>
                    <label class="form-check-label" for="actas_fiscalizadora_no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">¿La junta presenta informes a la asamblea?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="informes_fiscalizadora_si" name="informes_fiscalizadora" value="1" required>
                    <label class="form-check-label" for="informes_fiscalizadora_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="informes_fiscalizadora_no" name="informes_fiscalizadora" value="0" required>
                    <label class="form-check-label" for="informes_fiscalizadora_no">No</label>
                </div>
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
                <label class="form-label">¿Tiene {{ strtolower($label) }}?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="{{ $name }}_si" name="{{ $name }}" value="1" required>
                    <label class="form-check-label" for="{{ $name }}_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="{{ $name }}_no" name="{{ $name }}" value="0" required>
                    <label class="form-check-label" for="{{ $name }}_no">No</label>
                </div>
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
                <label class="form-label">¿Tiene {{ strtolower($label) }}?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="{{ $name }}_si" name="{{ $name }}" value="1" required>
                    <label class="form-check-label" for="{{ $name }}_si">Sí</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="{{ $name }}_no" name="{{ $name }}" value="0" required>
                    <label class="form-check-label" for="{{ $name }}_no">No</label>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Financiera --}}
        <div class="tab-pane fade" id="financiera">
            <div class="mb-3">
                <label for="eficiencia_financiera" class="form-label">Eficiencia financiera</label>
                <select id="eficiencia_financiera" name="eficiencia_financiera" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <option value="mayor">Mayor a la inflación</option>
                    <option value="menor">Menor a la inflación</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="apalancamiento" class="form-label">Apalancamiento financiero</label>
                <select id="apalancamiento" name="apalancamiento" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <option value="mayor_60">Mayor al 60%</option>
                    <option value="30_60">Entre 30% y 60%</option>
                    <option value="menor_30">Menor al 30%</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="sostenibilidad" class="form-label">Sostenibilidad financiera</label>
                <select id="sostenibilidad" name="sostenibilidad" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <option value="mayor_1">Mayor a 1</option>
                    <option value="igual_1">Igual a 1</option>
                    <option value="menor_1">Menor a 1</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="calidad_cartera" class="form-label">Calidad de cartera</label>
                <select id="calidad_cartera" name="calidad_cartera" class="form-control" required>
                    <option value="">-- Seleccione --</option>
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


@section('js')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('evaluacionForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Evita que se envíe directamente

            Swal.fire({
                title: '¿Está seguro?',
                text: "⚠️ La primera evaluación será ÚNICA y NO se podrá modificar después.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Envía el formulario solo si confirma
                }
            });
        });
    </script>
    <script>
    const radiosAntiguedad = document.querySelectorAll('input[name="mayor_seis_meses"]');
    const restoFormulario = document.getElementById('restoFormulario');
    const form = document.getElementById('evaluacionForm');

    // Mostrar/ocultar según respuesta
    radiosAntiguedad.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === "1") {
                restoFormulario.style.display = "block";
                restoFormulario.classList.add("animate__animated", "animate__fadeIn");
            } else {
                restoFormulario.style.display = "none";
            }
        });
    });
@endsection

@endsection
