<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Prestamo;

class PagoController extends Controller
{
    public function index($prestamoId)
    {
        $prestamo = Prestamo::with('pagos')->findOrFail($prestamoId);
        return view('pagos.index', compact('prestamo'));
    }
    

    public function create($prestamoId)
    {
        $prestamo = Prestamo::findOrFail($prestamoId);
        return view('pagos.create', compact('prestamo'));
    }

    public function store(Request $request, $prestamoId)
    {
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

        return redirect()->route('pagos.index', $prestamoId)->with('success', 'Pago registrado correctamente.');
    }
    public function marcarPagado($id)
{
    $pago = Pago::findOrFail($id);
    $pago->estado = 'pagado';
    $pago->fecha_pago_real = now(); // Si quieres registrar la fecha real del pago
    $pago->save();

    return back()->with('success', 'Pago marcado como pagado.');
}
}
