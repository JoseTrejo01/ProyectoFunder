<?php

namespace App\Http\Controllers;

use App\Models\IndicadorGenero;
use Illuminate\Http\Request;

class IndicadorGeneroController extends Controller
{
    public function index()
    {
        $indicadores = IndicadorGenero::all();
        return view('genero.index', compact('indicadores'));
    }

 public function create()
{
    return view('genero.create');
}



    public function store(Request $request)
    {
        $request->validate([
            'nombre_caja_rural' => 'required|string|max:255',
            'departamento' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'comunidad' => 'required|string|max:100',
            'nombre_apellidos' => 'required|string|max:255',
            'sexo' => 'required|string|in:Masculino,Femenino',
            'etnia' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer|min:0',
            'identidad' => 'required|string|max:50',
            'cargo' => 'required|string|max:100',
        ]);

        IndicadorGenero::create($request->all());

        return redirect()->route('genero.index')->with('success', 'Registro guardado correctamente.');
    }

    public function edit(IndicadorGenero $genero)
    {
        return view('genero.edit', compact('genero'));
    }

    public function update(Request $request, IndicadorGenero $genero)
{
    $request->validate([
        'nombre_caja_rural' => 'required|string|max:255',
        'departamento' => 'required|string|max:100',
        'municipio' => 'required|string|max:100',
        'comunidad' => 'required|string|max:100',
        'nombre_apellidos' => 'required|string|max:255',
        'sexo' => 'required|string|in:Masculino,Femenino', // validación agregada
        'etnia' => 'required|string|max:100',
        'fecha_nacimiento' => 'required|date',
        'edad' => 'required|integer|min:0',
        'identidad' => 'required|string|max:50',
        'cargo' => 'required|string|max:100',
    ]);

    $genero->update($request->all());

    return redirect()->route('genero.index')->with('success', 'Registro actualizado correctamente.');
}

    public function destroy(IndicadorGenero $genero)
    {
        $genero->delete();
        return redirect()->route('genero.index')->with('success', 'Registro eliminado.');
    }
}
