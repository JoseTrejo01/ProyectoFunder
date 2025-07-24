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
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar ahorros.');
        }

        $objeto = \App\Models\Objeto::where('Objeto', 'Ahorros')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario ingresó a la gestión de ahorros'
            );
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

        // Calcular promedios
        foreach ($agrupados as &$datos) {
            foreach ($datos as &$grupo) {
                $grupo['promedio'] = $grupo['cantidad'] > 0 ? $grupo['total'] / $grupo['cantidad'] : 0;
            }
        }

        return view('ahorros.index', compact('agrupados'));
    }

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
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'beneficiario_id' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);

        $ahorro = Ahorro::create($data);

        $objeto = \App\Models\Objeto::where('Objeto', 'Ahorros')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Creó un nuevo ahorro'
            );
        }

        return redirect()->route('ahorros.index')->with('success', 'Ahorro registrado correctamente.');
    }

    public function edit($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Actualizacion')) {
            abort(403, 'No tienes permiso para editar ahorros.');
        }

        $ahorro = Ahorro::findOrFail($id);
        $organizaciones = Organizacion::all();
        $socios = Socio::all();

        return view('ahorros.edit', compact('ahorro', 'organizaciones', 'socios'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Actualizacion')) {
            abort(403, 'No tienes permiso para actualizar ahorros.');
        }

        $ahorro = Ahorro::findOrFail($id);

        $data = $request->validate([
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'beneficiario_id' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);

        $ahorro->update($data);

        $objeto = \App\Models\Objeto::where('Objeto', 'Ahorros')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Update',
                'Actualizó el ahorro con ID: ' . $ahorro->id
            );
        }

        return redirect()->route('ahorros.index')->with('success', 'Ahorro actualizado correctamente.');
    }

    public function destroy($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Eliminacion')) {
            abort(403, 'No tienes permiso para eliminar ahorros.');
        }

        $ahorro = Ahorro::findOrFail($id);
        $ahorro->delete();

        $objeto = \App\Models\Objeto::where('Objeto', 'Ahorros')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Delete',
                'Eliminó el ahorro con ID: ' . $ahorro->id
            );
        }

        return redirect()->route('ahorros.index')->with('success', 'Ahorro eliminado correctamente.');
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

        // Calcular promedios
        foreach ($agrupados as &$datos) {
            foreach ($datos as &$grupo) {
                $grupo['promedio'] = $grupo['cantidad'] > 0 ? $grupo['total'] / $grupo['cantidad'] : 0;
            }
        }

        $pdf = Pdf::loadView('ahorros.pdf', ['agrupados' => $agrupados]);
        return $pdf->download('reporte_ahorros.pdf');
    }

    public function show($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar ahorros.');
        }

        $ahorro = Ahorro::with(['organizacion', 'beneficiario'])->findOrFail($id);
        return view('ahorros.show', compact('ahorro'));
    }

    public function sociosPorCaja($id)
    {
        $socios = DB::table('tbl_beneficiario')
            ->where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Socio')
            ->get(['Id_Beneficiario', 'Nombre']);

        return response()->json($socios);
    }
}
