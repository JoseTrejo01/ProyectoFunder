<?php

namespace App\Http\Controllers;

use App\Models\Ahorro;
use App\Models\Organizacion;
use App\Models\Beneficiario;
use App\Models\Objeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AhorroController extends Controller
{
    // Mostrar vista principal con cajas rurales y ahorros si hay caja seleccionada
    public function index(Request $request)
    {
        // Verificar permisos de acceso
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Consultar')) {
            return redirect()->back()->with('error', 'No tiene permisos para consultar ahorros');
        }

        // Registrar acceso a gestión de ahorros en bitácora
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
        $ahorros = [];

        if ($selectedCaja) {
            $ahorros = Ahorro::with('beneficiario')
                ->where('Id_Organizacion', $selectedCaja)
                ->get();
        }

        return view('ahorros.index', compact('cajas', 'selectedCaja', 'ahorros'));
    }

    // Mostrar formulario para crear un nuevo ahorro
    public function create()
    {
        // Verificar permisos para crear ahorros
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Insercion')) {
            return redirect()->back()->with('error', 'No tiene permisos para crear ahorros');
        }

        $cajas = Organizacion::all();
        return view('ahorros.create', compact('cajas'));
    }

    // Guardar nuevo ahorro en la base de datos
    public function store(Request $request)
    {
        // Verificar permisos para crear ahorros
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Insercion')) {
            return redirect()->back()->with('error', 'No tiene permisos para crear ahorros');
        }
        $request->validate([
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'Id_Beneficiario' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'Monto' => 'required|numeric|min:0.01',
            'Fecha' => 'required|date',
        ]);

        $ahorro = Ahorro::create([
            'Id_Organizacion' => $request->Id_Organizacion,
            'Id_Beneficiario' => $request->Id_Beneficiario,
            'Monto' => $request->Monto,
            'Fecha' => $request->Fecha,
        ]);

        // Registrar creación de ahorro en bitácora
        $objeto = Objeto::where('Objeto', 'Ahorros')->first();
        if ($objeto && Auth::check()) {
            $beneficiario = Beneficiario::find($request->Id_Beneficiario);
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                "Registró un ahorro de L.{$request->Monto} para {$beneficiario->Nombre_Beneficiario}"
            );
        }

        return redirect()->route('ahorros.index', ['caja' => $request->Id_Organizacion])
                         ->with('success', 'Ahorro registrado correctamente.');
    }

    // API: Obtener socios y no socios con sus totales de ahorro para una caja rural
    public function obtenerSocios($id)
    {
        $socios = DB::table('tbl_beneficiario as b')
            ->leftJoin('tbl_ahorros as a', 'b.Id_Beneficiario', '=', 'a.Id_Beneficiario')
            ->select('b.Id_Beneficiario', 'b.Nombre_Beneficiario', DB::raw('COALESCE(SUM(a.Monto), 0) as Monto'))
            ->where('b.Id_Organizacion', $id)
            ->where('b.Tipo_De_Socio', 'Socio')
            ->groupBy('b.Id_Beneficiario', 'b.Nombre_Beneficiario')
            ->orderBy('b.Nombre_Beneficiario')
            ->get();

        $noSocios = DB::table('tbl_beneficiario as b')
            ->leftJoin('tbl_ahorros as a', 'b.Id_Beneficiario', '=', 'a.Id_Beneficiario')
            ->select('b.Id_Beneficiario', 'b.Nombre_Beneficiario', DB::raw('COALESCE(SUM(a.Monto), 0) as Monto'))
            ->where('b.Id_Organizacion', $id)
            ->where('b.Tipo_De_Socio', 'No Socio')
            ->groupBy('b.Id_Beneficiario', 'b.Nombre_Beneficiario')
            ->orderBy('b.Nombre_Beneficiario')
            ->get();

        return response()->json([
            'socios' => $socios,
            'no_socios' => $noSocios,
        ]);
    }

    // API: Retornar resumen de ahorros por caja rural (totales)
    public function resumenCaja($id)
    {
        $sociosIds = Beneficiario::where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Socio')
            ->pluck('Id_Beneficiario');

        $total = Ahorro::whereIn('Id_Beneficiario', $sociosIds)->sum('Monto');
        $cantidad = $sociosIds->count();
        $promedio = $cantidad > 0 ? $total / $cantidad : 0;

        return response()->json([
            'cantidad_socios' => $cantidad,
            'total_ahorrado' => $total,
            'promedio' => $promedio,
        ]);
    }
public function listado($id)
{
    $ahorros = Ahorro::with('beneficiario')
        ->where('Id_Organizacion', $id)
        ->orderBy('Fecha', 'desc')
        ->get();

    return response()->json($ahorros);
}

}
