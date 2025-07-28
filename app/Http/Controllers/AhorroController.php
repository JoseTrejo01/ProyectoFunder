<<<<<<< HEAD
<?php

namespace App\Http\Controllers;

use App\Models\Ahorro;
use App\Models\Organizacion;
use App\Models\Beneficiario;
use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AhorroController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar ahorros.');
        }

        $objeto = \App\Models\Objeto::where('Objeto', 'Ahorros')->first();
        if ($objeto) {
            EVENT_BITACORA(auth()->user()->Id_Usuario, $objeto->Id_Objeto, 'Ingreso', 'El usuario ingresó a la gestión de ahorros');
        }

        $ahorros = Ahorro::with(['organizacion', 'beneficiario'])->get();
        $agrupados = [];

        foreach ($ahorros as $ahorro) {
            $org = $ahorro->organizacion->Nombre_Organizacion ?? 'Sin organización';
            $tipo = strtolower($ahorro->beneficiario->Tipo_De_Socio ?? 'no socio');
            $edad = $ahorro->beneficiario->edad ?? 0;

            if (!isset($agrupados[$org])) {
                $agrupados[$org] = [
                    'socios' => ['cantidad' => 0, 'total' => 0],
                    'no_socios_adultos' => ['cantidad' => 0, 'total' => 0],
                    'no_socios_jovenes' => ['cantidad' => 0, 'total' => 0],
                ];
            }

            if ($tipo === 'socio') {
                $agrupados[$org]['socios']['cantidad']++;
                $agrupados[$org]['socios']['total'] += $ahorro->monto_ahorrado;
            } else {
                if ($edad >= 30) {
                    $agrupados[$org]['no_socios_adultos']['cantidad']++;
                    $agrupados[$org]['no_socios_adultos']['total'] += $ahorro->monto_ahorrado;
                } else {
                    $agrupados[$org]['no_socios_jovenes']['cantidad']++;
                    $agrupados[$org]['no_socios_jovenes']['total'] += $ahorro->monto_ahorrado;
                }
            }
        }

        foreach ($agrupados as &$datos) {
            foreach ($datos as &$grupo) {
                $grupo['promedio'] = $grupo['cantidad'] > 0 ? $grupo['total'] / $grupo['cantidad'] : 0;
            }
        }

        return view('ahorros.index', compact('agrupados'));
    }

    public function create()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Insercion')) {
            abort(403, 'No tienes permiso para crear ahorros.');
        }

        $organizaciones = Organizacion::all();
        $socios = Socio::all();
        return view('ahorros.create', compact('organizaciones', 'socios'));
    }

    public function store(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Insercion')) {
            abort(403, 'No tienes permiso para crear ahorros.');
        }

        $data = $request->validate([
            'id_organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'id_beneficiario' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'monto_ahorrado' => 'required|numeric|min:0',
        ]);

        Ahorro::create($data);

        EVENT_BITACORA(auth()->user()->Id_Usuario, \App\Models\Objeto::where('Objeto', 'Ahorros')->value('Id_Objeto'), 'Nuevo', 'Creó un nuevo ahorro');

        return redirect()->route('ahorros.index')->with('success', 'Ahorro registrado correctamente.');
    }

    public function listarPorCaja($id)
    {
        $ahorros = Ahorro::with(['organizacion', 'beneficiario'])
            ->where('Id_Organizacion', $id)
            ->get();

        return response()->json($ahorros);
    }

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
            ->where('b.Tipo_De_Socio', 'Cliente')
            ->groupBy('b.Id_Beneficiario', 'b.Nombre_Beneficiario')
            ->orderBy('b.Nombre_Beneficiario')
            ->get();

        return response()->json([
            'socios' => $socios,
            'no_socios' => $noSocios,
        ]);
    }

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

    public function ficha($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar ahorros.');
        }

        $ahorro = Ahorro::with(['organizacion', 'beneficiario'])->findOrFail($id);
        return view('ahorros.ficha', compact('ahorro'));
    }

    public function exportPdf()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar ahorros.');
        }

        $ahorros = Ahorro::with(['organizacion', 'beneficiario'])->get();
        // El mismo agrupamiento y cálculo del método index()
        // ...

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('ahorros.pdf', ['agrupados' => $agrupados]);
        return $pdf->download('reporte_ahorros.pdf');
    }
}
=======
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
