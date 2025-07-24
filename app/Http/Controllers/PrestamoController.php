<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organizacion;
use App\Models\Prestamo;
use App\Models\Pago;
use App\Models\Beneficiario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
 public function create()
{
    $organizaciones = Organizacion::all();
     $beneficiarios = Beneficiario::select('id_Beneficiario', 'Nombre_Beneficiario', 'actividad_economica')->get();

    // Define las opciones para el porcentaje de mora (puedes modificar valores)
    $porcentajesMora = [
        '0' => '0%',
        '1' => '1%',
        '2' => '2%',
        '3' => '3%',
        '4' => '4%',
        '5' => '5%',
        '6' => '6%',
        '7' => '7%',
        '8' => '8%',
        '9' => '9%',
        '10' => '10%',
        '15' => '15%',
        '20' => '20%',
        '25' => '25%',
    ];

    return view('prestamos.crear', compact('organizaciones', 'beneficiarios', 'porcentajesMora'));
}

    public function index()
    {
        $prestamos = Prestamo::with('organizacion')->get();

        $prestamosPorMes = Prestamo::select(
            DB::raw('YEAR(fecha_solicitud) as anio'),
            DB::raw('MONTH(fecha_solicitud) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('anio', 'mes')
        ->orderBy('anio', 'asc')
        ->orderBy('mes', 'asc')
        ->get();

        $totalDesembolsado = Prestamo::where('estado', 'desembolsado')->sum('monto_solicitado');
        $total = Prestamo::count();
        $totalPrestamos = Prestamo::count();
        $aprobados = Prestamo::where('estado', 'aprobado')->count();
        $porcentajeAprobado = $totalPrestamos > 0 ? round(($aprobados / $totalPrestamos) * 100, 2) : 0;

        $topOrganizaciones = Prestamo::select('socio_id', DB::raw('COUNT(*) as total'))
            ->groupBy('socio_id')
            ->orderBy('total', 'desc')
            ->with('organizacion')
            ->take(5)
            ->get();

        return view('prestamos.index', compact(
            'prestamos',
            'prestamosPorMes',
            'totalDesembolsado',
            'porcentajeAprobado',
            'aprobados',
            'total',
            'topOrganizaciones'
        ));
    }

    public function reportes()
    {
        // Si usas esta función para otra vista independiente, puedes definirlo igual:
        $prestamosPorMes = Prestamo::select(
            DB::raw('YEAR(fecha_solicitud) as anio'),
            DB::raw('MONTH(fecha_solicitud) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('anio', 'mes')
        ->orderBy('anio', 'asc')
        ->orderBy('mes', 'asc')
        ->get();

        $totalDesembolsado = Prestamo::where('estado', 'desembolsado')->sum('monto_solicitado');
        $total = Prestamo::count();
        $totalPrestamos = Prestamo::count();
        $aprobados = Prestamo::where('estado', 'aprobado')->count();
        $porcentajeAprobado = $totalPrestamos > 0 ? round(($aprobados / $totalPrestamos) * 100, 2) : 0;

        $topOrganizaciones = Prestamo::select('socio_id', DB::raw('COUNT(*) as total'))
            ->groupBy('socio_id')
            ->orderBy('total', 'desc')
            ->with('organizacion')
            ->take(5)
            ->get();

        return view('prestamos.reportes', compact(
            'prestamosPorMes',
            'totalDesembolsado',
            'porcentajeAprobado',
            'aprobados',
            'total',
            'topOrganizaciones'
        ));
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

        $prestamo->estado = 'desembolsado';
        $prestamo->save();

        Pago::create([
            'prestamo_id' => $prestamo->id,
            'fecha_pago' => now()->toDateString(),
            'monto_pagado' => $prestamo->monto_solicitado,
            'observaciones' => 'Desembolso inicial del préstamo',
        ]);

        return redirect()->route('creditos')->with('success', 'Préstamo desembolsado y pago inicial registrado.');
    }
public function store(Request $request)
{

    $validated = $request->validate([
        'socio_id' => 'required|exists:tbl_organizacion,Id_Organizacion',
       'beneficiario_id' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
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

    // Cálculo de puntaje automático
    $puntaje = 0;
    if ($request->porcentaje_mora_caja < 5) $puntaje += 30;
    if ($request->capital_social > 50000) $puntaje += 20;
    if ($request->capital_trabajo > 30000) $puntaje += 15;
    if ($request->reservas > 10000) $puntaje += 10;
    if ($request->intereses_cobrados > 5000) $puntaje += 5;

    // Guardar el préstamo
    $prestamo = Prestamo::create([
        'socio_id' => $request->socio_id,
        'beneficiario_id' => $request->beneficiario_id, // ✅ Aquí se guarda el campo que faltaba
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

    // Generar pagos automáticos
    $montoPorCuota = $request->monto_solicitado / $request->plazo_meses;
    $fechaInicio = \Carbon\Carbon::parse($request->fecha_solicitud);

    for ($i = 1; $i <= $request->plazo_meses; $i++) {
        DB::table('tbl_pagos')->insert([
            'prestamo_id' => $prestamo->id,
            'fecha_pago' => $fechaInicio->copy()->addMonths($i)->toDateString(),
            'monto_pagado' => $montoPorCuota,
            'estado' => 'pendiente',
            'observaciones' => 'Pago automático generado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('creditos')->with('success', 'Solicitud registrada y pagos generados automáticamente.');
}

}
