<?php

namespace App\Http\Controllers;

use App\Models\Ahorro;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AhorroController extends Controller
{
    // Mostrar lista paginada de ahorros
    public function index()
    {
        $ahorros = Ahorro::paginate(15);
        return view('ahorros.index', compact('ahorros'));
    }

    // Mostrar formulario para crear nuevo registro
    public function create()
    {
        return view('ahorros.create');
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_caja_rural' => 'required|string|max:255',

            'socios_no' => 'required|integer',
            'socios_ahorros' => 'required|numeric',
            'socios_promedio' => 'required|numeric',

            'adultos_no' => 'required|integer',
            'adultos_ahorros' => 'required|numeric',
            'adultos_promedio' => 'required|numeric',

            'ninos_no' => 'required|integer',
            'ninos_ahorros' => 'required|numeric',
            'ninos_promedio' => 'required|numeric',

            'subtotal_no_socios_no' => 'required|integer',
            'subtotal_no_socios_ahorros' => 'required|numeric',
            'subtotal_no_socios_promedio' => 'required|numeric',

            'total_no' => 'required|integer',
            'total_ahorros' => 'required|numeric',
            'total_promedio' => 'required|numeric',
        ]);

        Ahorro::create($data);

        return redirect()->route('ahorros.index')->with('success', 'Ahorro registrado correctamente.');
    }

    // Mostrar formulario para editar
    public function edit($id)
    {
        $ahorro = Ahorro::findOrFail($id);
        return view('ahorros.edit', compact('ahorro'));
    }

    // Actualizar un registro existente
    public function update(Request $request, $id)
    {
        $ahorro = Ahorro::findOrFail($id);

        $data = $request->validate([
            'nombre_caja_rural' => 'required|string|max:255',

            'socios_no' => 'required|integer',
            'socios_ahorros' => 'required|numeric',
            'socios_promedio' => 'required|numeric',

            'adultos_no' => 'required|integer',
            'adultos_ahorros' => 'required|numeric',
            'adultos_promedio' => 'required|numeric',

            'ninos_no' => 'required|integer',
            'ninos_ahorros' => 'required|numeric',
            'ninos_promedio' => 'required|numeric',

            'subtotal_no_socios_no' => 'required|integer',
            'subtotal_no_socios_ahorros' => 'required|numeric',
            'subtotal_no_socios_promedio' => 'required|numeric',

            'total_no' => 'required|integer',
            'total_ahorros' => 'required|numeric',
            'total_promedio' => 'required|numeric',
        ]);

        $ahorro->update($data);

        return redirect()->route('ahorros.index')->with('success', 'Ahorro actualizado correctamente.');
    }

    // Eliminar un registro
    public function destroy($id)
    {
        $ahorro = Ahorro::findOrFail($id);
        $ahorro->delete();

        return redirect()->route('ahorros.index')->with('success', 'Ahorro eliminado correctamente.');
    }

    // ✅ Mostrar ficha individual
    public function ficha($id)
    {
        $ahorro = Ahorro::findOrFail($id);
        return view('ahorros.ficha', compact('ahorro'));
    }

    // 🟥 Exportar todos los ahorros a PDF
    public function exportPdf()
    {
        $ahorros = Ahorro::all();
        $pdf = Pdf::loadView('ahorros.pdf', compact('ahorros'))->setPaper('landscape', 'letter');
        return $pdf->download('reporte_ahorros.pdf');
    }
}
