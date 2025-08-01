<?php

namespace App\Http\Controllers;

use App\Models\Emprendimiento;
use App\Models\Municipio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Departamento;
use App\Models\Aldea;
use App\Models\Organizacion; // <--- NUEVO

class EmprendimientoController extends Controller
{
  public function index(Request $request)
{
    $query = Emprendimiento::with(['municipio', 'tecnico', 'organizacion', 'aldea']); // <-- Agregamos aldea

    if ($request->filled('municipio')) {
        $query->where('Id_Municipio', $request->municipio);
    }

    if ($request->filled('fecha')) {
        $query->whereDate('Fecha_Levantamiento', $request->fecha);
    }

    if ($request->filled('tecnico')) {
        $query->whereHas('tecnico', function ($q) use ($request) {
            $q->where('Nombre_Usuario', 'like', '%' . $request->tecnico . '%');
        });
    }

    if ($request->filled('nombre')) {
        $query->where('Caja_Rural', 'like', '%' . $request->nombre . '%');
    }

    $emprendimientos = $query->orderBy('Fecha_Levantamiento', 'desc')
                             ->paginate(10)
                             ->appends($request->query());

    $municipios = Municipio::all();

    return view('emprendimientos.index', compact('emprendimientos', 'municipios'));
}

    public function create()
    {
        $departamentos = Departamento::all();
        $tecnicos = User::all();
      $organizaciones = Organizacion::where('Estado_Organizacion', 'ACTIVO')->get();

        return view('emprendimientos.create', compact('departamentos', 'tecnicos', 'organizaciones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Caja_Rural' => 'required|string|max:100',
            'Id_Municipio' => 'required|integer|exists:tbl_municipio,Id_Municipio',
            'aldea_id' => 'nullable|integer|exists:tbl_aldea,Id_Aldea',
            'Comunidad' => 'nullable|string|max:100',
            'Socios_Hombres' => 'nullable|integer|min:0',
            'Socios_Mujeres' => 'nullable|integer|min:0',
            'Tipo_Negocio' => 'required|string|max:255',
            'Ventas_Trimestrales' => 'nullable|numeric|min:0',
            'Empleos_Hombres' => 'nullable|integer|min:0',
            'Empleos_Mujeres' => 'nullable|integer|min:0',
            'Fecha_Levantamiento' => 'required|date',
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion', // <--- nuevo
        ]);

        $validated['Fecha_Inicio_Operaciones'] = now();
        $validated['Id_Tecnico'] = Auth::user()->Id_Usuario;
        $validated['Id_Aldea'] = $validated['aldea_id'] ?? null;
        unset($validated['aldea_id']);

        Emprendimiento::create($validated);

        return redirect()->route('emprendimientos.index')->with('success', 'Registro creado exitosamente');
    }

    public function edit(Emprendimiento $emprendimiento)
    {
        $departamentos = Departamento::all();
        $municipios = Municipio::all();
        $tecnicos = User::all();
      $organizaciones = Organizacion::where('Estado_Organizacion', 'ACTIVO')->get();

        return view('emprendimientos.edit', compact('emprendimiento', 'departamentos', 'municipios', 'tecnicos', 'organizaciones'));
    }

    public function update(Request $request, Emprendimiento $emprendimiento)
    {
        $validated = $request->validate([
            'Caja_Rural' => 'required|string|max:100',
            'Id_Municipio' => 'required|integer|exists:tbl_municipio,Id_Municipio',
            'aldea_id' => 'nullable|integer|exists:tbl_aldea,Id_Aldea',
            'Comunidad' => 'nullable|string|max:100',
            'Socios_Hombres' => 'nullable|integer|min:0',
            'Socios_Mujeres' => 'nullable|integer|min:0',
            'Tipo_Negocio' => 'required|string|max:255',
            'Ventas_Trimestrales' => 'nullable|numeric|min:0',
            'Empleos_Hombres' => 'nullable|integer|min:0',
            'Empleos_Mujeres' => 'nullable|integer|min:0',
            'Fecha_Levantamiento' => 'required|date',
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion', // <--- nuevo
        ]);

        $validated['Id_Aldea'] = $validated['aldea_id'] ?? null;
        unset($validated['aldea_id']);

        $emprendimiento->update($validated);

        return redirect()->route('emprendimientos.index')->with('success', 'Registro actualizado');
    }

    public function destroy(Emprendimiento $emprendimiento)
    {
        $emprendimiento->delete();
        return redirect()->route('emprendimientos.index')->with('success', 'Registro eliminado');
    }
}
