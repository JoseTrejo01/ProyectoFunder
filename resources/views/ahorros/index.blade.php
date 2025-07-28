@extends('adminlte::page')

@section('title', 'Ahorros - Selección de Caja Rural')

@section('content_header')
    <h1>Ahorros por Caja Rural</h1>

    <!-- 🔘 Botón para registrar nuevo ahorro -->
    <a href="{{ route('ahorros.create') }}" class="btn btn-primary mt-2">
        <i class="fas fa-plus-circle"></i> Registrar Nuevo Ahorro
    </a>
@stop

@section('content')
    <div class="card mt-3">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="card-body">
            <div class="form-group">
                <label for="caja-select"><strong>Seleccione una Caja Rural:</strong></label>
                <select id="caja-select" class="form-control" onchange="cargarDatos()" name="caja">
                    <option value="">-- Seleccione --</option>
                    @foreach ($cajas as $caja)
                        <option value="{{ $caja->Id_Organizacion }}" {{ (isset($selectedCaja) && $selectedCaja == $caja->Id_Organizacion) ? 'selected' : '' }}>
                            {{ $caja->Nombre_Organizacion }}
                        </option>
                    @endforeach
                </select>
            </div>

<<<<<<< HEAD
            <ul class="nav nav-tabs mt-4" id="ahorroTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="socios-tab" data-toggle="tab" href="#socios" role="tab"><strong>Socios</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="no-socios-tab" data-toggle="tab" href="#no-socios" role="tab"><strong>No Socios</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="totales-tab" data-toggle="tab" href="#totales" role="tab"><strong>Totales</strong></a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="ahorroTabsContent">
                <div class="tab-pane fade show active" id="socios" role="tabpanel">
                    <div id="lista-socios"></div>
                </div>
                <div class="tab-pane fade" id="no-socios" role="tabpanel">
                    <div id="lista-no-socios"></div>
                </div>
                <div class="tab-pane fade" id="totales" role="tabpanel">
                    <div id="resumen-totales"></div>
                </div>
            </div>

            <hr>

            <h4><strong>Listado de Ahorros Registrados</strong></h4>
            <div id="listado-ahorros"></div>
        </div>
    </div>
