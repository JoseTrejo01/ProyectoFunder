<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Organizacion;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Aldea;
use App\Models\CoordenadaMunicipio;

class OrganizacionController extends Controller
{
    public function index()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar organizaciones.');
        }

        $objeto = \App\Models\Objeto::where('Objeto', 'Organizaciones')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(auth()->user()->Id_Usuario, $objeto->Id_Objeto, 'Ingreso', 'El usuario ingresó a la gestión de organizaciones');
        }

        $user = auth()->user();
        $rol = $user->rol->Rol ?? null;
        $organizaciones = ($rol === 'TECNICO DE CAMPO')
            ? Organizacion::where('Estado_Organizacion', 'ACTIVO')->with(['aldea.municipio.departamento'])->get()
            : Organizacion::with(['aldea.municipio.departamento'])->get();

        $departamentos = Departamento::all();
        $municipios = Municipio::all();
        $municipiosPorDepto = $municipios->groupBy('Id_Departamento');

        $coordenadas = DB::table('tbl_coordenadas_municipio')
            ->select('Id_Municipio', 'coordenada_x', 'coordenada_y')
            ->get()
            ->keyBy('Id_Municipio');

        return view('organizaciones.index', compact('organizaciones', 'departamentos', 'municipiosPorDepto', 'coordenadas'));
    }

    public function store(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organización', 'Insercion')) {
            abort(403, 'No tienes permiso para crear organizaciones.');
        }

        $request->validate([
            'Nombre_Organizacion' => 'required|string|max:100',
            'departamento' => 'required|exists:tbl_departamento,Id_Departamento',
            'municipio' => 'required|exists:tbl_municipio,Id_Municipio',
            'Nombre_Aldea' => 'required|string|max:60',
            'coordenada_x' => 'required|numeric',
            'coordenada_y' => 'required|numeric',
        ]);

        $aldea = Aldea::firstOrCreate([
            'Nombre_Aldea' => $request->Nombre_Aldea,
            'Id_Municipio' => $request->municipio,
        ]);

        $org = Organizacion::create([
            'Id_Aldea' => $aldea->Id_Aldea,
            'Nombre_Organizacion' => $request->Nombre_Organizacion,
            'Estado_Organizacion' => 'ACTIVO',
            'Id_Usuario' => auth()->id() ?? 1,
        ]);

        DB::table('tbl_coordenadas_municipio')->updateOrInsert(
            [
                'Id_Municipio' => $request->municipio,
                'Id_Departamento' => $request->departamento
            ],
            [
                'coordenada_x' => $request->coordenada_x,
                'coordenada_y' => $request->coordenada_y
            ]
        );

        return redirect()->route('organizaciones.index')->with('success', 'Organización registrada correctamente');
    }

    public function edit($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organización', 'Actualizacion')) {
            abort(403, 'No tienes permiso para editar organizaciones.');
        }

        $organizacion = Organizacion::findOrFail($id);
        $departamentos = Departamento::all();
        $municipiosPorDepto = Municipio::all()->groupBy('Id_Departamento');

        return view('organizaciones.edit', compact('organizacion', 'departamentos', 'municipiosPorDepto'));
    }

    public function destroy($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organización', 'Eliminacion')) {
            abort(403, 'No tienes permiso para eliminar organizaciones.');
        }

        $org = Organizacion::findOrFail($id);
        $org->Estado_Organizacion = 'INACTIVO';
        $org->save();

        $objeto = \App\Models\Objeto::where('Objeto', 'Organizaciones')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(auth()->user()->Id_Usuario, $objeto->Id_Objeto, 'Delete', 'Inactivó la organización: ' . $org->Nombre_Organizacion);
        }

        return redirect()->route('organizaciones.index')->with('success', 'Organización inactivada correctamente');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organización', 'Actualizacion')) {
            abort(403, 'No tienes permiso para actualizar organizaciones.');
        }

        $request->validate([
            'Nombre_Organizacion' => 'required|string|max:100',
            'departamento' => 'required|exists:tbl_departamento,Id_Departamento',
            'municipio' => 'required|exists:tbl_municipio,Id_Municipio',
            'Nombre_Aldea' => 'required|string|max:60',
            'Estado_Organizacion' => 'required|in:ACTIVO,INACTIVO',
        ]);

        $org = Organizacion::findOrFail($id);

        $aldea = Aldea::firstOrCreate([
            'Nombre_Aldea' => $request->Nombre_Aldea,
            'Id_Municipio' => $request->municipio,
        ]);

        $org->Id_Aldea = $aldea->Id_Aldea;
        $org->Nombre_Organizacion = $request->Nombre_Organizacion;
        $org->Estado_Organizacion = $request->Estado_Organizacion;
        $org->save();

        $objeto = \App\Models\Objeto::where('Objeto', 'Organizaciones')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(auth()->user()->Id_Usuario, $objeto->Id_Objeto, 'Update', 'Actualizó la organización: ' . $org->Nombre_Organizacion);
        }

        return redirect()->route('organizaciones.index')->with('success', 'Organización actualizada correctamente');
    }

    public function vistaMapa()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar organizaciones.');
        }

        $organizaciones = Organizacion::with([
            'aldea.municipio.coordenada',
            'aldea.municipio.departamento'
        ])->withCount('socios')->get();

        $departamentos = Departamento::all();
        $municipios = Municipio::all();

        $municipiosPorDepto = $municipios->groupBy('Id_Departamento')->map(function ($group) {
            return $group->map(function ($muni) {
                return [
                    'Id_Municipio' => $muni->Id_Municipio,
                    'Nombre_Municipio' => $muni->Nombre_Municipio
                ];
            })->values();
        });

        return view('organizaciones.mapa', compact('organizaciones', 'departamentos', 'municipios', 'municipiosPorDepto'));
    }

    public function obtenerCajasConSocios()
    {
        $cajas = Organizacion::withCount('socios')
            ->with(['aldea.municipio.departamento'])
            ->get()
            ->map(function ($caja) {
                return [
                    'nombre' => $caja->Nombre_Organizacion,
                    'aldea' => optional($caja->aldea)->Nombre_Aldea,
                    'municipio' => optional($caja->aldea->municipio)->Nombre_Municipio ?? '',
                    'departamento' => optional($caja->aldea->municipio->departamento)->Nombre_Departamento ?? '',
                    'estado' => $caja->Estado_Organizacion,
                    'lat' => optional(optional($caja->aldea)->municipio)->coordenada->coordenada_y,
                    'lng' => optional(optional($caja->aldea)->municipio)->coordenada->coordenada_x,
                    'total_socios' => $caja->socios_count,
                ];
            });

        return response()->json($cajas);
    }
}
