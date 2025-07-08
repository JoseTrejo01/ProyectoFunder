<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organizacion;
use App\Models\Prestamo;
use App\Models\Pago; 

class PrestamoController extends Controller
{
    public function create()
    {
        // Traer organizaciones para mostrar en el select
        $organizaciones = Organizacion::all();
        return view('prestamos.crear', compact('organizaciones'));
    }


    public function pendientes()
{
    $prestamos = Prestamo::where('estado', 'pendiente')->get();
    return view('prestamos.pending', compact('prestamos'));
}
public function aprobar($id)
{
    $prestamo = Prestamo::findOrFail($id);
    $prestamo->estado = 'aprobado';
    $prestamo->save();

    return redirect()->route('creditos')->with('success', 'Solicitud aprobada.');
}

public function rechazar($id)
{
    $prestamo = Prestamo::findOrFail($id);
    $prestamo->estado = 'rechazado';
    $prestamo->save();

    return redirect()->route('creditos')->with('success', 'Solicitud rechazada.');
}
public function desembolsar($id)
{
    $prestamo = Prestamo::findOrFail($id);

    if ($prestamo->estado !== 'aprobado') {
        return redirect()->route('creditos')->with('error', 'Solo se pueden desembolsar préstamos aprobados.');
    }

    // Cambiar el estado a 'desembolsado'
    $prestamo->estado = 'desembolsado';
    $prestamo->save();

    // Registrar el pago por el monto total del préstamo
    Pago::create([
        'prestamo_id'   => $prestamo->id,
        'fecha_pago'    => now()->toDateString(),
        'monto_pagado'  => $prestamo->monto_solicitado,
        'observaciones' => 'Desembolso inicial del préstamo'
    ]);

    return redirect()->route('creditos')->with('success', 'Préstamo desembolsado y pago inicial registrado.');
}

    public function index()
{
    // Aquí puedes mostrar una lista de solicitudes de préstamo, por ejemplo:
    $prestamos = Prestamo::all(); // Importa el modelo arriba con use App\Models\Prestamo;
    return view('prestamos.index', compact('prestamos'));
}

   public function store(Request $request)
{
    $validated = $request->validate([
        'socio_id' => 'required|exists:tbl_organizacion,Id_Organizacion',
        'nombre_caja_rural' => 'required|string|max:255',
        'monto_solicitado' => 'required|numeric',
        'plazo_meses' => 'required|integer',
        'destino' => 'required|string|max:255',
        'tipo_credito' => 'required|string|max:255',
        'fecha_solicitud' => 'required|date',
        'porcentaje_mora_caja' => 'nullable|numeric',
        'intereses_cobrados' => 'nullable|numeric',
        'capital_social' => 'nullable|numeric',
        'capital_trabajo' => 'nullable|numeric',
        'reservas' => 'nullable|numeric',
        'estado' => 'nullable|string',
        'observaciones' => 'nullable|string',
    ]);

    // Cálculo del puntaje automático
    $puntaje = 0;

    if ($request->porcentaje_mora_caja < 5) $puntaje += 30;
    if ($request->capital_social > 50000) $puntaje += 20;
    if ($request->capital_trabajo > 30000) $puntaje += 15;
    if ($request->reservas > 10000) $puntaje += 10;
    if ($request->intereses_cobrados > 5000) $puntaje += 5;



    // Guardar la solicitud
    Prestamo::create([
        'socio_id' => $request->socio_id,
        'nombre_caja_rural' => $request->nombre_caja_rural,
        'monto_solicitado' => $request->monto_solicitado,
        'plazo_meses' => $request->plazo_meses,
        'destino' => $request->destino,
        'tipo_credito' => $request->tipo_credito,
        'fecha_solicitud' => $request->fecha_solicitud,
        'porcentaje_mora_caja' => $request->porcentaje_mora_caja,
        'intereses_cobrados' => $request->intereses_cobrados,
        'capital_social' => $request->capital_social,
        'capital_trabajo' => $request->capital_trabajo,
        'reservas' => $request->reservas,
        'estado' => $request->estado ?? 'pendiente',
        'puntaje' => $puntaje,
        'observaciones' => $request->observaciones,
    ]);

    return redirect()->route('creditos')->with('success', 'Solicitud registrada con puntaje automático.');
}

}
