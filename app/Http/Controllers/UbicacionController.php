<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\Aldea;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    /**
     * Retorna los municipios pertenecientes a un departamento dado.
     *
     * @param  int  $departamento_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMunicipios($departamento_id)
    {
        $municipios = Municipio::where('Id_Departamento', $departamento_id)
            ->get(['Id_Municipio as id', 'Nombre_Municipio as nombre']);

        return response()->json($municipios);
    }

    /**
     * Retorna las aldeas pertenecientes a un municipio dado.
     *
     * @param  int  $municipio_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAldeas($municipio_id)
    {
        $aldeas = Aldea::where('Id_Municipio', $municipio_id)
            ->get(['Id_Aldea as id', 'Nombre_Aldea as nombre']);

        return response()->json($aldeas);
    }
}
