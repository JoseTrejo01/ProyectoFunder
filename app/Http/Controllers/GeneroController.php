<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneroController extends Controller
{
     public function index()
    {
        return view('genero.index');
    }

    public function obtenerDatos()
    {
        $hombres = DB::table('tbl_beneficiario')->where('genero', 'Masculino')->count();
        $mujeres = DB::table('tbl_beneficiario')->where('genero', 'Femenino')->count();

        $adultos = DB::table('tbl_beneficiario')->where('edad', '>=', 18)->count();
        $ninos = DB::table('tbl_beneficiario')->where('edad', '<', 18)->count();

        $promedioEdadHombres = DB::table('tbl_beneficiario')->where('genero', 'Masculino')->avg('edad');
        $promedioEdadMujeres = DB::table('tbl_beneficiario')->where('genero', 'Femenino')->avg('edad');

        return response()->json([
            'hombres' => $hombres,
            'mujeres' => $mujeres,
            'adultos' => $adultos,
            'ninos' => $ninos,
            'promEdadHombres' => round($promedioEdadHombres, 1),
            'promEdadMujeres' => round($promedioEdadMujeres, 1),
        ]);
    }
}
