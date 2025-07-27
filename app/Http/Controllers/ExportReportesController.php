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
        // Encabezados
        // Encabezados agrupados (dos filas)
        // Primera fila
        $sheet->setCellValue('A1', 'Departamento');
        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('B1', 'No. de Cajas Rurales');
        $sheet->mergeCells('B1:B2');
        $sheet->setCellValue('C1', 'Municipios');
        $sheet->mergeCells('C1:C2');
        $sheet->setCellValue('D1', 'Comunidades');
        $sheet->mergeCells('D1:D2');
        $sheet->setCellValue('E1', 'Socios');
        $sheet->mergeCells('E1:F1');
        $sheet->setCellValue('G1', 'Particulares');
        $sheet->mergeCells('G1:H1');
        $sheet->setCellValue('I1', 'Beneficiarios');
        $sheet->mergeCells('I1:K1');
        // Segunda fila
        $sheet->setCellValue('E2', 'H');
        $sheet->setCellValue('F2', 'M');
        $sheet->setCellValue('G2', 'Adultos');
        $sheet->setCellValue('H2', 'Niños');
        $sheet->setCellValue('I2', 'Socios');
        $sheet->setCellValue('J2', 'No Socios');
        $sheet->setCellValue('K2', 'Total');
        // Estilo encabezados agrupados
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A1:K2')->applyFromArray($headerStyle);
        // Datos
        $fila = 3;
        foreach ($resumen as $row) {
            $sheet->setCellValue("A{$fila}", $row->departamento);
            $sheet->setCellValue("B{$fila}", $row->cajas ?? '-');
            $sheet->setCellValue("C{$fila}", $row->municipios ?? '-');
            $sheet->setCellValue("D{$fila}", $row->comunidades ?? '-');
            $sheet->setCellValue("E{$fila}", $row->socios_h ?? '-');
            $sheet->setCellValue("F{$fila}", $row->socios_m ?? '-');
            $sheet->setCellValue("G{$fila}", $row->socios_adultos ?? '-');
            $sheet->setCellValue("H{$fila}", $row->socios_ninos ?? '-');
            $sheet->setCellValue("I{$fila}", $row->socios ?? '-');
            $sheet->setCellValue("J{$fila}", $row->no_socios ?? '-');
            $sheet->setCellValue("K{$fila}", ($row->socios + $row->no_socios) ?? '-');
            $fila++;
        }
        // Bordes y centrado para datos
        $lastRow = $fila - 1;
        $sheet->getStyle("A3:K$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);
        // Ajustar ancho de columnas
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
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
        // Encabezados agrupados (dos filas)
        $cargosList = [
            'Presidente Consejo Admon',
            'Secretario Consejo Admon',
            'Tesorero Consejo Admon',
            'Presidente Comité de Crédito',
            'Presidente Consejo Vigilancia',
        ];
        // Primera fila de encabezados
        $sheet->setCellValue('A1', 'Departamento');
        $col = 'B';
        foreach ($cargosList as $cargo) {
            $sheet->setCellValue($col.'1', $cargo);
            $sheet->mergeCells($col.'1:'.chr(ord($col)+1).'1');
            $col = chr(ord($col)+2);
        }
        // Segunda fila de encabezados
        $sheet->setCellValue('A2', '');
        $col = 'B';
        foreach ($cargosList as $cargo) {
            $sheet->setCellValue($col.'2', 'H');
            $col = chr(ord($col)+1);
            $sheet->setCellValue($col.'2', 'M');
            $col = chr(ord($col)+1);
        }
        // Estilo encabezados
        $lastCol = chr(ord('A') + count($cargosList)*2);
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle("A1:{$lastCol}2")->applyFromArray($headerStyle);
        // Datos
        $fila = 3;
        foreach ($cargosGeneroResumen as $dep => $cargosDep) {
            $sheet->setCellValue('A'.$fila, $dep);
            $col = 'B';
            foreach ($cargosList as $cargo) {
                $sheet->setCellValue($col.$fila, $cargosDep[$cargo]['M'] ?? 0);
                $col = chr(ord($col)+1);
                $sheet->setCellValue($col.$fila, $cargosDep[$cargo]['F'] ?? 0);
                $col = chr(ord($col)+1);
            }
            $fila++;
        }
        // Fila de totales
        $totales = [];
        $porcentajes = [];
        $totalGeneral = 0;
        foreach ($cargosList as $cargo) {
            $totales[$cargo]['M'] = 0;
            $totales[$cargo]['F'] = 0;
            foreach ($cargosGeneroResumen as $dep => $cargosDep) {
                $totales[$cargo]['M'] += $cargosDep[$cargo]['M'] ?? 0;
                $totales[$cargo]['F'] += $cargosDep[$cargo]['F'] ?? 0;
            }
            $totalGeneral += $totales[$cargo]['M'] + $totales[$cargo]['F'];
        }
        foreach ($cargosList as $cargo) {
            $totalCargo = $totales[$cargo]['M'] + $totales[$cargo]['F'];
            $porcentajes[$cargo]['M'] = $totalCargo > 0 ? round(($totales[$cargo]['M'] / $totalCargo) * 100, 1) : 0;
            $porcentajes[$cargo]['F'] = $totalCargo > 0 ? round(($totales[$cargo]['F'] / $totalCargo) * 100, 1) : 0;
        }
        // Totales
        $sheet->setCellValue('A'.$fila, 'Total');
        $col = 'B';
        foreach ($cargosList as $cargo) {
            $sheet->setCellValue($col.$fila, $totales[$cargo]['M']);
            $col = chr(ord($col)+1);
            $sheet->setCellValue($col.$fila, $totales[$cargo]['F']);
            $col = chr(ord($col)+1);
        }
        $fila++;
        // Porcentajes
        $sheet->setCellValue('A'.$fila, '% Participación');
        $col = 'B';
        foreach ($cargosList as $cargo) {
            $sheet->setCellValue($col.$fila, $porcentajes[$cargo]['M'].'%');
            $col = chr(ord($col)+1);
            $sheet->setCellValue($col.$fila, $porcentajes[$cargo]['F'].'%');
            $col = chr(ord($col)+1);
        }
        // Bordes y centrado para datos
        $lastRow = $fila;
        $sheet->getStyle("A3:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);
        // Ajustar ancho de columnas
        foreach (range('A', $lastCol) as $colLet) {
            $sheet->getColumnDimension($colLet)->setAutoSize(true);
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
