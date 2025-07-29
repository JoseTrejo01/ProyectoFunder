@extends('adminlte::page')

@section('title', 'Capacitaciones')

@section('content_header')
    <h1>Gestión de Capacitaciones</h1>
@stop

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <strong>Se encontraron errores al enviar el formulario:</strong>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


    {{-- MENSAJES DE ÉXITO Y ERROR --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#28a745',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545',
                timer: 3000,
                showConfirmButton: true
            });
        </script>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form id="form-capacitacion" method="POST" action="{{ route('capacitacion.guardar') }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Id_Organizacion" class="form-label">Organización</label>
                        <select class="form-control" id="Id_Organizacion" name="Id_Organizacion" required>
                            <option value="">Seleccione una organización</option>
                            @foreach($organizaciones as $org)
                                <option value="{{ $org->Id_Organizacion }}">{{ $org->Nombre_Organizacion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="Fecha" class="form-label">Fecha de capacitación</label>
                        <input type="date" class="form-control" name="Fecha" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="Nombre_Modulo" class="form-label">Módulo</label>
                    <select class="form-control" id="Nombre_Modulo" name="Nombre_Modulo" required>
                        <option value="">Seleccione un módulo</option>
                        @foreach($modulos as $modulo => $temas)
                            <option value="{{ $modulo }}">{{ $modulo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="Nombre_Tema" class="form-label">Tema</label>
                    <select class="form-control" id="Nombre_Tema" name="Nombre_Tema" required>
                        <option value="">Seleccione un tema</option>
                        {{-- Este será dinámico con JS --}}
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Beneficiarios capacitados</label>
                    <div id="beneficiarios-container">
                        <p class="text-muted">Seleccione una organización para cargar los beneficiarios.</p>
                    </div>
                </div>

                <div class="text-end">
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-save"></i> Guardar capacitación
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    {{-- Cargar SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const beneficiariosPorOrg = @json($beneficiariosPorOrg);
        const temasPorModulo = @json($modulos);

        document.getElementById('Id_Organizacion').addEventListener('change', function () {
            const orgId = this.value;
            const contenedor = document.getElementById('beneficiarios-container');
            contenedor.innerHTML = '';

            if (orgId && beneficiariosPorOrg[orgId]) {
                beneficiariosPorOrg[orgId].forEach(b => {
                    const checkbox = `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="beneficiarios[]" value="${b.Id_Beneficiario}" id="benef_${b.Id_Beneficiario}">
                            <label class="form-check-label" for="benef_${b.Id_Beneficiario}">${b.Nombre_Beneficiario}</label>
                        </div>
                    `;
                    contenedor.innerHTML += checkbox;
                });
            } else {
                contenedor.innerHTML = '<p class="text-muted">No se encontraron beneficiarios.</p>';
            }
        });

        document.getElementById('Nombre_Modulo').addEventListener('change', function () {
            const modulo = this.value;
            const temas = temasPorModulo[modulo] || [];
            const temaSelect = document.getElementById('Nombre_Tema');

            temaSelect.innerHTML = '<option value="">Seleccione un tema</option>';
            temas.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t;
                opt.textContent = t;
                temaSelect.appendChild(opt);
            });
        });

        // Mostrar SweetAlert si hay éxito o error
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#28a745',
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545',
                timer: 3000,
                showConfirmButton: true
            });
        @endif
    </script>
@endsection
