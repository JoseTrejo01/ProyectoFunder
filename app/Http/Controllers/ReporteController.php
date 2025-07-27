<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $departamento = $request->input('departamento');

        // Consulta: obtener resumen de socios y beneficiarios por departamento
        $query = DB::table('tbl_organizacion as o')
            ->join('tbl_beneficiario as b', 'o.Id_Organizacion', '=', 'b.Id_Organizacion')
            ->join('tbl_aldea as a', 'o.Id_Aldea', '=', 'a.Id_Aldea')
            ->join('tbl_municipio as m', 'a.Id_Municipio', '=', 'm.Id_Municipio')
            ->join('tbl_departamento as d', 'm.Id_Departamento', '=', 'd.Id_Departamento')
            ->select(
                'd.Nombre_Departamento as departamento',
                DB::raw('COUNT(DISTINCT o.Id_Organizacion) as cajas'),
                DB::raw('COUNT(DISTINCT m.Id_Municipio) as municipios'),
                DB::raw('COUNT(DISTINCT a.Id_Aldea) as aldeas'),
                // Socios por género
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" AND b.genero = "M" THEN b.Id_Beneficiario END) as socios_h'),
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" AND b.genero = "F" THEN b.Id_Beneficiario END) as socios_m'),
                // Socios adultos y niños
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" AND b.edad >= 18 THEN b.Id_Beneficiario END) as socios_adultos'),
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" AND b.edad < 18 THEN b.Id_Beneficiario END) as socios_ninos'),
                // Total socios
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" THEN b.Id_Beneficiario END) as socios'),
                // No socios
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio != "Socio" OR b.Tipo_De_Socio IS NULL THEN b.Id_Beneficiario END) as no_socios'),
                // Total beneficiarios
                DB::raw('COUNT(DISTINCT b.Id_Beneficiario) as beneficiarios')
            );

        if ($departamento) {
            $query->where('d.Nombre_Departamento', $departamento);
        }

        $resumen = $query->groupBy('d.Nombre_Departamento')->get();
        $departamentos = DB::table('tbl_departamento')->pluck('Nombre_Departamento');

        return view('admin.reportes.index', compact('resumen', 'departamentos', 'departamento'));
    }
    /**
     * Reporte de cajas rurales y distribución de socios (mismo contenido que index, pero vista diferente)
     */
    public function cajas(Request $request)
    {
        $departamento = $request->input('departamento');

        $query = DB::table('tbl_organizacion as o')
            ->join('tbl_beneficiario as b', 'o.Id_Organizacion', '=', 'b.Id_Organizacion')
            ->join('tbl_aldea as a', 'o.Id_Aldea', '=', 'a.Id_Aldea')
            ->join('tbl_municipio as m', 'a.Id_Municipio', '=', 'm.Id_Municipio')
            ->join('tbl_departamento as d', 'm.Id_Departamento', '=', 'd.Id_Departamento')
            ->select(
                'd.Nombre_Departamento as departamento',
                DB::raw('COUNT(DISTINCT o.Id_Organizacion) as cajas'),
                DB::raw('COUNT(DISTINCT m.Id_Municipio) as municipios'),
                DB::raw('COUNT(DISTINCT a.Id_Aldea) as comunidades'),
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" AND b.genero = "M" THEN b.Id_Beneficiario END) as socios_h'),
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" AND b.genero = "F" THEN b.Id_Beneficiario END) as socios_m'),
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" AND b.edad >= 18 THEN b.Id_Beneficiario END) as socios_adultos'),
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" AND b.edad < 18 THEN b.Id_Beneficiario END) as socios_ninos'),
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio = "Socio" THEN b.Id_Beneficiario END) as socios'),
                DB::raw('COUNT(DISTINCT CASE WHEN b.Tipo_De_Socio != "Socio" OR b.Tipo_De_Socio IS NULL THEN b.Id_Beneficiario END) as no_socios')
            );
        if ($departamento) {
            $query->where('d.Nombre_Departamento', $departamento);
        }
        $resumen = $query->groupBy('d.Nombre_Departamento')->get();
        $departamentos = DB::table('tbl_departamento')->pluck('Nombre_Departamento');
        return view('admin.reportes.cajas', compact('resumen', 'departamentos', 'departamento'));
    }

    /**
     * Reporte de cargos según género
     */
    public function cargos(Request $request)
    {
        $departamento_cargos = $request->input('departamento_cargos');
        $departamentos = DB::table('tbl_departamento')->pluck('Nombre_Departamento');

        // Definir los cargos a mostrar
        $cargos = [
            'Presidente Consejo Admon',
            'Secretario Consejo Admon',
            'Tesorero Consejo Admon',
            'Presidente Comité de Crédito',
            'Presidente Consejo Vigilancia',
        ];

        $query = DB::table('tbl_organizacion as o')
            ->join('tbl_beneficiario as b', 'o.Id_Organizacion', '=', 'b.Id_Organizacion')
            ->join('tbl_aldea as a', 'o.Id_Aldea', '=', 'a.Id_Aldea')
            ->join('tbl_municipio as m', 'a.Id_Municipio', '=', 'm.Id_Municipio')
            ->join('tbl_departamento as d', 'm.Id_Departamento', '=', 'd.Id_Departamento')
            ->select(
                'd.Nombre_Departamento as departamento',
                'b.Tipo_Cargo',
                'b.genero',
                DB::raw('COUNT(DISTINCT b.Id_Beneficiario) as total')
            )
            ->whereIn('b.Tipo_Cargo', $cargos)
            ->groupBy('d.Nombre_Departamento', 'b.Tipo_Cargo', 'b.genero');

        if ($departamento_cargos) {
            $query->where('d.Nombre_Departamento', $departamento_cargos);
        }

        $resultados = $query->get();

        // Armar el resumen agrupado por departamento y cargo
        $cargosGeneroResumen = [];
        foreach ($resultados as $row) {
            foreach ($cargos as $cargo) {
                if (!isset($cargosGeneroResumen[$row->departamento][$cargo]['M'])) {
                    $cargosGeneroResumen[$row->departamento][$cargo]['M'] = 0;
                }
                if (!isset($cargosGeneroResumen[$row->departamento][$cargo]['F'])) {
                    $cargosGeneroResumen[$row->departamento][$cargo]['F'] = 0;
                }
            }
            $cargosGeneroResumen[$row->departamento][$row->Tipo_Cargo][$row->genero] = $row->total;
        }

        return view('admin.reportes.cargos', compact('cargosGeneroResumen', 'departamentos', 'departamento_cargos'));
    }
}
