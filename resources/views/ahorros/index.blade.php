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

            <ul class="nav nav-tabs mt-4" id="ahorroTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="socios-tab" data-toggle="tab" href="#socios" role="tab"><strong>Socios</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="clientes-tab" data-toggle="tab" href="#clientes" role="tab"><strong>Clientes</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="totales-tab" data-toggle="tab" href="#totales" role="tab"><strong>Totales</strong></a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="ahorroTabsContent">
                <div class="tab-pane fade show active" id="socios" role="tabpanel">
                    <div id="lista-socios"></div>
                </div>
                <div class="tab-pane fade" id="clientes" role="tabpanel">
                    <div id="lista-clientes"></div>
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
@stop

@section('js')
<script>
    function cargarDatos() {
        const id = document.getElementById('caja-select').value;
        if (!id) {
            // Limpiar todo si no hay caja seleccionada
            document.getElementById('lista-socios').innerHTML = '';
            document.getElementById('lista-clientes').innerHTML = '';
            document.getElementById('resumen-totales').innerHTML = '';
            document.getElementById('listado-ahorros').innerHTML = '';
            return;
        }

        // Fetch socios y clientes con sus ahorros y resumen
        fetch(`/api/ahorros/caja/${id}/socios`)
            .then(response => response.json())
            .then(data => {
                const listaSocios = document.getElementById('lista-socios');
                const listaClientes = document.getElementById('lista-clientes');
                const resumenTotales = document.getElementById('resumen-totales');

                listaSocios.innerHTML = '';
                listaClientes.innerHTML = '';
                resumenTotales.innerHTML = '';

                // Mostrar socios
                if (data.socios && data.socios.length > 0) {
                    data.socios.forEach(socio => {
                        const monto = parseFloat(socio.Monto ?? 0).toLocaleString('es-HN', { minimumFractionDigits: 2 });
                        listaSocios.innerHTML += `<p><strong>${socio.Nombre_Beneficiario}</strong> - L. ${monto}</p>`;
                    });
                } else {
                    listaSocios.innerHTML = '<p>No hay socios registrados.</p>';
                }

                // Mostrar clientes
                if (data.clientes && data.clientes.length > 0) {
                    data.clientes.forEach(cliente => {
                        const monto = parseFloat(cliente.Monto ?? 0).toLocaleString('es-HN', { minimumFractionDigits: 2 });
                        listaClientes.innerHTML += `<p><strong>${cliente.Nombre_Beneficiario}</strong> - L. ${monto}</p>`;
                    });
                } else {
                    listaClientes.innerHTML = '<p>No hay clientes registrados.</p>';
                }

                // Mostrar resumen totales
                const totalSocios = data.socios.reduce((sum, s) => sum + parseFloat(s.Monto ?? 0), 0);
                const totalClientes = data.clientes.reduce((sum, c) => sum + parseFloat(c.Monto ?? 0), 0);

                const totalPersonas = (data.socios.length + data.clientes.length);
                const totalAhorros = totalSocios + totalClientes;
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

                let tablaHTML = `
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre del Beneficiario</th>
                                    <th>Monto (Lps)</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                data.forEach(ahorro => {
                    const montoFormateado = Number(ahorro.Monto).toLocaleString('es-HN', { minimumFractionDigits: 2 });
                    const fechaFormateada = new Date(ahorro.Fecha).toLocaleDateString('es-HN');
                    const beneficiario = ahorro.beneficiario ? ahorro.beneficiario.Nombre_Beneficiario : 'Sin nombre';

                    tablaHTML += `
                        <tr>
                            <td>${beneficiario}</td>
                            <td>L. ${montoFormateado}</td>
                            <td>${fechaFormateada}</td>
                        </tr>
                    `;
                });

                tablaHTML += `
                            </tbody>
                        </table>
                    </div>
                `;

                contenedor.innerHTML = tablaHTML;
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
