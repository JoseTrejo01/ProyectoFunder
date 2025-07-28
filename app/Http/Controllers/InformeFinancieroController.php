<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InformeFinancieroController extends Controller
{
    public function mostrarInforme(Request $request) // recibe Request para filtrar
    {
        $departamentos = DB::table('tbl_departamento')->pluck('Nombre_Departamento');

        $query = DB::table('tbl_departamento as d')
            ->leftJoin('tbl_prestamos as p', 'p.departamento_id', '=', 'd.Id_Departamento')
            ->select(
                'd.Nombre_Departamento as departamento',

                DB::raw('SUM(p.monto_solicitado - COALESCE((SELECT SUM(monto_pagado) FROM tbl_pagos pay WHERE pay.prestamo_id = p.id AND pay.estado = "pagado"), 0)) as prestamos_por_cobrar'),

                DB::raw('SUM(p.monto_solicitado) as total_monto_solicitado'),

                DB::raw('SUM(CASE WHEN p.porcentaje_mora_caja > 0 THEN p.monto_solicitado ELSE 0 END) as saldo_prestamo_mora'),

                DB::raw('(SELECT COALESCE(SUM(a.Monto), 0)
                        FROM tbl_ahorros a
                        JOIN tbl_prestamos p2 ON a.Id_Beneficiario = p2.beneficiario_id
                        WHERE p2.departamento_id = d.Id_Departamento) as depositos_ahorro'),

                DB::raw('SUM(CASE WHEN p.estado IN ("aprobado", "desembolsado") THEN (p.monto_solicitado - COALESCE((SELECT SUM(monto_pagado) FROM tbl_pagos pay WHERE pay.prestamo_id = p.id AND pay.estado = "pagado"), 0)) ELSE 0 END) as prestamos_por_pagar'),

                DB::raw('SUM(p.capital_social) as total_capital_social'),

                DB::raw('SUM(p.capital_trabajo) as total_capital_trabajo'),

                DB::raw('SUM(p.reservas) as total_reservas'),

                DB::raw('SUM(p.intereses_cobrados) as total_intereses_cobrados'),

                DB::raw('SUM(p.capital_social + p.capital_trabajo + p.reservas) as total_capital_semilla'),

                DB::raw('COUNT(p.id) as total_prestamos_registrados'),

                DB::raw('
                    CASE 
                        WHEN SUM(p.monto_solicitado) > 0 THEN 
                            ROUND(
                                SUM(CASE WHEN p.porcentaje_mora_caja > 0 THEN p.monto_solicitado ELSE 0 END) 
                                / SUM(p.monto_solicitado) * 100, 2
                            )
                        ELSE 0
                    END as porcentaje_mora
                ')
            )
            ->groupBy('d.Id_Departamento', 'd.Nombre_Departamento');

        // Aplicar filtro por departamento si existe en la request
        if ($request->filled('departamento')) {
            $query->where('d.Nombre_Departamento', $request->departamento);
        }

        $resultados = $query->get();
        

        return view('informes.informe_departamentos', compact('resultados', 'departamentos'));
    }
}
