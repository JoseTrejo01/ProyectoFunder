<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportSociosController extends Controller
{
    public function export()
    {
        $socios = Socio::where('estado', 1)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'DNI');
        $sheet->setCellValue('D1', 'Teléfono');
        $sheet->setCellValue('E1', 'Dirección');

        $fila = 2;
        foreach ($socios as $socio) {
            $sheet->setCellValue("A{$fila}", $socio->Id_Beneficiario);
            $sheet->setCellValue("B{$fila}", $socio->Nombre_Beneficiario);
            $sheet->setCellValue("C{$fila}", $socio->DNI);
            $sheet->setCellValue("D{$fila}", $socio->Telefono);
            $sheet->setCellValue("E{$fila}", $socio->direccion);
            $fila++;
        }

        $writer = new Xlsx($spreadsheet);

        $filename = "socios.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }
}
