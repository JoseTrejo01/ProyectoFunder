@extends('adminlte::page')

@section('title', 'Resumen de Ahorros')

@section('content_header')
    <h1 class="text-center text-primary">
        <i class="fas fa-piggy-bank"></i> Resumen General de Ahorros por Caja Rural
    </h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title"><i class="fas fa-chart-pie"></i> Detalle por Caja Rural</h3>
        <div class="card-tools">
            <a href="{{ route('ahorros.reportePDF') }}" class="btn btn-light btn-sm">
                <i class="fas fa-file-pdf text-danger"></i> Exportar PDF
            </a>
        </div>
    </div>

    <div class="card-body table-responsive">
        <form action="{{ url('/ahorros/guardar-edicion') }}" method="POST">
            @csrf
            <table class="table table-bordered table-striped table-hover text-center">
                <thead class="table-dark">
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
                            $num_socios = $item['num_socios'] ?? 0;
                            $ahorros_socios = $item['ahorros_socios'] ?? 0;
                            $promedio_socios = $item['promedio_socios'] ?? 0;

                            $num_adultos = $item['num_adultos'] ?? 0;
                            $ahorros_adultos = $item['ahorros_adultos'] ?? 0;
                            $promedio_adultos = $item['promedio_adultos'] ?? 0;

                            $num_ninos = $item['num_ninos'] ?? 0;
                            $ahorros_ninos = $item['ahorros_ninos'] ?? 0;
                            $promedio_ninos = $item['promedio_ninos'] ?? 0;

                            $num_subtotal_no_socios = $num_adultos + $num_ninos;
                            $ahorros_subtotal_no_socios = $ahorros_adultos + $ahorros_ninos;
                            $promedio_subtotal_no_socios = $num_subtotal_no_socios > 0 ? $ahorros_subtotal_no_socios / $num_subtotal_no_socios : 0;

                            $num_total = $num_socios + $num_subtotal_no_socios;
                            $ahorros_total = $ahorros_socios + $ahorros_subtotal_no_socios;
                            $promedio_total = $num_total > 0 ? $ahorros_total / $num_total : 0;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-left font-weight-bold">{{ $item['caja'] }}</td>

                            {{-- Inputs ocultos con el ID de ahorro --}}
                            <input type="hidden" name="id[]" value="{{ $item['id'] ?? 0 }}">

                            {{-- Socios --}}
                            <td><input type="number" name="num_socios[]" value="{{ $num_socios }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="ahorros_socios[]" value="{{ $ahorros_socios }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="promedio_socios[]" value="{{ $promedio_socios }}" class="form-control form-control-sm"></td>

                            {{-- No Socios - Adultos --}}
                            <td><input type="number" name="num_adultos[]" value="{{ $num_adultos }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="ahorros_adultos[]" value="{{ $ahorros_adultos }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="promedio_adultos[]" value="{{ $promedio_adultos }}" class="form-control form-control-sm"></td>

                            {{-- No Socios - Niños --}}
                            <td><input type="number" name="num_ninos[]" value="{{ $num_ninos }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="ahorros_ninos[]" value="{{ $ahorros_ninos }}" class="form-control form-control-sm"></td>
                            <td><input type="text" name="promedio_ninos[]" value="{{ $promedio_ninos }}" class="form-control form-control-sm"></td>

                            {{-- Sub Total No Socios (solo lectura) --}}
                            <td class="font-weight-bold">{{ $num_subtotal_no_socios }}</td>
                            <td class="font-weight-bold">L. {{ number_format($ahorros_subtotal_no_socios, 2, '.', ',') }}</td>
                            <td class="font-weight-bold">L. {{ number_format($promedio_subtotal_no_socios, 2, '.', ',') }}</td>

                            {{-- Total General (solo lectura) --}}
                            <td class="font-weight-bold">{{ $num_total }}</td>
                            <td class="font-weight-bold">L. {{ number_format($ahorros_total, 2, '.', ',') }}</td>
                            <td class="font-weight-bold">L. {{ number_format($promedio_total, 2, '.', ',') }}</td>
                        </tr>

                        @php
                            $granTotalNumSocios += $num_socios;
                            $granTotalAhorrosSocios += $ahorros_socios;
                            $granTotalNumAdultos += $num_adultos;
                            $granTotalAhorrosAdultos += $ahorros_adultos;
                            $granTotalNumNinos += $num_ninos;
                            $granTotalAhorrosNinos += $ahorros_ninos;
                            $granTotalNumNoSocios += $num_subtotal_no_socios;
                            $granTotalAhorrosNoSocios += $ahorros_subtotal_no_socios;
                            $granTotalNumTotal += $num_total;
                            $granTotalAhorrosTotal += $ahorros_total;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="17">No hay datos para mostrar.</td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot class="table-secondary">
                    <tr>
                        <th colspan="2" class="text-right">Total General:</th>

                        <th>{{ number_format($granTotalNumSocios) }}</th>
                        <th>L. {{ number_format($granTotalAhorrosSocios, 2, '.', ',') }}</th>
                        <th>L. {{ number_format($granTotalNumSocios > 0 ? $granTotalAhorrosSocios / $granTotalNumSocios : 0, 2, '.', ',') }}</th>

                        <th>{{ number_format($granTotalNumAdultos) }}</th>
                        <th>L. {{ number_format($granTotalAhorrosAdultos, 2, '.', ',') }}</th>
                        <th>L. {{ number_format($granTotalNumAdultos > 0 ? $granTotalAhorrosAdultos / $granTotalNumAdultos : 0, 2, '.', ',') }}</th>

                        <th>{{ number_format($granTotalNumNinos) }}</th>
                        <th>L. {{ number_format($granTotalAhorrosNinos, 2, '.', ',') }}</th>
                        <th>L. {{ number_format($granTotalNumNinos > 0 ? $granTotalAhorrosNinos / $granTotalNumNinos : 0, 2, '.', ',') }}</th>

                        <th class="font-weight-bold">{{ number_format($granTotalNumNoSocios) }}</th>
                        <th class="font-weight-bold">L. {{ number_format($granTotalAhorrosNoSocios, 2, '.', ',') }}</th>
                        <th class="font-weight-bold">L. {{ number_format($granTotalNumNoSocios > 0 ? $granTotalAhorrosNoSocios / $granTotalNumNoSocios : 0, 2, '.', ',') }}</th>

                        <th class="text-primary">{{ number_format($granTotalNumTotal) }}</th>
                        <th class="text-primary">L. {{ number_format($granTotalAhorrosTotal, 2, '.', ',') }}</th>
                        <th class="text-primary">L. {{ number_format($granTotalNumTotal > 0 ? $granTotalAhorrosTotal / $granTotalNumTotal : 0, 2, '.', ',') }}</th>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-3 text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@stop
