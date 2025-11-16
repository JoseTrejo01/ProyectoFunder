@extends('adminlte::page')

@section('title', 'Pagos del Préstamo')

@section('content_header')
    <h1 id="titulo-pagos" class="fw-bold">
        Pagos del Préstamo #{{ $prestamo->id }}
    </h1>
@stop

@section('content')
<div role="main" aria-labelledby="titulo-pagos">

    {{-- BOTÓN PDF --}}
    <a href="{{ route('prestamos.pagos.pdf', $prestamo->id) }}"
       target="_blank"
       class="btn fw-semibold mb-3"
       style="background-color:#B71C1C; color:white;"
       role="button"
       aria-label="Ver pagos del préstamo en formato PDF">
        Ver Pagos (PDF)
    </a>

    {{-- VALIDACIÓN DE REGISTROS --}}
    @if($prestamo->pagos->count())

        <div class="table-responsive" role="region" aria-labelledby="tabla-pagos">
            <table class="table table-striped table-hover align-middle"
                   id="tabla-pagos"
                   aria-describedby="tabla-ayuda">
                <caption class="visually-hidden">Listado de pagos correspondientes al préstamo.</caption>

                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID Pago</th>
                        <th scope="col">Fecha Programada</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Observaciones</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($prestamo->pagos as $pago)
                        <tr>
                            <td>{{ $pago->id }}</td>

                            <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>

                            <td>{{ number_format($pago->monto_pagado, 2) }}</td>

                            {{-- ESTADO ACCESIBLE --}}
                            <td>
                                @if ($pago->estado === 'pagado')
                                    <span class="badge bg-success"
                                          aria-label="Pago realizado">
                                        Pagado
                                    </span>

                                @elseif ($pago->estado === 'pendiente')
                                    <span class="badge bg-warning text-dark"
                                          aria-label="Pago pendiente">
                                        Pendiente
                                    </span>

                                @elseif ($pago->estado === 'atrasado')
                                    <span class="badge bg-danger"
                                          aria-label="Pago atrasado">
                                        Atrasado
                                    </span>

                                @else
                                    <span class="badge bg-secondary"
                                          aria-label="Estado desconocido">
                                        {{ ucfirst($pago->estado) }}
                                    </span>
                                @endif
                            </td>

                            <td>{{ $pago->observaciones }}</td>

                            {{-- ACCIONES --}}
                            <td>
                                @if ($pago->estado !== 'pagado')

                                    @if ($pago->prestamo->estado === 'aprobado')

                                        <form action="{{ route('pagos.marcarPagado', $pago->id) }}"
                                              method="POST"
                                              style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm fw-semibold"
                                                    style="background-color:#0D47A1; color:white;"
                                                    aria-label="Marcar pago {{ $pago->id }} como pagado">
                                                Marcar como pagado
                                            </button>
                                        </form>

                                    @else
                                        <button class="btn btn-sm btn-warning"
                                                disabled
                                                aria-disabled="true"
                                                title="El préstamo no está aprobado. No se puede marcar como pagado.">
                                            Marcar como pagado
                                        </button>
                                    @endif

                                @else
                                    <span class="text-muted"
                                          aria-label="Este pago ya está marcado como pagado">
                                        -
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

            <p id="tabla-ayuda" class="visually-hidden">
                La tabla muestra la información de todos los pagos realizados y pendientes del préstamo seleccionado.
            </p>

        </div>

    @else
        <p role="alert" aria-live="polite">
            No hay pagos registrados para este préstamo.
        </p>
    @endif

</div>
@stop
