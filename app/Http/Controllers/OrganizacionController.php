<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organizacion;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Aldea;

class OrganizacionController extends Controller
{
    public function index()
    {
        $organizaciones = Organizacion::with(['aldea.municipio.departamento'])->get();
        $departamentos = Departamento::all();
        $municipios = Municipio::all();
        // Agrupar municipios por departamento para el JS
        $municipiosPorDepto = [];
        foreach ($municipios as $muni) {
            $municipiosPorDepto[$muni->Id_Departamento][] = $muni;
        }
        return view('organizaciones.index', compact('organizaciones', 'departamentos', 'municipiosPorDepto'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre_Organizacion' => 'required|string|max:100',
            'departamento' => 'required|exists:tbl_departamento,Id_Departamento',
            'municipio' => 'required|exists:tbl_municipio,Id_Municipio',
            'Nombre_Aldea' => 'required|string|max:60',
        ]);
        // Crear la aldea si no existe
        $aldea = Aldea::firstOrCreate([
            'Nombre_Aldea' => $request->Nombre_Aldea,
            'Id_Municipio' => $request->municipio,
        ]);
        // Crear la organización
        Organizacion::create([
            'Id_Aldea' => $aldea->Id_Aldea,
            'Nombre_Organizacion' => $request->Nombre_Organizacion,
            'Estado_Organizacion' => 'ACTIVO',
            'Id_Usuario' => auth()->id() ?? 1,
        ]);
        return redirect()->route('organizaciones.index')->with('success', 'Organización registrada correctamente');
    }

        // Mostrar formulario de edición de organización
    public function edit($id)
    {
        $organizacion = \App\Models\Organizacion::findOrFail($id);
        $departamentos = \App\Models\Departamento::all();
        $municipiosPorDepto = \App\Models\Municipio::all()->groupBy('Id_Departamento');
        return view('organizaciones.edit', compact('organizacion', 'departamentos', 'municipiosPorDepto'));
    }
        // Inactivar organización
    public function destroy($id)
    {
        $org = Organizacion::findOrFail($id);
        $org->Estado_Organizacion = 'INACTIVO';
        $org->save();
        return redirect()->route('organizaciones.index')->with('success', 'Organización inactivada correctamente');
    }

    // Actualizar organización
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

        // Actualizar o crear aldea
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
}
