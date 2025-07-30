<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class ExportReportesController extends Controller
{
    public function exportCajas(Request $request)
    {
        // Repetir la lógica del reporte de cajas
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Departamento');
        $sheet->setCellValue('B1', 'No. de Cajas Rurales');
        $sheet->setCellValue('C1', 'Municipios');
        $sheet->setCellValue('D1', 'Comunidades');
        $sheet->setCellValue('E1', 'Socios H');
        $sheet->setCellValue('F1', 'Socios M');
        $sheet->setCellValue('G1', 'Socios Adultos');
        $sheet->setCellValue('H1', 'Socios Niños');
        $sheet->setCellValue('I1', 'Socios');
        $sheet->setCellValue('J1', 'No Socios');
        $fila = 2;
        foreach ($resumen as $row) {
            $sheet->setCellValue("A{$fila}", $row->departamento);
            $sheet->setCellValue("B{$fila}", $row->cajas);
            $sheet->setCellValue("C{$fila}", $row->municipios);
            $sheet->setCellValue("D{$fila}", $row->comunidades);
            $sheet->setCellValue("E{$fila}", $row->socios_h);
            $sheet->setCellValue("F{$fila}", $row->socios_m);
            $sheet->setCellValue("G{$fila}", $row->socios_adultos);
            $sheet->setCellValue("H{$fila}", $row->socios_ninos);
            $sheet->setCellValue("I{$fila}", $row->socios);
            $sheet->setCellValue("J{$fila}", $row->no_socios);
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = "reporte_cajas.xlsx";
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }

    public function exportCargos(Request $request)
    {
        // Aquí deberías repetir la lógica del reporte de cargos según género
        // Por simplicidad, solo exporta los totales por departamento y cargo
        $departamento_cargos = $request->input('departamento_cargos');
        $departamentos = DB::table('tbl_departamento')->pluck('Nombre_Departamento');
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
        // Inicializar resumen igual que en el reporte
        $cargosGeneroResumen = [];
        foreach ($departamentos as $departamento) {
            foreach ($cargos as $cargo) {
                $cargosGeneroResumen[$departamento][$cargo]['M'] = 0;
                $cargosGeneroResumen[$departamento][$cargo]['F'] = 0;
            }
        }
        foreach ($resultados as $row) {
            $cargosGeneroResumen[$row->departamento][$row->Tipo_Cargo][$row->genero] = $row->total;
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Departamento');
        $col = 'B';
        foreach ($cargos as $cargo) {
            $sheet->setCellValue($col.'1', $cargo.' H');
            $col++;
            $sheet->setCellValue($col.'1', $cargo.' M');
            $col++;
        }
        $fila = 2;
        foreach ($cargosGeneroResumen as $dep => $cargosDep) {
            $sheet->setCellValue('A'.$fila, $dep);
            $col = 'B';
            foreach ($cargos as $cargo) {
                $sheet->setCellValue($col.$fila, $cargosDep[$cargo]['M']);
                $col++;
                $sheet->setCellValue($col.$fila, $cargosDep[$cargo]['F']);
                $col++;
            }
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = "reporte_cargos.xlsx";
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }
}
