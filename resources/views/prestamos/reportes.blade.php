@extends('adminlte::page')

@section('title', 'Créditos')

@section('content_header')
<div class="d-flex justify-content-between align-items-center" role="banner">
    <h1 id="titulo-creditos" class="m-0 fw-bold">Listado de Préstamos</h1>

    <div role="group" aria-label="Acciones principales">

        {{-- Nueva Solicitud --}}
        <a href="{{ route('prestamos.create') }}"
           class="btn fw-semibold"
           style="background-color:#0D47A1; color:white;"
           aria-label="Crear nueva solicitud de préstamo">
            Nueva Solicitud
        </a>

        {{-- Reportes --}}
        <a href="{{ route('creditos.reportes') }}"
           class="btn ms-2 fw-semibold"
           style="background-color:#1B5E20; color:white;"
           aria-label="Abrir reportes y métricas de créditos">
            Reportes y Métricas
        </a>

        {{-- Exportar PDF --}}
        <a href="{{ route('prestamos.pdf') }}"
           class="btn fw-semibold ms-2"
           style="background-color:#B71C1C; color:white;"
           aria-label="Exportar listado completo en PDF">
            Exportar listado (PDF)
        </a>

    </div>
</div>
@endsection

@section('content')
<div class="tab-content mt-3" id="creditosTabsContent" role="main" aria-labelledby="titulo-creditos">

    {{-- TAB LISTADO --}}
    <div class="tab-pane fade show active"
         id="listado"
         role="tabpanel"
         aria-labelledby="listado-tab">

        @if($prestamos->count())

            <div class="table-responsive" role="region" aria-labelledby="tabla-prestamos-titulo">

                <table class="table table-bordered table-hover align-middle" aria-describedby="tabla-descripcion">
                    <caption id="tabla-prestamos-titulo" class="visually-hidden">
                        Tabla con el listado de préstamos registrados en el sistema.
                    </caption>

                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Socio</th>
                            <th scope="col">Monto</th>
                            <th scope="col">Destino</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha de Solicitud</th>
                            <th scope="col">Acciones</th>
                            <th scope="col">Pagos</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($prestamos as $prestamo)
                            <tr>
                                <td>{{ $prestamo->id }}</td>

                                <td>{{ $prestamo->organizacion->Nombre_Organizacion ?? 'N/A' }}</td>

                                <td>L. {{ number_format($prestamo->monto_solicitado, 2) }}</td>

                                <td>{{ $prestamo->destino }}</td>

                                <td>
                                    {{-- BADGES FUNDER --}}
                                    @switch($prestamo->estado)

                                        @case('pendiente')
                                            <span class="badge"
                                                  style="background-color:#F9A825; color:black;"
                                                  aria-label="Estado: Pendiente">
                                                Pendiente
                                            </span>
                                            @break

                                        @case('aprobado')
                                            <span class="badge"
                                                  style="background-color:#1B5E20; color:white;"
                                                  aria-label="Estado: Aprobado">
                                                Aprobado
                                            </span>
                                            @break

                                        @case('rechazado')
                                            <span class="badge"
                                                  style="background-color:#B71C1C; color:white;"
                                                  aria-label="Estado: Rechazado">
                                                Rechazado
                                            </span>
                                            @break

                                        @case('desembolsado')
                                            <span class="badge"
                                                  style="background-color:#0D47A1; color:white;"
                                                  aria-label="Estado: Desembolsado">
                                                Desembolsado
                                            </span>
                                            @break

                                        @default
                                            <span class="badge bg-secondary" aria-label="Estado no disponible">
                                                N/A
                                            </span>

                                    @endswitch
                                </td>

                                <td>{{ \Carbon\Carbon::parse($prestamo->fecha_solicitud)->format('d/m/Y') }}</td>

                                {{-- ACCIONES --}}
                                <td>

                                    @if ($prestamo->estado === 'pendiente')

                                        {{-- Aprobar --}}
                                        <form action="{{ route('prestamos.aprobar', $prestamo->id) }}"
                                              method="POST"
                                              style="display:inline-block"
                                              aria-label="Aprobar préstamo {{ $prestamo->id }}">
                                            @csrf @method('PUT')
                                            <button class="btn btn-sm fw-semibold"
                                                    style="background-color:#1B5E20; color:white;">
                                                Aprobar
                                            </button>
                                        </form>

                                        {{-- Rechazar --}}
                                        <form action="{{ route('prestamos.rechazar', $prestamo->id) }}"
                                              method="POST"
                                              style="display:inline-block"
                                              aria-label="Rechazar préstamo {{ $prestamo->id }}">
                                            @csrf @method('PUT')
                                            <button class="btn btn-sm fw-semibold"
                                                    style="background-color:#B71C1C; color:white;">
                                                Rechazar
                                            </button>
                                        </form>

                                    @elseif ($prestamo->estado === 'aprobado')

                                        {{-- Desembolsar --}}
                                        <form action="{{ route('prestamos.desembolsar', $prestamo->id) }}"
                                              method="POST"
                                              style="display:inline-block"
                                              aria-label="Desembolsar préstamo {{ $prestamo->id }}">
                                            @csrf
                                            <button class="btn btn-sm fw-semibold"
                                                    style="background-color:#F9A825; color:black;">
                                                Desembolsar
                                            </button>
                                        </form>

                                    @else
                                        <span class="text-muted" aria-label="Sin acciones disponibles">
                                            Sin acciones
                                        </span>
                                    @endif

                                </td>

                                {{-- PAGOS --}}
                                <td>
                                    <a href="{{ route('pagos.index', $prestamo->id) }}"
                                       class="btn btn-sm fw-semibold"
                                       style="background-color:#424242; color:white;"
                                       aria-label="Ver pagos del préstamo {{ $prestamo->id }}">
                                        Ver Pagos
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        @else
            <div class="alert alert-info" role="alert" aria-live="polite">
                No hay préstamos registrados.
            </div>
        @endif
    </div>

    {{-- TAB REPORTES --}}
    <div class="tab-pane fade mt-4"
         id="reportes"
         role="tabpanel"
         aria-labelledby="reportes-tab">

        <h3 class="fw-bold">Reportes y Métricas</h3>

        <ul>
            <li><strong>Total de préstamos por mes:</strong></li>
            <ul>
                @foreach($prestamosPorMes as $item)
                    <li>{{ $item->anio }}-{{ str_pad($item->mes, 2, '0', STR_PAD_LEFT) }} :
                        {{ $item->total }} préstamos
                    </li>
                @endforeach
            </ul>

            <li><strong>Total desembolsado:</strong>
                Lps {{ number_format($totalDesembolsado, 2) }}
            </li>

            <li><strong>Porcentaje de aprobación:</strong>
                {{ $porcentajeAprobado }}%
            </li>

            <li><strong>Top 5 organizaciones que más solicitan:</strong>
                <ul>
                    @foreach($topOrganizaciones as $org)
                        <li>{{ $org->organizacion->Nombre_Organizacion ?? 'N/A' }} -
                            {{ $org->total }} solicitudes
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>

    </div>

</div>
@endsection
