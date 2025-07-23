<?php
namespace App\Http\Controllers;

use App\Models\Ahorro;
use App\Models\Organizacion;
use App\Models\Socio;
use Illuminate\Http\Request;

class AhorroController extends Controller
{

    // Mostrar lista paginada de ahorros
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
        foreach ($agrupados as $org => &$datos) {
            foreach ($datos as $key => &$grupo) {
                $grupo['promedio'] = $grupo['cantidad'] > 0 ? $grupo['total'] / $grupo['cantidad'] : 0;
            }
        }
        unset($grupo, $datos);
        return view('ahorros.index', compact('agrupados'));
    }

    // Mostrar formulario para crear nuevo registro
    public function create()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Insercion')) {
            abort(403, 'No tienes permiso para crear ahorros.');
        }
        $organizaciones = Organizacion::all();
        $socios = Socio::all();
        return view('ahorros.create', compact('organizaciones', 'socios'));
    }

    // Guardar nuevo registro
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


    // Mostrar formulario para editar
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

    // Actualizar un registro existente
    public function update(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Actualizacion')) {
            abort(403, 'No tienes permiso para actualizar ahorros.');
        }
        $ahorro = Ahorro::findOrFail($id);

        $data = $request->validate([
            'id_organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'id_beneficiario' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'monto_ahorrado' => 'required|numeric|min:0',
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

    // Eliminar un registro
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

    // Mostrar ficha individual
    public function ficha($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar ahorros.');
        }
        $ahorro = Ahorro::with(['organizacion', 'beneficiario'])->findOrFail($id);
        return view('ahorros.ficha', compact('ahorro'));
    }

    // ...existing code...

    // Exportar reporte PDF agrupado y clasificado
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
        foreach ($agrupados as $org => &$datos) {
            foreach ($datos as $key => &$grupo) {
                $grupo['promedio'] = $grupo['cantidad'] > 0 ? $grupo['total'] / $grupo['cantidad'] : 0;
            }
        }
        unset($grupo, $datos);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('ahorros.pdf', ['agrupados' => $agrupados]);
        return $pdf->download('reporte_ahorros.pdf');
    }

        // Mostrar un ahorro individual (show)
    public function show($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Ahorros', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar ahorros.');
        }
        $ahorro = Ahorro::with(['organizacion', 'beneficiario'])->findOrFail($id);
        return view('ahorros.show', compact('ahorro'));
    }
}
