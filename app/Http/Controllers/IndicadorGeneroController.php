<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndicadorGeneroController extends Controller
{
    public function index()
{
    $beneficiarios = DB::table('tbl_beneficiario')
        ->select('genero', 'edad')
        ->get();

    $generos = [
        'Masculino' => 0,
        'Femenino' => 0
        // Eliminamos 'Otro'
    ];

    $edades = [
        'Niños (0-12)' => 0,
        'Adolescentes (13-17)' => 0,
        'Adultos (18-59)' => 0,
        'Adultos Mayores (60+)' => 0,
    ];

    foreach ($beneficiarios as $b) {
        // Interpretar 'M' y 'F'
        $gen = strtoupper($b->genero);
        if ($gen === 'M') {
            $generos['Masculino']++;
        } elseif ($gen === 'F') {
            $generos['Femenino']++;
        }
        // Si no es ni M ni F, no contamos ni agregamos 'Otro'

        // Edad
        $edad = $b->edad;
        if ($edad <= 12) {
            $edades['Niños (0-12)']++;
        } elseif ($edad <= 17) {
            $edades['Adolescentes (13-17)']++;
        } elseif ($edad <= 59) {
            $edades['Adultos (18-59)']++;
        } else {
            $edades['Adultos Mayores (60+)']++;
        }
    }

    return view('genero.index', compact('generos', 'edades'));
}

}
