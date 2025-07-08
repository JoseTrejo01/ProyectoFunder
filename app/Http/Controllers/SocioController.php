<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;



class SocioController extends Controller
{
    // listar
    public function index(Request $request)
    {
        $query = Socio::query()
            ->when(
                $request->filled('estado'),
                fn($q) => $q->where('estado', $request->estado),
                fn($q) => $q->where('estado', 1) // por defecto activos
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nombre_Beneficiario', 'like', "%$search%")
                  ->orWhere('DNI', 'like', "%$search%")
                  ->orWhere('Telefono', 'like', "%$search%");
            });
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('localidad')) {
            $query->where('comunidad', 'like', "%{$request->localidad}%");
        }

        if ($request->filled('tipo')) {
            $query->where('Tipo_De_Socio', 'like', "%{$request->tipo}%");
        }
        if ($request->filled('departamento')) {
    $query->where('departamento', $request->departamento);
}

        if ($request->filled('estado_civil')) {
            $query->where('estado_civil', $request->estado_civil);
        }

        if ($request->filled('nivel_educativo')) {
            $query->where('nivel_educativo', $request->nivel_educativo);
        }
        if ($request->filled('edad')) {
    $query->where('edad', $request->edad);
}


        $socios = $query->paginate(10);

        return view('socios.index', compact('socios'));
    }

    // mostrar formulario de creación
    public function create()
    {
        return view('socios.create');
    }

    // guardar nuevo socio
 public function store(Request $request)
{
    $validated = $request->validate([
        'Id_Organizacion'       => 'required|integer',
        'Nombre_Caja'           => 'required|string|max:150',
        'Nombre_Beneficiario'   => 'required|string|max:150',
        'DNI'                   => 'required|regex:/^\d{4}-\d{4}-\d{5}$/|unique:tbl_beneficiario,DNI',
        'genero'                => 'required|in:M,F',
        'fecha_nacimiento'      => 'nullable|date',
        'edad'                  => 'nullable|integer|min:15|max:100',
        'estado_civil'          => 'nullable|string|max:50',
        'etnia'                 => 'nullable|string|max:100',
        'nivel_educativo'       => 'nullable|string|max:100',
        'medio_comunicacion'    => 'nullable|string|max:100',
        'departamento'          => 'nullable|string|max:100',
        'municipio'             => 'nullable|string|max:100',
        'comunidad'             => 'nullable|string|max:100',
        'direccion'             => 'nullable|string|max:150',
        'Telefono'              => 'nullable|regex:/^\d{4}-\d{4}$/',
        'actividad_economica'   => 'nullable|string|max:150',
        'actividad_no_agricola' => 'nullable|string|max:150',
        'Tipo_Cargo'            => 'nullable|string|max:100',
        'Tipo_De_Socio'         => 'nullable|string|max:100',
        'categoria'             => 'nullable|string|max:100',
        'estado'                => 'required|boolean'
    ]);

    try {
        Socio::create($validated);
        return redirect()->route('socios.index')->with('success', 'Socio creado correctamente.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Ocurrió un error al guardar el socio: ' . $e->getMessage()]);
    }
}



    // mostrar formulario de edición
    public function edit($id)
    {
        $socio = Socio::findOrFail($id);
        return view('socios.edit', compact('socio'));
    }

    // actualizar socio
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Id_Organizacion'       => 'required|integer',
            'Nombre_Beneficiario'   => 'required|max:150',
            'DNI'                   => [
            'required',
            'regex:/^\d{4}-\d{4}-\d{5}$/',
            Rule::unique('tbl_beneficiario', 'DNI')->ignore($id, 'Id_Beneficiario'),],
            'genero'                => 'required|in:M,F',
            'Nombre_Caja'           => 'nullable|max:150',
            'fecha_nacimiento'      => 'nullable|date',
            'edad'                  => 'nullable|integer',
            'estado_civil'          => 'nullable|max:50',
            'etnia'                 => 'nullable|max:100',
            'nivel_educativo'       => 'nullable|max:100',
            'medio_comunicacion'    => 'nullable|max:100',
            'departamento'          => 'nullable|max:100',
            'municipio'             => 'nullable|max:100',
            'comunidad'             => 'nullable|max:100',
            'direccion'             => 'nullable|max:150',
            'Telefono'              => 'nullable|max:20',
            'actividad_economica'   => 'nullable|max:150',
            'actividad_no_agricola' => 'nullable|max:150',
            'Tipo_Cargo'            => 'nullable|max:100',
            'Tipo_De_Socio'         => 'nullable|max:100',
            'categoria'             => 'nullable|max:100',
            'estado'                => 'boolean'
        ]);

        $socio = Socio::findOrFail($id);
        $socio->update($validated);

        return redirect()->route('socios.index')
            ->with('success', 'Socio actualizado correctamente.');
    }

    // eliminar (baja lógica)
    public function destroy($id)
    {
        $socio = Socio::findOrFail($id);
        $socio->update(['estado' => 0]);

        return redirect()->route('socios.index')
            ->with('success', 'Socio inactivado correctamente.');
    }

    // ficha de socio
    public function ficha($id)
    {
        $socio = Socio::findOrFail($id);
        return view('socios.ficha', compact('socio'));
    }

    // reactivar socio
    public function reactivar($id)
    {
        $socio = Socio::findOrFail($id);
        $socio->estado = 1;
        $socio->save();

        return redirect()->route('socios.index')
            ->with('success', 'Socio reactivado correctamente.');
    }
}

