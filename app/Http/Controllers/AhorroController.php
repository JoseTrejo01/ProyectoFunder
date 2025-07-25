<?php

namespace App\Http\Controllers;

use App\Models\Ahorro;
use App\Models\Organizacion;
use App\Models\Socio;
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
        $num_socios = DB::table('tbl_beneficiario')
            ->where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Socio')
            ->count();

        $total_ahorros = DB::table('tbl_ahorros')
            ->where('Id_Organizacion', $id)
            ->sum('monto');

        $promedio_ahorros = $num_socios > 0 ? $total_ahorros / $num_socios : 0;

        return response()->json([
            'num_socios' => $num_socios,
            'total_ahorros' => $total_ahorros,
            'promedio_ahorros' => $promedio_ahorros,
        ]);
    }

    // Mostrar formulario para crear nuevo ahorro
    public function create()
    {
        $organizaciones = Organizacion::all();
        return view('ahorros.create', compact('organizaciones'));
    }

    // Guardar nuevo ahorro
    public function store(Request $request)
    {
        $request->validate([
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'beneficiario_id' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);

        Ahorro::create([
            'Id_Organizacion' => $request->Id_Organizacion,
            'beneficiario_id' => $request->beneficiario_id,
            'monto' => $request->monto,
            'fecha' => $request->fecha,
        ]);

        return redirect()->route('ahorros.create')->with('success', 'Ahorro registrado correctamente.');
    }

    // Obtener socios (beneficiarios) por caja rural
    public function sociosPorCaja($id)
    {
        $socios = DB::table('tbl_beneficiario')
            ->where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Socio')
            ->get(['Id_Beneficiario', 'Nombre']);

        return response()->json($socios);
    }

    // Mostrar formulario para editar ahorro
    public function edit($id)
    {
        $ahorro = Ahorro::findOrFail($id);
        $organizaciones = Organizacion::all();
        return view('ahorros.edit', compact('ahorro', 'organizaciones'));
    }

    // Actualizar un ahorro existente
    public function update(Request $request, $id)
    {
        $ahorro = Ahorro::findOrFail($id);

        $request->validate([
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'beneficiario_id' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);

        $ahorro->update([
            'Id_Organizacion' => $request->Id_Organizacion,
            'beneficiario_id' => $request->beneficiario_id,
            'monto' => $request->monto,
            'fecha' => $request->fecha,
        ]);

        return redirect()->route('ahorros.index')->with('success', 'Ahorro actualizado correctamente.');
    }

    // Eliminar un ahorro
    public function destroy($id)
    {
        $ahorro = Ahorro::findOrFail($id);
        $ahorro->delete();

        return redirect()->route('ahorros.index')->with('success', 'Ahorro eliminado correctamente.');
    }

    // Mostrar ficha individual del ahorro
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
