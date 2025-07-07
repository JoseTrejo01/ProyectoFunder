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

        // poner estilos de cabecera
$sheet->getStyle('A1:O1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4CAF50'] // verde bonito
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
]);

// opcional: ancho automático de columnas
foreach (range('A', 'O') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}


        // Encabezados
     $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'DNI');
        $sheet->setCellValue('D1', 'Teléfono');
        $sheet->setCellValue('E1', 'Género');
        $sheet->setCellValue('F1', 'Estado Civil');
        $sheet->setCellValue('G1', 'Nivel Educativo');
        $sheet->setCellValue('H1', 'Medio de Comunicación');
        $sheet->setCellValue('I1', 'Departamento');
        $sheet->setCellValue('J1', 'Municipio');
        $sheet->setCellValue('K1', 'Comunidad');
        $sheet->setCellValue('L1', 'Tipo Cargo');
        $sheet->setCellValue('M1', 'Tipo Socio');
        $sheet->setCellValue('N1', 'Categoría');
        $sheet->setCellValue('O1', 'Estado');

        $fila = 2;
       foreach ($socios as $socio) {
            $sheet->setCellValue("A{$fila}", $socio->Id_Beneficiario);
            $sheet->setCellValue("B{$fila}", $socio->Nombre_Beneficiario);
            $sheet->setCellValue("C{$fila}", $socio->DNI);
            $sheet->setCellValue("D{$fila}", $socio->Telefono);
            $sheet->setCellValue("E{$fila}", $socio->genero);
            $sheet->setCellValue("F{$fila}", $socio->estado_civil);
            $sheet->setCellValue("G{$fila}", $socio->nivel_educativo);
            $sheet->setCellValue("H{$fila}", $socio->medio_comunicacion);
            $sheet->setCellValue("I{$fila}", $socio->departamento);
            $sheet->setCellValue("J{$fila}", $socio->municipio);
            $sheet->setCellValue("K{$fila}", $socio->comunidad);
            $sheet->setCellValue("L{$fila}", $socio->Tipo_Cargo);
            $sheet->setCellValue("M{$fila}", $socio->Tipo_De_Socio);
            $sheet->setCellValue("N{$fila}", $socio->categoria);
            $sheet->setCellValue("O{$fila}", $socio->estado == 1 ? 'Activo' : 'Inactivo');
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
