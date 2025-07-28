<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Organizacion;
use App\Models\Criterio;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index()
    {
        $evaluaciones = Evaluacion::with(['organizacion', 'criterio'])->get();
        return view('evaluacion.index', compact('evaluaciones'));
    }

    public function create()
    {
        $organizaciones = Organizacion::where('Estado_Organizacion', 1)->get();
        $criterios = Criterio::all();
        return view('evaluacion.create', compact('organizaciones', 'criterios'));
    }

    public function store(Request $request)
    {
        Evaluacion::create($request->all());
        return redirect()->route('evaluacion.index')->with('success', 'Evaluación guardada.');
    }

    public function edit(Evaluacion $evaluacion)
    {
        $organizaciones = Organizacion::where('Estado_Organizacion', 1)->get();
        $criterios = Criterio::all();
        return view('evaluacion.edit', compact('evaluacion', 'organizaciones', 'criterios'));
    }

    public function update(Request $request, Evaluacion $evaluacion)
    {
        $evaluacion->update($request->all());
        return redirect()->route('evaluacion.index')->with('success', 'Evaluación actualizada.');
    }

    public function destroy(Evaluacion $evaluacion)
    {
        $evaluacion->delete();
        return redirect()->route('evaluacion.index')->with('success', 'Evaluación eliminada.');
    }
}
