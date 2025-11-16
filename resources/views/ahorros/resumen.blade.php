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

    /* Inputs dentro de tabla */
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
            <a href="{{ route('ahorros.reportePDF') }}" class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-file-pdf text-danger"></i> Exportar PDF
            </a>
        </div>
    </div>

    <div class="card-body table-responsive">
        <form action="{{ url('/ahorros/guardar-edicion') }}" method="POST">
            @csrf

            <table class="table table-bordered table-striped table-hover text-center align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th rowspan="3">No.</th>
                        <th rowspan="3">Nombre de la Caja Rural</th>

                        <th colspan="3" rowspan="2">Socios</th>
                        <th colspan="9">No Socios</th>
                        <th colspan="3" rowspan="2">Total</th>
                    </tr>

                    <tr>
                        <th colspan="3">Adultos</th>
                        <th colspan="3">Niños</th>
                        <th colspan="3">Sub Total No Socios</th>
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
                        $granTotalNumSocios = $granTotalAhorrosSocios = 0;
                        $granTotalNumAdultos = $granTotalAhorrosAdultos = 0;
                        $granTotalNumNinos  = $granTotalAhorrosNinos = 0;
                        $granTotalNumNoSocios = $granTotalAhorrosNoSocios = 0;
                        $granTotalNumTotal = $granTotalAhorrosTotal = 0;
                    @endphp

                    @forelse ($resumen as $item)
                        @php
                            $id = $item['id'] ?? 0;

                            $num_socios = $item['num_socios'] ?? 0;
                            $ahorro_soc = $item['ahorros_socios'] ?? 0;
                            $prom_soc  = $item['promedio_socios'] ?? 0;

                            $num_ad = $item['num_adultos'] ?? 0;
                            $aho_ad = $item['ahorros_adultos'] ?? 0;
                            $prom_ad = $item['promedio_adultos'] ?? 0;

                            $num_ni = $item['num_ninos'] ?? 0;
                            $aho_ni = $item['ahorros_ninos'] ?? 0;
                            $prom_ni = $item['promedio_ninos'] ?? 0;

                            $num_sub = $num_ad + $num_ni;
                            $aho_sub = $aho_ad + $aho_ni;
                            $prom_sub = $num_sub > 0 ? $aho_sub / $num_sub : 0;

                            $num_total = $num_socios + $num_sub;
                            $aho_total = $ahorro_soc + $aho_sub;
                            $prom_total = $num_total > 0 ? $aho_total / $num_total : 0;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-left fw-bold">{{ $item['caja'] }}</td>

                            <input type="hidden" name="id[]" value="{{ $id }}">

                            {{-- Socios --}}
                            <td><input type="number" name="num_socios[]" value="{{ $num_socios }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="ahorros_socios[]" value="{{ $ahorro_soc }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="promedio_socios[]" value="{{ $prom_soc }}" class="form-control form-control-sm"></td>

                            {{-- Adultos --}}
                            <td><input type="number" name="num_adultos[]" value="{{ $num_ad }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="ahorros_adultos[]" value="{{ $aho_ad }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="promedio_adultos[]" value="{{ $prom_ad }}" class="form-control form-control-sm"></td>

                            {{-- Niños --}}
                            <td><input type="number" name="num_ninos[]" value="{{ $num_ni }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="ahorros_ninos[]" value="{{ $aho_ni }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="promedio_ninos[]" value="{{ $prom_ni }}" class="form-control form-control-sm"></td>

                            {{-- Subtotales --}}
                            <td class="fw-bold">{{ $num_sub }}</td>
                            <td class="fw-bold text-primary">L. {{ number_format($aho_sub, 2) }}</td>
                            <td class="fw-bold text-primary">L. {{ number_format($prom_sub, 2) }}</td>

                            {{-- Totales --}}
                            <td class="fw-bold">{{ $num_total }}</td>
                            <td class="fw-bold text-success">L. {{ number_format($aho_total, 2) }}</td>
                            <td class="fw-bold text-success">L. {{ number_format($prom_total, 2) }}</td>
                        </tr>

                        @php
                            $granTotalNumSocios += $num_socios;
                            $granTotalAhorrosSocios += $ahorro_soc;

                            $granTotalNumAdultos += $num_ad;
                            $granTotalAhorrosAdultos += $aho_ad;

                            $granTotalNumNinos += $num_ni;
                            $granTotalAhorrosNinos += $aho_ni;

                            $granTotalNumNoSocios += $num_sub;
                            $granTotalAhorrosNoSocios += $aho_sub;

                            $granTotalNumTotal += $num_total;
                            $granTotalAhorrosTotal += $aho_total;
                        @endphp

                    @empty
                        <tr>
                            <td colspan="17" class="text-center text-muted py-4">
                                No hay datos para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <th colspan="2" class="text-right">Total General:</th>

                        <th>{{ $granTotalNumSocios }}</th>
                        <th>L. {{ number_format($granTotalAhorrosSocios, 2) }}</th>
                        <th>L. {{ number_format($granTotalNumSocios ? $granTotalAhorrosSocios/$granTotalNumSocios : 0, 2) }}</th>

                        <th>{{ $granTotalNumAdultos }}</th>
                        <th>L. {{ number_format($granTotalAhorrosAdultos, 2) }}</th>
                        <th>L. {{ number_format($granTotalNumAdultos ? $granTotalAhorrosAdultos/$granTotalNumAdultos : 0, 2) }}</th>

                        <th>{{ $granTotalNumNinos }}</th>
                        <th>L. {{ number_format($granTotalAhorrosNinos, 2) }}</th>
                        <th>L. {{ number_format($granTotalNumNinos ? $granTotalAhorrosNinos/$granTotalNumNinos : 0, 2) }}</th>

                        <th>{{ $granTotalNumNoSocios }}</th>
                        <th>L. {{ number_format($granTotalAhorrosNoSocios, 2) }}</th>
                        <th>L. {{ number_format($granTotalNumNoSocios ? $granTotalAhorrosNoSocios/$granTotalNumNoSocios : 0, 2) }}</th>

                        <th class="text-primary">{{ $granTotalNumTotal }}</th>
                        <th class="text-primary">L. {{ number_format($granTotalAhorrosTotal, 2) }}</th>
                        <th class="text-primary">L. {{ number_format($granTotalNumTotal ? $granTotalAhorrosTotal/$granTotalNumTotal : 0, 2) }}</th>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-success fw-bold px-4">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>
@stop