=======
    {{-- TABLA RESUMEN AGRUPADO --}}
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-primary">
            <tr>
                <th>Caja Rural</th>
                <th>Socios<br><small>(Cantidad / Total / Promedio)</small></th>
                <th>No Socios Adultos<br><small>(Cantidad / Total / Promedio)</small></th>
                <th>No Socios Jóvenes<br><small>(Cantidad / Total / Promedio)</small></th>
            </tr>
        </thead>
        <tbody>
            @forelse($agrupados as $caja => $datos)
                <tr>
                    <td>{{ $caja }}</td>
                    <td>
                        {{ $datos['socios']['cantidad'] }}<br>
                        L {{ number_format($datos['socios']['total'], 2) }}<br>
                        L {{ number_format($datos['socios']['promedio'], 2) }}
                    </td>
                    <td>
                        {{ $datos['no_socios_adultos']['cantidad'] }}<br>
                        L {{ number_format($datos['no_socios_adultos']['total'], 2) }}<br>
                        L {{ number_format($datos['no_socios_adultos']['promedio'], 2) }}
                    </td>
                    <td>
                        {{ $datos['no_socios_jovenes']['cantidad'] }}<br>
                        L {{ number_format($datos['no_socios_jovenes']['total'], 2) }}<br>
                        L {{ number_format($datos['no_socios_jovenes']['promedio'], 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay registros de ahorros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
>>>>>>> 3ee94b0a8dc5e34c4eb7247b1e2a8c0652b035d0
@stop

@section('js')
<script>
    function cargarDatos() {
        const id = document.getElementById('caja-select').value;
        if (!id) {
            // Limpiar todo si no hay caja seleccionada
            document.getElementById('lista-socios').innerHTML = '';
            document.getElementById('lista-no-socios').innerHTML = '';
            document.getElementById('resumen-totales').innerHTML = '';
            document.getElementById('listado-ahorros').innerHTML = '';
            return;
        }

        // Fetch socios y no socios con sus ahorros y resumen
        fetch(`/api/ahorros/caja/${id}/socios`)
            .then(response => response.json())
            .then(data => {
                const listaSocios = document.getElementById('lista-socios');
                const listaNoSocios = document.getElementById('lista-no-socios');
                const resumenTotales = document.getElementById('resumen-totales');

                listaSocios.innerHTML = '';
                listaNoSocios.innerHTML = '';
                resumenTotales.innerHTML = '';

                // Mostrar socios
                if (data.socios.length > 0) {
                    data.socios.forEach(socio => {
                        const monto = parseFloat(socio.Monto ?? 0).toLocaleString('es-HN', { minimumFractionDigits: 2 });
                        listaSocios.innerHTML += `<p><strong>${socio.Nombre_Beneficiario}</strong> - L. ${monto}</p>`;
                    });
                } else {
                    listaSocios.innerHTML = '<p>No hay socios registrados.</p>';
                }

                // Mostrar no socios
                if (data.no_socios.length > 0) {
                    data.no_socios.forEach(noSocio => {
                        const monto = parseFloat(noSocio.Monto ?? 0).toLocaleString('es-HN', { minimumFractionDigits: 2 });
                        listaNoSocios.innerHTML += `<p><strong>${noSocio.Nombre_Beneficiario}</strong> - L. ${monto}</p>`;
                    });
                } else {
                    listaNoSocios.innerHTML = '<p>No hay no socios registrados.</p>';
                }

                // Mostrar resumen totales
                const totalSocios = data.socios.reduce((sum, s) => sum + parseFloat(s.Monto ?? 0), 0);
                const totalNoSocios = data.no_socios.reduce((sum, s) => sum + parseFloat(s.Monto ?? 0), 0);

                const totalPersonas = data.socios.length + data.no_socios.length;
                const totalAhorros = totalSocios + totalNoSocios;
                const promedio = totalPersonas > 0 ? (totalAhorros / totalPersonas) : 0;

                resumenTotales.innerHTML = `
                    <p><strong>Total de Ahorros:</strong> L. ${totalAhorros.toLocaleString('es-HN', { minimumFractionDigits: 2 })}</p>
                    <p><strong>Promedio por Persona:</strong> L. ${promedio.toLocaleString('es-HN', { minimumFractionDigits: 2 })}</p>
                `;
            })
            .catch(error => {
                console.error('Error al cargar los datos:', error);
            });

        // Fetch listado individual de ahorros (resumen detallado)
        fetch(`/ahorros/caja/${id}/listado`)
            .then(response => response.json())
            .then(data => {
                const contenedor = document.getElementById('listado-ahorros');
                contenedor.innerHTML = '';

                if (data.length === 0) {
                    contenedor.innerHTML = '<p>No hay ahorros registrados para esta caja rural.</p>';
                    return;
                }

                let resumenHTML = '<ul class="list-group">';
                data.forEach(ahorro => {
                    const montoFormateado = Number(ahorro.Monto).toLocaleString('es-HN', { minimumFractionDigits: 2 });
                    const fechaFormateada = new Date(ahorro.Fecha).toLocaleDateString('es-HN');
                    const beneficiario = ahorro.beneficiario ? ahorro.beneficiario.Nombre_Beneficiario : 'Sin nombre';

                    resumenHTML += `
                        <li class="list-group-item">
                            <strong>${beneficiario}</strong> - L. ${montoFormateado} - Fecha: ${fechaFormateada}
                        </li>
                    `;
                });
                resumenHTML += '</ul>';

                contenedor.innerHTML = resumenHTML;
            })
            .catch(error => {
                console.error('Error al cargar el listado de ahorros:', error);
            });
    }

    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const cajaId = urlParams.get('caja');

        if (cajaId) {
            document.getElementById('caja-select').value = cajaId;
            cargarDatos();
        }
    });
</script>

@stop
