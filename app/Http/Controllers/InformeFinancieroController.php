<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InformeFinancieroController extends Controller
{
    public function exportInformeFinancieroExcel(Request $request)
    {
        // 1. Obtener datos del informe financiero
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
        if ($request->filled('departamento')) {
            $query->where('d.Nombre_Departamento', $request->departamento);
        }
        $resultados = $query->get();

        // 2. Crear el Excel con PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 3. Encabezados
        $cols = [
            'Departamento',
            'Préstamos por cobrar',
            'Total monto solicitado',
            'Saldo préstamo mora',
            'Depósitos ahorro',
            'Préstamos por pagar',
            'Total capital social',
            'Total capital trabajo',
            'Total reservas',
            'Total intereses cobrados',
            'Total capital semilla',
            'Total préstamos registrados',
            'Porcentaje mora (%)'
        ];
        foreach ($cols as $i => $nombre) {
            $colLetra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue("{$colLetra}1", $nombre);
            $sheet->getColumnDimension($colLetra)->setWidth(15);
        }

        // 4. Datos
        $fila = 2;
        foreach ($resultados as $row) {
            $sheet->setCellValue("A{$fila}", $row->departamento);
            $sheet->setCellValue("B{$fila}", $row->prestamos_por_cobrar);
            $sheet->setCellValue("C{$fila}", $row->total_monto_solicitado);
            $sheet->setCellValue("D{$fila}", $row->saldo_prestamo_mora);
            $sheet->setCellValue("E{$fila}", $row->depositos_ahorro);
            $sheet->setCellValue("F{$fila}", $row->prestamos_por_pagar);
            $sheet->setCellValue("G{$fila}", $row->total_capital_social);
            $sheet->setCellValue("H{$fila}", $row->total_capital_trabajo);
            $sheet->setCellValue("I{$fila}", $row->total_reservas);
            $sheet->setCellValue("J{$fila}", $row->total_intereses_cobrados);
            $sheet->setCellValue("K{$fila}", $row->total_capital_semilla);
            $sheet->setCellValue("L{$fila}", $row->total_prestamos_registrados);
            $sheet->setCellValue("M{$fila}", $row->porcentaje_mora);
            $fila++;
        }

        // 5. Estilos compactos
        $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($cols));
        $sheet->getStyle("A1:{$maxCol}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 9],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
        $sheet->getDefaultRowDimension()->setRowHeight(16);
        $sheet->getRowDimension(1)->setRowHeight(20);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "informe_financiero.xlsx";
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save("php://output");
        exit;
    }

    public function mostrarInforme(Request $request)
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

