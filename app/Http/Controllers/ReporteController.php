<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Reporte de cargos según género por departamento
     */
    public function cargos(Request $request)
    {
        $departamento = $request->input('departamento_cargos');
        $cargosList = [
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
            ->whereIn('b.Tipo_Cargo', $cargosList);
        if ($departamento) {
            $query->where('d.Nombre_Departamento', $departamento);
        }
        $rows = $query->groupBy('d.Nombre_Departamento', 'b.Tipo_Cargo', 'b.genero')->get();
        // Armar resumen para la vista
        $cargosGeneroResumen = [];
        foreach ($rows as $row) {
            $cargosGeneroResumen[$row->departamento][$row->Tipo_Cargo][$row->genero] = $row->total;
        }
        $departamentos = DB::table('tbl_departamento')->pluck('Nombre_Departamento');
        return view('admin.reportes.cargos', compact('cargosGeneroResumen', 'departamentos', 'departamento'));
    }

    /**
     * Exporta el reporte de capacitaciones y beneficiarios a Excel
     */
    public function exportCapacitacionesExcel()
    {
        // 1. Obtener módulos y temas
        // Orden manual de módulos
        $ordenManual = [
            'Módulo I: Organización de cajas rurales / Gobernanza',
            'Módulo II: Cálculo de intereses y administración de ahorros',
            'Módulo III: Administración de préstamos',
            'Módulo IV: Elaboración de estados financieros para las cajas rurales',
            'Módulo V: Aspectos legales; Obtención de personalidad jurídica y/o Actualización de Junta Directiva',
            'Contabilidad empresarial (para emprendimientos)',
            'Elaboración de planes estratégicos'
        ];
        $modulosAll = DB::table('tbl_modulo_capacitacion')->select('Id_Modulo', 'Nombre_Modulo')->get();
        $modulos = collect($ordenManual)
            ->map(function($nombre) use ($modulosAll) {
                return $modulosAll->first(function($m) use ($nombre) {
                    return $m->Nombre_Modulo === $nombre;
                });
            })
            ->filter();
        $temasPorModulo = [];
        $temasTotales = [];
        foreach ($modulos as $modulo) {
            $temasPorModulo[$modulo->Id_Modulo] = DB::table('tbl_tema_modulo')
                ->where('Id_Modulo', $modulo->Id_Modulo)
                ->select('Id_Tema', 'Nombre_Tema')
                ->get();
            foreach ($temasPorModulo[$modulo->Id_Modulo] as $tema) {
                $temasTotales[] = ['modulo' => $modulo, 'tema' => $tema];
            }
        }

        // 2. Obtener beneficiarios con datos de organización y ubicación
        $beneficiarios = DB::table('tbl_beneficiario as b')
            ->join('tbl_organizacion as o', 'b.Id_Organizacion', '=', 'o.Id_Organizacion')
            ->leftJoin('tbl_aldea as a', 'o.Id_Aldea', '=', 'a.Id_Aldea')
            ->leftJoin('tbl_municipio as m', 'a.Id_Municipio', '=', 'm.Id_Municipio')
            ->leftJoin('tbl_departamento as d', 'm.Id_Departamento', '=', 'd.Id_Departamento')
            ->select([
                'b.Id_Beneficiario',
                'b.Nombre_Beneficiario',
                'o.Nombre_Organizacion as Caja_Rural',
                'd.Nombre_Departamento as Departamento',
                'm.Nombre_Municipio as Municipio',
                'a.Nombre_Aldea as Aldea',
            ])->get();

        // 3. Obtener registros de recibido
        $recibidos = DB::table('tbl_capacitacion_miembro')->get();
        $recibidoMap = [];
        foreach ($recibidos as $r) {
            $recibidoMap[$r->Id_Beneficiario][$r->Id_Modulo][$r->Id_Tema] = $r->Recibido ? '✓' : '';
        }

        // 4. Crear el Excel con PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 5. Encabezados agrupados
        $colsFijas = ['Caja Rural', 'Departamento', 'Municipio', 'Aldea', 'Beneficiario'];
        foreach ($colsFijas as $i => $nombre) {
            $colLetra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue("{$colLetra}1", $nombre);
            $sheet->mergeCells("{$colLetra}1:{$colLetra}2");
            $sheet->getColumnDimension($colLetra)->setWidth(13); // ancho fijo compacto
        }
        $col = count($colsFijas) + 1;
        foreach ($modulos as $modulo) {
            $temas = $temasPorModulo[$modulo->Id_Modulo];
            $colSpan = count($temas);
            if ($colSpan < 1) {
                continue; // No hacer merge ni encabezado si no hay temas
            }
            $colLetraIni = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $colLetraFin = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + $colSpan - 1);
            $sheet->mergeCells("{$colLetraIni}1:{$colLetraFin}1");
            $sheet->setCellValue("{$colLetraIni}1", $modulo->Nombre_Modulo);
            // Subencabezados de temas
            foreach ($temas as $i => $tema) {
                $colLetra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + $i);
                $sheet->setCellValue("{$colLetra}2", $tema->Nombre_Tema);
                $sheet->getColumnDimension($colLetra)->setWidth(10); // ancho fijo compacto para temas
            }
            $col += $colSpan;
        }

        // 6. Beneficiarios y checks
        $fila = 3;
        foreach ($beneficiarios as $ben) {
            $sheet->setCellValue("A{$fila}", $ben->Caja_Rural);
            $sheet->setCellValue("B{$fila}", $ben->Departamento);
            $sheet->setCellValue("C{$fila}", $ben->Municipio);
            $sheet->setCellValue("D{$fila}", $ben->Aldea);
            $sheet->setCellValue("E{$fila}", $ben->Nombre_Beneficiario);
            // Aplica bordes a todas las celdas de la fila (fijas y dinámicas)
            $maxCol = $col - 1;
            $colLetraIni = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1);
            $colLetraFin = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($maxCol);
            $sheet->getStyle("{$colLetraIni}{$fila}:{$colLetraFin}{$fila}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'A9DFBF'],
                    ],
                ],
            ]);
            $col = count($colsFijas) + 1;
            foreach ($modulos as $modulo) {
                foreach ($temasPorModulo[$modulo->Id_Modulo] as $tema) {
                    $valor = isset($recibidoMap[$ben->Id_Beneficiario][$modulo->Id_Modulo][$tema->Id_Tema]) ? $recibidoMap[$ben->Id_Beneficiario][$modulo->Id_Modulo][$tema->Id_Tema] : '';
                    $colLetra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $sheet->setCellValue("{$colLetra}{$fila}", $valor);
                    // Si hay checkmark, ponerlo en verde y centrado
                    if ($valor === '✓') {
                        $sheet->getStyle("{$colLetra}{$fila}")->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '28a745'],
                                'bold' => true,
                                'size' => 13,
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                    }
                    $col++;
                }
            }
            $fila++;
        }

        // 7. Estilos
        $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col - 1);
        $sheet->getStyle("A1:{$maxCol}2")->applyFromArray([
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
        $sheet->getDefaultRowDimension()->setRowHeight(16); // filas compactas
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(120); // suficiente para 7 filas de texto
        // Wrap text solo en la fila 2 (temas)
        $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col - 1);
        $sheet->getStyle("A2:{$maxCol}2")->getAlignment()->setWrapText(true);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "capacitaciones.xlsx";
        // Limpiar cualquier salida previa
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save("php://output");
        exit;
    }

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

}
