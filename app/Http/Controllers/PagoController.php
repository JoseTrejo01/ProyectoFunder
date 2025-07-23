<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Prestamo;

class PagoController extends Controller
{
    public function index($prestamoId)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Créditos', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar pagos.');
        }
        $objeto = \App\Models\Objeto::where('Objeto', 'Créditos')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario ingresó a la gestión de pagos del préstamo ID: ' . $prestamoId
            );
        }
        $prestamo = Prestamo::with('pagos')->findOrFail($prestamoId);
        return view('pagos.index', compact('prestamo'));
    }

    public function create($prestamoId)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Créditos', 'Insercion')) {
            abort(403, 'No tienes permiso para registrar pagos.');
        }
        $objeto = \App\Models\Objeto::where('Objeto', 'Créditos')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario ingresó a la creación de pagos del préstamo ID: ' . $prestamoId
            );
        }
        $prestamo = Prestamo::findOrFail($prestamoId);
        return view('pagos.create', compact('prestamo'));
    }

    public function store(Request $request, $prestamoId)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Créditos', 'Insercion')) {
            abort(403, 'No tienes permiso para registrar pagos.');
        }
        $request->validate([
            'fecha_pago' => 'required|date',
            'monto_pagado' => 'required|numeric|min:0.01',
            'observaciones' => 'nullable|string|max:255',
        ]);

        Pago::create([
            'prestamo_id' => $prestamoId,
            'fecha_pago' => $request->fecha_pago,
            'monto_pagado' => $request->monto_pagado,
            'observaciones' => $request->observaciones,
        ]);

        $objeto = \App\Models\Objeto::where('Objeto', 'Créditos')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Registró un pago para el préstamo ID: ' . $prestamoId
            );
        }

        return redirect()->route('pagos.index', $prestamoId)->with('success', 'Pago registrado correctamente.');
    }
}
