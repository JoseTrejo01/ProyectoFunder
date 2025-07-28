<?php

namespace App\Http\Controllers;

use App\Models\Criterio;
use Illuminate\Http\Request;

class CriterioController extends Controller
{
    public function index()
    {
        $criterios = Criterio::all();
        return view('criterio.index', compact('criterios'));
    }

    public function create()
    {
        return view('criterio.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'variable' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'subindice' => 'nullable|string|max:100'
    ]);

    // Guarda el criterio y almacena el modelo creado
    $criterio = Criterio::create($request->all());

    // Redirige correctamente con el modelo si deseas ir al edit
    // return redirect()->route('criterio.edit', $criterio); // ✅ si quieres ir a editar
    return redirect()->route('criterio.index')->with('success', 'Criterio creado correctamente.'); // ✅ si solo vuelves a la lista
}

    public function edit(Criterio $criterio)
    {
        return view('criterio.edit', compact('criterio'));
    }

    public function update(Request $request, Criterio $criterio)
    {
        $request->validate([
            'variable' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'subindice' => 'nullable|string|max:100'
        ]);

        $criterio->update($request->all());

        return redirect()->route('criterio.index')->with('success', 'Criterio actualizado correctamente.');
    }

    public function destroy(Criterio $criterio)
    {
        $criterio->delete();

        return redirect()->route('criterio.index')->with('success', 'Criterio eliminado correctamente.');
    }
}
