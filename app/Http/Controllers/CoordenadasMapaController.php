<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoordenadaMunicipio;

class CoordenadasMapaController extends Controller
{
    // Muestra el mapa con los puntos geográficos
    public function index()
    {
        $coordenadas = CoordenadaMunicipio::with(['departamento', 'municipio'])->get();

        return view('mapa.municipios', compact('coordenadas'));
    }
}
