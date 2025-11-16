@extends('adminlte::page')

@section('title', 'Resumen de Ahorros')

@section('css')
<style>
    /* Mejor contraste en tabla */
    .thead-dark th {
        background-color: #1b263b !important;
        color: #fff !important;
        border-color: #0f172a !important;
        font-size: 0.9rem;
    }

    th, td {
        vertical-align: middle !important;
    }

    /* Inputs dentro de tabla (por si algún día se usan) */
    .table input {
        text-align: center;
        font-size: 0.85rem;
        padding: 3px;
        border-radius: 4px;
        border: 1px solid #6c757d;
    }

    .table input:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 4px rgba(13,110,253,.4);
    }

    /* Totales */
    .font-weight-bold {
        font-weight: 700 !important;
    }

    /* Encabezado de tarjeta */
    .card-header {
        background-color: #0d6efd !important;
        border-bottom: 3px solid #003f88 !important;
    }
</style>
@stop

@section('content_header')
    <h1 class="text-center text-primary fw-bold">
        <i class="fas fa-piggy-bank"></i> Resumen General de Ahorros por Caja Rural
    </h1>
@stop

@section('content')
<div class="card shadow border-0">
    <div class="card-header text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold">
            <i class="fas fa-chart-pie"></i> Detalle por Caja Rural
        </h3>
        <div>
            {{-- Botón para exportar PDF --}}
            <a href="{{ route('ahorros.reportePDF') }}" class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-file-pdf text-danger"></i> Exportar PDF
            </a>
        </div>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover text-center align-middle">
            <thead class="thead-dark">
                <tr>
                    <th rowspan="3" class="align-middle">No.</th>
                    <th rowspan="3" class="align-middle">Nombre de la Caja Rural</th>

                    <th colspan="3" rowspan="2" class="text-center align-middle">Socios</th>
                    <th colspan="9" class="text-center">No Socios</th>
                    <th colspan="3" rowspan="2" class="text-center align-middle">Total</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-center">Adultos</th>
                    <th colspan="3" class="text-center">Niños</th>
                    <th colspan="3" class="text-center">Sub Total No Socios</th>
                </tr>
                <tr>
                    <th>No.</th>
                    <th>Ahorros</th>
                    <th>Promedio</th>

                    <th>No.</th>
                    <th>Ahorros</th>
                    <th>Promedio</th>

                    <th>No.</th>
                    <th>Ahorros</th>
                    <th>Promedio</th>

                    <th>No.</th>
                    <th>Ahorros</th>
                    <th>Promedio</th>

                    <th>No.</th>
                    <th>Ahorros</th>
                    <th>Promedio</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $granTotalNumSocios = 0;
                    $granTotalAhorrosSocios = 0;

                    $granTotalNumAdultos = 0;
                    $granTotalAhorrosAdultos = 0;

                    $granTotalNumNinos = 0;
                    $granTotalAhorrosNinos = 0;

                    $granTotalNumNoSocios = 0;
                    $granTotalAhorrosNoSocios = 0;

                    $granTotalNumTotal = 0;
                    $granTotalAhorrosTotal = 0;
                @endphp

                @forelse ($resumen as $item)
                    @php
                        // Socios
                        $num_socios       = $item['num_socios']       ?? 0;
                        $ahorros_socios   = $item['ahorros_socios']   ?? 0;
                        $promedio_socios  = $item['promedio_socios']  ?? 0;

                        // No socios - adultos
                        $num_adultos      = $item['num_adultos']      ?? 0;
                        $ahorros_adultos  = $item['ahorros_adultos']  ?? 0;
                        $promedio_adultos = $item['promedio_adultos'] ?? 0;

                        // No socios - niños
                        $num_ninos        = $item['num_ninos']        ?? 0;
                        $ahorros_ninos    = $item['ahorros_ninos']    ?? 0;
                        $promedio_ninos   = $item['promedio_ninos']   ?? 0;

                        // Subtotal no socios
                        $num_subtotal_no_socios      = $num_adultos + $num_ninos;
                        $ahorros_subtotal_no_socios  = $ahorros_adultos + $ahorros_ninos;
                        $promedio_subtotal_no_socios = $num_subtotal_no_socios > 0
                            ? $ahorros_subtotal_no_socios / $num_subtotal_no_socios
                            : 0;

                        // Totales generales por caja
                        $num_total      = $num_socios + $num_subtotal_no_socios;
                        $ahorros_total  = $ahorros_socios + $ahorros_subtotal_no_socios;
                        $promedio_total = $num_total > 0 ? $ahorros_total / $num_total : 0;

                        // Acumulados generales
                        $granTotalNumSocios        += $num_socios;
                        $granTotalAhorrosSocios    += $ahorros_socios;

                        $granTotalNumAdultos       += $num_adultos;
                        $granTotalAhorrosAdultos   += $ahorros_adultos;

                        $granTotalNumNinos         += $num_ninos;
                        $granTotalAhorrosNinos     += $ahorros_ninos;

                        $granTotalNumNoSocios      += $num_subtotal_no_socios;
                        $granTotalAhorrosNoSocios  += $ahorros_subtotal_no_socios;

                        $granTotalNumTotal         += $num_total;
                        $granTotalAhorrosTotal     += $ahorros_total;
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left font-weight-bold">{{ $item['caja'] }}</td>

                        {{-- Socios --}}
                        <td>{{ $num_socios }}</td>
                        <td>L. {{ number_format($ahorros_socios, 2, '.', ',') }}</td>
                        <td>L. {{ number_format($promedio_socios, 2, '.', ',') }}</td>

                        {{-- No Socios - Adultos --}}
                        <td>{{ $num_adultos }}</td>
                        <td>L. {{ number_format($ahorros_adultos, 2, '.', ',') }}</td>
                        <td>L. {{ number_format($promedio_adultos, 2, '.', ',') }}</td>

                        {{-- No Socios - Niños --}}
                        <td>{{ $num_ninos }}</td>
                        <td>L. {{ number_format($ahorros_ninos, 2, '.', ',') }}</td>
                        <td>L. {{ number_format($promedio_ninos, 2, '.', ',') }}</td>

                        {{-- Sub Total No Socios --}}
                        <td class="font-weight-bold">{{ $num_subtotal_no_socios }}</td>
                        <td class="font-weight-bold">
                            L. {{ number_format($ahorros_subtotal_no_socios, 2, '.', ',') }}
                        </td>
                        <td class="font-weight-bold">
                            L. {{ number_format($promedio_subtotal_no_socios, 2, '.', ',') }}
                        </td>

                        {{-- Total General por caja --}}
                        <td class="font-weight-bold">{{ $num_total }}</td>
                        <td class="font-weight-bold">
                            L. {{ number_format($ahorros_total, 2, '.', ',') }}
                        </td>
                        <td class="font-weight-bold">
                            L. {{ number_format($promedio_total, 2, '.', ',') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17" class="text-center text-muted py-4">
                            No hay datos para mostrar.
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <tfoot class="table-secondary">
                <tr>
                    <th colspan="2" class="text-right">Total General:</th>

                    {{-- Socios --}}
                    <th>{{ number_format($granTotalNumSocios) }}</th>
                    <th>L. {{ number_format($granTotalAhorrosSocios, 2, '.', ',') }}</th>
                    <th>
                        L.
                        {{ number_format(
                            $granTotalNumSocios > 0
                                ? $granTotalAhorrosSocios / $granTotalNumSocios
                                : 0,
                            2,
                            '.',
                            ','
                        ) }}
                    </th>

                    {{-- Adultos --}}
                    <th>{{ number_format($granTotalNumAdultos) }}</th>
                    <th>L. {{ number_format($granTotalAhorrosAdultos, 2, '.', ',') }}</th>
                    <th>
                        L.
                        {{ number_format(
                            $granTotalNumAdultos > 0
                                ? $granTotalAhorrosAdultos / $granTotalNumAdultos
                                : 0,
                            2,
                            '.',
                            ','
                        ) }}
                    </th>

                    {{-- Niños --}}
                    <th>{{ number_format($granTotalNumNinos) }}</th>
                    <th>L. {{ number_format($granTotalAhorrosNinos, 2, '.', ',') }}</th>
                    <th>
                        L.
                        {{ number_format(
                            $granTotalNumNinos > 0
                                ? $granTotalAhorrosNinos / $granTotalNumNinos
                                : 0,
                            2,
                            '.',
                            ','
                        ) }}
                    </th>

                    {{-- Sub Total No Socios --}}
                    <th class="font-weight-bold">{{ number_format($granTotalNumNoSocios) }}</th>
                    <th class="font-weight-bold">
                        L. {{ number_format($granTotalAhorrosNoSocios, 2, '.', ',') }}
                    </th>
                    <th class="font-weight-bold">
                        L.
                        {{ number_format(
                            $granTotalNumNoSocios > 0
                                ? $granTotalAhorrosNoSocios / $granTotalNumNoSocios
                                : 0,
                            2,
                            '.',
                            ','
                        ) }}
                    </th>

                    {{-- Total General --}}
                    <th class="text-primary">{{ number_format($granTotalNumTotal) }}</th>
                    <th class="text-primary">
                        L. {{ number_format($granTotalAhorrosTotal, 2, '.', ',') }}
                    </th>
                    <th class="text-primary">
                        L.
                        {{ number_format(
                            $granTotalNumTotal > 0
                                ? $granTotalAhorrosTotal / $granTotalNumTotal
                                : 0,
                            2,
                            '.',
                            ','
                        ) }}
                    </th>
                </tr>
            </tfoot>
        </table>

        <div class="mt-3 text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Atrás
            </a>
        </div>
    </div>
</div>
@stop
