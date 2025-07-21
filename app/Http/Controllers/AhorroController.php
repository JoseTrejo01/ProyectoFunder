<?php

namespace App\Http\Controllers;

use App\Models\Ahorro;
use App\Models\Organizacion;
use App\Models\Beneficiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AhorroController extends Controller
{
    // Página principal del módulo de ahorros
    public function index()
    {
        $organizaciones = Organizacion::all();
        return view('ahorros.index', compact('organizaciones'));
    }

    // Endpoint para obtener el resumen por caja rural
   public function resumen($id)
{
    $num_socios = \DB::table('tbl_beneficiario')
        ->where('Id_Organizacion', $id)
        ->where('Tipo_De_Socio', 'Socio')
        ->count();

    $total_ahorros = \DB::table('tbl_ahorros')
        ->where('Id_Organizacion', $id)
        ->sum('total_ahorros');

    $promedio_ahorros = $num_socios > 0 ? $total_ahorros / $num_socios : 0;

    return response()->json([
        'num_socios' => $num_socios,
        'total_ahorros' => $total_ahorros,
        'promedio_ahorros' => $promedio_ahorros,
    ]);
}


    // Mostrar formulario para crear nuevo registro
    public function create()
    {
        $cajas = Organizacion::all();
        return view('ahorros.create', compact('cajas'));
    }

    public function contarSocios($id)
    {
        $totalSocios = Beneficiario::where('Id_Organizacion', $id)
                        ->where('Tipo_De_Socio', 'Socio')
                        ->count();

        return response()->json([
            'socios_no' => $totalSocios
        ]);
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        $data = $request->validate([
            'organizacion_id' => 'required|integer',

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
        $cajas = Organizacion::all();
        return view('ahorros.edit', compact('ahorro', 'cajas'));
    }

    // Actualizar un registro existente
    public function update(Request $request, $id)
    {
        $ahorro = Ahorro::findOrFail($id);

        $data = $request->validate([
            'organizacion_id' => 'required|integer',

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

    // Mostrar ficha individual
    public function ficha($id)
    {
        $ahorro = Ahorro::findOrFail($id);
        return view('ahorros.ficha', compact('ahorro'));
    }

    // Exportar todos los ahorros a PDF
    public function exportPdf()
    {
        $ahorros = Ahorro::all();
        $pdf = Pdf::loadView('ahorros.pdf', compact('ahorros'))->setPaper('landscape', 'letter');
        return $pdf->download('reporte_ahorros.pdf');
    }
}
