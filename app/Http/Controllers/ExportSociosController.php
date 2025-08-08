<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExportSociosController extends Controller
{
   public function export(Request $request)
{
    // 1) FILTROS
    $q = \App\Models\Socio::query()->where('estado', 1);

    if ($request->filled('search')) {
        $s = $request->string('search');
        $q->where(function ($w) use ($s) {
            $w->where('Nombre_Beneficiario', 'like', "%$s%")
              ->orWhere('DNI', 'like', "%$s%")
              ->orWhere('Telefono', 'like', "%$s%");
        });
    }
    if ($request->filled('genero'))     $q->where('genero', $request->genero);
    if ($request->filled('localidad'))  $q->where('comunidad', 'like', "%{$request->localidad}%");
    if ($request->filled('tipo'))       $q->where('Tipo_De_Socio', 'like', "%{$request->tipo}%");

    $socios = $q->get();

    // 2) SPREADSHEET + HOJA
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte de Socios');

    // 3) CONFIG DE PÁGINA (horizontal, márgenes, ajustar a 1 página de ancho)
    $ps = $sheet->getPageSetup();
    $ps->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $ps->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);
    $ps->setFitToWidth(1);
    $ps->setFitToHeight(0); // alto libre

    $margins = $sheet->getPageMargins();
    $margins->setTop(0.4)->setBottom(0.4)->setLeft(0.35)->setRight(0.35);

    // Pie de página con paginación
    $sheet->getHeaderFooter()->setOddFooter('&R Página &P de &N');

    // 4) ENCABEZADO (fecha izq, títulos centrados, logo der)
    // Columnas A:O (15 cols). Ajusta si cambias cantidad de columnas.
    foreach (range('A','O') as $c) {
        $sheet->getColumnDimension($c)->setAutoSize(true);
    }

    // Fecha (A1)
    $sheet->setCellValue('A1', 'FECHA: '.now()->format('Y/m/d'));
    $sheet->getStyle('A1')->getFont()->setSize(10);

    // Títulos centrados (merge)
    $sheet->mergeCells('C1:K1');
    $sheet->mergeCells('C2:K2');
    $sheet->setCellValue('C1', 'FUNDER');
    $sheet->setCellValue('C2', 'Reporte de Socios');
    $sheet->getStyle('C1:C2')->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(13);
    $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(12);

    // Logo (derecha). Cambia la ruta si tu logo está en otro sitio.
    $logoPath = public_path('images/cropped-cropped-logo-funder-1.webp');
    if (is_file($logoPath)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath($logoPath);
        $drawing->setHeight(55);
        $drawing->setCoordinates('M1'); // cerca del extremo derecho
        $drawing->setOffsetX(10)->setOffsetY(0);
        $drawing->setWorksheet($sheet);
    }

    // Altura filas de cabecera
    $sheet->getRowDimension(1)->setRowHeight(22);
    $sheet->getRowDimension(2)->setRowHeight(20);

    // 5) TABLA
    $headers = [
        'No.','Nombre','DNI','Teléfono','Género','Estado Civil','Nivel Educativo',
        'Departamento','Municipio','Comunidad','Tipo Socio','Estado'
    ];
    $startRow = 4;           // fila donde inician encabezados de tabla
    $startCol = 'A';
    $endCol   = 'L';         // 12 columnas (A..L)

    // Escribir encabezados
    $sheet->fromArray($headers, null, $startCol.$startRow);

    // Estilo encabezados: gris claro + negrita + centrado + bordes
    $sheet->getStyle("{$startCol}{$startRow}:{$endCol}{$startRow}")->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'wrapText'   => true,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F0F0F0'],
        ],
        'borders' => ['allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ]],
    ]);
    $sheet->getRowDimension($startRow)->setRowHeight(20);

    // Datos
    $row = $startRow + 1;
    $n = 1;
    foreach ($socios as $s) {
        $sheet->fromArray([[
            $n++,
            $s->Nombre_Beneficiario,
            $s->DNI,
            $s->Telefono,
            $s->genero,
            $s->estado_civil,
            $s->nivel_educativo,
            $s->departamento,
            $s->municipio,
            $s->comunidad,
            $s->Tipo_De_Socio,
            $s->estado == 1 ? 'Activo' : 'Inactivo',
        ]], null, "A{$row}");

        $row++;
    }

    // Bordes a toda la tabla (encabezado + datos)
    $lastDataRow = max($row-1, $startRow);
    $sheet->getStyle("{$startCol}{$startRow}:{$endCol}{$lastDataRow}")->applyFromArray([
        'borders' => ['allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ]],
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ]);

    // Alinear texto: nombre a la izquierda, números centrados, etc.
    $sheet->getStyle("B".($startRow+1).":B{$lastDataRow}")
          ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle("A".($startRow).":A{$lastDataRow}")
          ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("C".($startRow+1).":L{$lastDataRow}")
          ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Filtro y freeze pane (como en reportes)
    $sheet->setAutoFilter("{$startCol}{$startRow}:{$endCol}{$lastDataRow}");
    $sheet->freezePane('A'.($startRow+1));

    // 6) DESCARGA
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    // Limpia cualquier salida previa
    if (ob_get_length()) { ob_end_clean(); }

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, 'socios.xlsx', [
        'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control'       => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
        'Pragma'              => 'public',
    ]);
}
}