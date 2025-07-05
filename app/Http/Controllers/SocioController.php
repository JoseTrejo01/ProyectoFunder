<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;

class SocioController extends Controller
{
    // listar
   public function index(Request $request)
{
    $query = Socio::where('estado', 1);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('Nombre_Beneficiario', 'like', "%$search%")
              ->orWhere('DNI', 'like', "%$search%")
              ->orWhere('Telefono', 'like', "%$search%");
        });
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
            'Id_Organizacion' => 'required|integer',
            'Nombre_Beneficiario' => 'required|max:100',
            'DNI' => 'required|unique:tbl_beneficiario,DNI|max:30',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'nullable|date',
            'Telefono' => 'nullable|max:20',
            'direccion' => 'nullable|max:150',
            'actividad_economica' => 'nullable',
            'Tipo_Cargo' => 'nullable',
            'Tipo_De_Socio' => 'nullable',
            'estado' => 'boolean'
        ]);

        Socio::create($validated);

        return redirect()->route('socios.index')
            ->with('success', 'Socio creado correctamente.');
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
            'Id_Organizacion' => 'required|integer',
            'Nombre_Beneficiario' => 'required|max:100',
            'DNI' => 'required|unique:tbl_beneficiario,DNI|max:30',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'nullable|date',
            'Telefono' => 'nullable|max:20',
            'direccion' => 'nullable|max:150',
            'actividad_economica' => 'nullable',
            'Tipo_Cargo' => 'nullable',
            'Tipo_De_Socio' => 'nullable',
            'estado' => 'boolean'
        ]);

        $socio = Socio::findOrFail($id);
        $socio->update($validated);

        return redirect()->route('socios.index')
            ->with('success', 'Socio actualizado correctamente.');
    }

    // eliminar (soft delete)
    public function destroy($id)
    {
        $socio = Socio::findOrFail($id);
        $socio->update(['estado' => 0]); // baja lógica
       return redirect()->route('socios.index')
    ->with('success', 'Socio inactivado correctamente.');

    }
}
