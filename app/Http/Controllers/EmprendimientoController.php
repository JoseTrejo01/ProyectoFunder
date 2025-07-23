<?php

namespace App\Http\Controllers;

use App\Models\Emprendimiento;
use App\Models\Municipio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Departamento;
class EmprendimientoController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Emprendimientos', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar emprendimientos.');
        }
        $objeto = \App\Models\Objeto::where('Objeto', 'Emprendimientos')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario ingresó a la gestión de emprendimientos'
            );
        }
        $query = Emprendimiento::with(['municipio', 'tecnico']);

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

        $emprendimientos = $query->orderBy('Fecha_Levantamiento', 'desc')->paginate(10)->appends($request->query());
        $municipios = Municipio::all();

        return view('emprendimientos.index', compact('emprendimientos', 'municipios'));
    }

    public function create()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Emprendimientos', 'Insercion')) {
            abort(403, 'No tienes permiso para crear emprendimientos.');
        }
        $departamentos = Departamento::all(); // nuevo
        $tecnicos = User::all();
        return view('emprendimientos.create', compact('departamentos', 'tecnicos'));
    }

    public function store(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Emprendimientos', 'Insercion')) {
            abort(403, 'No tienes permiso para crear emprendimientos.');
        }
        $validated = $request->validate([
            'Caja_Rural' => 'required|string|max:100',
            'Id_Municipio' => 'required|integer|exists:tbl_municipio,Id_Municipio',
            'aldea_id' => 'nullable|integer|exists:tbl_aldea,Id_Aldea', // <--- NUEVO
            'Comunidad' => 'nullable|string|max:100',
            'Socios_Hombres' => 'nullable|integer|min:0',
            'Socios_Mujeres' => 'nullable|integer|min:0',
            'Tipo_Negocio' => 'required|string|max:255',
            'Ventas_Trimestrales' => 'nullable|numeric|min:0',
            'Empleos_Hombres' => 'nullable|integer|min:0',
            'Empleos_Mujeres' => 'nullable|integer|min:0',
            'Fecha_Levantamiento' => 'required|date',
        ]);

        $validated['Fecha_Inicio_Operaciones'] = now();
        $validated['Id_Tecnico'] = Auth::user()->Id_Usuario;

        // Guardar con Id_Aldea si está presente
        $validated['Id_Aldea'] = $validated['aldea_id'] ?? null;
        unset($validated['aldea_id']); // Ya lo guardamos en el campo correcto

        Emprendimiento::create($validated);

        $objeto = \App\Models\Objeto::where('Objeto', 'Emprendimientos')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Creó un nuevo emprendimiento: ' . $validated['Caja_Rural']
            );
        }

        return redirect()->route('emprendimientos.index')->with('success', 'Registro creado exitosamente');
    }

    public function show(Emprendimiento $emprendimiento)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Emprendimientos', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar emprendimientos.');
        }
        return view('emprendimientos.show', compact('emprendimiento'));
    }

    public function edit(Emprendimiento $emprendimiento)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Emprendimientos', 'Actualizacion')) {
            abort(403, 'No tienes permiso para editar emprendimientos.');
        }
        $municipios = Municipio::all();
        $tecnicos = User::all();
        return view('emprendimientos.edit', compact('emprendimiento', 'municipios', 'tecnicos'));
    }

    public function update(Request $request, Emprendimiento $emprendimiento)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Emprendimientos', 'Actualizacion')) {
            abort(403, 'No tienes permiso para actualizar emprendimientos.');
        }
        $validated = $request->validate([
            'Caja_Rural' => 'required|string|max:100',
            'Id_Municipio' => 'required|integer|exists:tbl_municipio,Id_Municipio',
            'Comunidad' => 'nullable|string|max:100',
            'Socios_Hombres' => 'nullable|integer|min:0',
            'Socios_Mujeres' => 'nullable|integer|min:0',
            'Tipo_Negocio' => 'required|string|max:255',
            'Ventas_Trimestrales' => 'nullable|numeric|min:0',
            'Empleos_Hombres' => 'nullable|integer|min:0',
            'Empleos_Mujeres' => 'nullable|integer|min:0',
            'Fecha_Levantamiento' => 'required|date',
        ]);

        $emprendimiento->update($validated);

        $objeto = \App\Models\Objeto::where('Objeto', 'Emprendimientos')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Update',
                'Actualizó el emprendimiento: ' . $emprendimiento->Caja_Rural
            );
        }

        return redirect()->route('emprendimientos.index')->with('success', 'Registro actualizado');
    }

    public function destroy(Emprendimiento $emprendimiento)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Emprendimientos', 'Eliminacion')) {
            abort(403, 'No tienes permiso para eliminar emprendimientos.');
        }
        $emprendimiento->delete();

        $objeto = \App\Models\Objeto::where('Objeto', 'Emprendimientos')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Delete',
                'Eliminó el emprendimiento: ' . $emprendimiento->Caja_Rural
            );
        }

        return redirect()->route('emprendimientos.index')->with('success', 'Registro eliminado');
    }
}
