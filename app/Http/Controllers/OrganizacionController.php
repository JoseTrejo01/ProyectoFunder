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
        $organizaciones = Organizacion::with(['aldea.municipio.departamento'])->get();
        $departamentos = Departamento::all();
        $municipios = Municipio::all();

        // Agrupar municipios por departamento para JS
        $municipiosPorDepto = [];
        foreach ($municipios as $muni) {
            $municipiosPorDepto[$muni->Id_Departamento][] = $muni;
        }

        // Coordenadas por municipio
        $coordenadas = DB::table('tbl_coordenadas_municipio')
            ->select('Id_Municipio', 'coordenada_x', 'coordenada_y')
            ->get()
            ->keyBy('Id_Municipio');

        return view('organizaciones.index', compact('organizaciones', 'departamentos', 'municipiosPorDepto', 'coordenadas'));
    }

    public function store(Request $request)
    {
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

        Organizacion::create([
            'Id_Aldea' => $aldea->Id_Aldea,
            'Nombre_Organizacion' => $request->Nombre_Organizacion,
            'Estado_Organizacion' => 'ACTIVO',
            'Id_Usuario' => auth()->id() ?? 1,
        ]);

        // Registrar coordenadas si no existen
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
        $organizacion = Organizacion::findOrFail($id);
        $departamentos = Departamento::all();
        $municipiosPorDepto = Municipio::all()->groupBy('Id_Departamento');
        return view('organizaciones.edit', compact('organizacion', 'departamentos', 'municipiosPorDepto'));
    }

    public function destroy($id)
    {
        $org = Organizacion::findOrFail($id);
        $org->Estado_Organizacion = 'INACTIVO';
        $org->save();
        return redirect()->route('organizaciones.index')->with('success', 'Organización inactivada correctamente');
    }

    public function update(Request $request, $id)
    {
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

        return redirect()->route('organizaciones.index')->with('success', 'Organización actualizada correctamente');
    }
    public function vistaMapa()
{
    $organizaciones = Organizacion::with([
        'aldea.municipio.coordenada',
        'aldea.municipio.departamento'
    ])->get();

    $departamentos = Departamento::all();
    $municipios = Municipio::all();

    // Agrupar municipios por departamento
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
}
