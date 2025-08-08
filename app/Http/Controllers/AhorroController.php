<?php

namespace App\Http\Controllers;

use App\Models\Ahorro;
use App\Models\Organizacion;
use App\Models\Beneficiario;
use App\Models\Objeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class AhorroController extends Controller
{
    /**
     * Vista principal de Ahorros: lista cajas y, si hay una seleccionada, sus ahorros.
     */
  public function index(Request $request)
{
    // Permisos
    if (!Auth::check() || !Auth::user()->tienePermiso('Ahorros', 'Consultar')) {
        return redirect()->back()->with('error', 'No tiene permisos para consultar ahorros');
    }

    // Bitácora
    $objeto = Objeto::where('Objeto', 'Ahorros')->first();
    if ($objeto && Auth::check()) {
        EVENT_BITACORA(
            Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Ingreso',
            'El usuario accedió a la gestión de ahorros'
        );
    }

    $cajas = Organizacion::all();
    $selectedCaja = $request->query('caja');

    $query = Ahorro::with(['beneficiario', 'organizacion'])->orderByDesc('Fecha');

    if ($selectedCaja) {
        $query->where('Id_Organizacion', $selectedCaja);
    }

    $ahorros = $query->paginate(10);

    return view('ahorros.index', compact('cajas', 'selectedCaja', 'ahorros'));
}


public function reportePDF()
{
    $ahorros = Ahorro::with('beneficiario', 'organizacion')->orderBy('Fecha', 'desc')->get();

     $pdf = Pdf::loadView('ahorros.reporte_pdf', [
    'ahorros' => $ahorros,
    'pdf' => true, 
])
        ->setPaper('A4', 'landscape');
    $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
    $pdf->getDomPDF()->set_option('isPhpEnabled', true);
    return $pdf->download('reporte_ahorros.pdf');
}

    

    /**
     * Formulario de creación.
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->tienePermiso('Ahorros', 'Insercion')) {
            return redirect()->back()->with('error', 'No tiene permisos para crear ahorros');
        }

        $cajas = Organizacion::all();
        return view('ahorros.create', compact('cajas'));
    }

    /**
     * Guardar un ahorro.
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->tienePermiso('Ahorros', 'Insercion')) {
            return redirect()->back()->with('error', 'No tiene permisos para crear ahorros');
        }

        $request->validate(
            [
                'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
                'Id_Beneficiario' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
                'Monto'          => 'required|numeric|min:0.01',
                'Fecha'          => 'required|date',
            ],
            [
                'Id_Organizacion.required' => 'Seleccione una organización.',
                'Id_Organizacion.exists'   => 'La organización no existe.',
                'Id_Beneficiario.required' => 'Seleccione un beneficiario.',
                'Id_Beneficiario.exists'   => 'El beneficiario no existe.',
                'Monto.required'           => 'El campo monto es obligatorio.',
                'Monto.numeric'            => 'El monto debe ser un número válido.',
                'Monto.min'                => 'El monto debe ser mayor a cero.',
                'Fecha.required'           => 'La fecha es obligatoria.',
                'Fecha.date'               => 'La fecha no tiene un formato válido.',
            ]
        );

        // Crear registro
        $ahorro = Ahorro::create([
            'Id_Organizacion' => $request->Id_Organizacion,
            'Id_Beneficiario' => $request->Id_Beneficiario,
            'Monto'           => $request->Monto,
            'Fecha'           => $request->Fecha,
        ]);

        // Bitácora
        $objeto = Objeto::where('Objeto', 'Ahorros')->first();
        if ($objeto && Auth::check()) {
            $beneficiario = Beneficiario::find($request->Id_Beneficiario);
            $nombre = $beneficiario?->Nombre_Beneficiario ?? 'N/D';
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                "Registró un ahorro de L.{$request->Monto} para {$nombre}"
            );
        }

        return redirect()
            ->route('ahorros.index', [])
            ->with('success', 'Ahorro registrado correctamente.');
    }

    /**
     * API: Obtener socios y clientes con total ahorrado por beneficiario en una caja.
     */
    public function obtenerSocios($id)
    {
        $baseQuery = fn ($tipo) => DB::table('tbl_beneficiario as b')
            ->leftJoin('tbl_ahorros as a', 'b.Id_Beneficiario', '=', 'a.Id_Beneficiario')
            ->select(
                'b.Id_Beneficiario',
                'b.Nombre_Beneficiario',
                DB::raw('COALESCE(SUM(a.Monto), 0) as Monto')
            )
            ->where('b.Id_Organizacion', $id)
            ->where('b.Tipo_De_Socio', $tipo)
            ->groupBy('b.Id_Beneficiario', 'b.Nombre_Beneficiario')
            ->orderBy('b.Nombre_Beneficiario');

        $socios = $baseQuery('Socio')->get()->map(function ($item) {
            $item->Tipo_De_Socio = 'Socio';
            return $item;
        });

        $clientes = $baseQuery('Cliente')->get()->map(function ($item) {
            $item->Tipo_De_Socio = 'Cliente';
            return $item;
        });

        return response()->json([
            'socios'   => $socios,
            'clientes' => $clientes,
        ]);
    }

    /**
     * API: Resumen de totales y promedios por tipo (socios/clientes) en una caja.
     */
    public function resumenCaja($id)
    {
        $sociosIds = Beneficiario::where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Socio')
            ->pluck('Id_Beneficiario');

        $clientesIds = Beneficiario::where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Cliente')
            ->pluck('Id_Beneficiario');

        $totalSocios   = Ahorro::whereIn('Id_Beneficiario', $sociosIds)->sum('Monto');
        $totalClientes = Ahorro::whereIn('Id_Beneficiario', $clientesIds)->sum('Monto');

        $cantidadSocios   = $sociosIds->count();
        $cantidadClientes = $clientesIds->count();

        $promedioSocios   = $cantidadSocios > 0 ? round($totalSocios / $cantidadSocios, 2) : 0;
        $promedioClientes = $cantidadClientes > 0 ? round($totalClientes / $cantidadClientes, 2) : 0;

        return response()->json([
            'cantidad_socios'          => $cantidadSocios,
            'total_ahorrado_socios'    => $totalSocios,
            'promedio_socios'          => $promedioSocios,
            'cantidad_clientes'        => $cantidadClientes,
            'total_ahorrado_clientes'  => $totalClientes,
            'promedio_clientes'        => $promedioClientes,
        ]);
    }

    /**
     * API: Listado de ahorros por caja (ordenado por fecha desc).
     */
    public function listado($id)
    {
        try {
            $ahorros = Ahorro::with('beneficiario')
                ->where('Id_Organizacion', $id)
                ->orderBy('Fecha', 'desc')
                ->get();

            return response()->json($ahorros);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
{
    if (!Auth::check() || !Auth::user()->tienePermiso('Ahorros', 'Actualizacion')) {
        return redirect()->back()->with('error', 'No tiene permisos para editar ahorros');
    }

    $ahorro = Ahorro::findOrFail($id);
    $cajas = Organizacion::all();
    $beneficiarios = Beneficiario::where('Id_Organizacion', $ahorro->Id_Organizacion)->get();

    return view('ahorros.edit', compact('ahorro', 'cajas', 'beneficiarios'));
}

/**
 * Actualizar un ahorro existente.
 */
public function update(Request $request, $id)
{
    if (!Auth::check() || !Auth::user()->tienePermiso('Ahorros', 'Actualizacion')) {
        return redirect()->back()->with('error', 'No tiene permisos para actualizar ahorros');
    }

    $ahorro = Ahorro::findOrFail($id);

    $request->validate(
        [
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'Id_Beneficiario' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'Monto'          => 'required|numeric|min:0.01',
            'Fecha'          => 'required|date',
        ],
        [
            'Id_Organizacion.required' => 'Seleccione una organización.',
            'Id_Organizacion.exists'   => 'La organización no existe.',
            'Id_Beneficiario.required' => 'Seleccione un beneficiario.',
            'Id_Beneficiario.exists'   => 'El beneficiario no existe.',
            'Monto.required'           => 'El campo monto es obligatorio.',
            'Monto.numeric'            => 'El monto debe ser un número válido.',
            'Monto.min'                => 'El monto debe ser mayor a cero.',
            'Fecha.required'           => 'La fecha es obligatoria.',
            'Fecha.date'               => 'La fecha no tiene un formato válido.',
        ]
    );

    $ahorro->update([
        'Id_Organizacion' => $request->Id_Organizacion,
        'Id_Beneficiario' => $request->Id_Beneficiario,
        'Monto'           => $request->Monto,
        'Fecha'           => $request->Fecha,
    ]);

    // Bitácora
    $objeto = Objeto::where('Objeto', 'Ahorros')->first();
    if ($objeto && Auth::check()) {
        $beneficiario = Beneficiario::find($request->Id_Beneficiario);
        $nombre = $beneficiario?->Nombre_Beneficiario ?? 'N/D';
        EVENT_BITACORA(
            Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Edición',
            "Actualizó un ahorro (ID: {$ahorro->id}) a L.{$request->Monto} para {$nombre}"
        );
    }

    return redirect()
        ->route('ahorros.index', [])
        ->with('success', 'Ahorro actualizado correctamente.');
}

/**
 * Eliminar un ahorro existente.
 */
public function destroy($id)
{
    if (!Auth::check() || !Auth::user()->tienePermiso('Ahorros', 'Eliminacion')) {
        return redirect()->back()->with('error', 'No tiene permisos para eliminar ahorros');
    }

    $ahorro = Ahorro::findOrFail($id);

    // Guardar datos para bitácora antes de eliminar
    $objeto = Objeto::where('Objeto', 'Ahorros')->first();
    $beneficiario = Beneficiario::find($ahorro->Id_Beneficiario);
    $nombre = $beneficiario?->Nombre_Beneficiario ?? 'N/D';
    $monto = $ahorro->Monto;

    $ahorro->delete();

    if ($objeto && Auth::check()) {
        EVENT_BITACORA(
            Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Eliminación',
            "Eliminó un ahorro de L.{$monto} para {$nombre}"
        );
    }

    return redirect()
        ->route('ahorros.index', [])
        ->with('success', 'Ahorro eliminado correctamente.');
}

}
