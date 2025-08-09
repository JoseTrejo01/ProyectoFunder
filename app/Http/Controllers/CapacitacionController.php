<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Objeto;
use App\Models\Organizacion;
use App\Models\Socio;
use Illuminate\Support\Facades\Auth;

class CapacitacionController extends Controller
{
    // Vista principal: Selección de caja rural, módulos, temas y miembros
    public function index()
    {
        try {
            // Verificar permisos para consultar capacitaciones
            if (!auth()->user() || !auth()->user()->tienePermiso('Capacitacion', 'Consultar')) {
                return redirect()->back()->with('error', 'No tiene permisos para consultar capacitaciones');
            }

            // Registrar acceso a gestión de capacitaciones en bitácora
            $objeto = Objeto::where('Objeto', 'Capacitacion')->first();
            if ($objeto && Auth::check()) {
                EVENT_BITACORA(
                    Auth::user()->Id_Usuario,
                    $objeto->Id_Objeto,
                    'Ingreso',
                    'El usuario accedió a la gestión de capacitaciones'
                );
            }

            // Obtener organizaciones usando consulta directa
            $organizaciones = DB::table('tbl_organizacion')
                ->where('Estado_Organizacion', 'ACTIVO')
                ->select('Id_Organizacion', 'Nombre_Organizacion')
                ->orderBy('Nombre_Organizacion', 'asc')
                ->get();
            
            \Log::info('Organizaciones obtenidas:', [
                'count' => $organizaciones->count(),
                'sample' => $organizaciones->take(3)->pluck('Nombre_Organizacion', 'Id_Organizacion')->toArray()
            ]);

            // Obtener beneficiarios por organización
            $beneficiariosPorOrg = [];
            foreach ($organizaciones as $org) {
                $beneficiarios = DB::table('tbl_beneficiario')
                    ->where('Id_Organizacion', $org->Id_Organizacion)
                    ->select('Id_Beneficiario', 'Nombre_Beneficiario')
                    ->orderBy('Nombre_Beneficiario', 'asc')
                    ->get()
                    ->toArray();
                
                $beneficiariosPorOrg[$org->Id_Organizacion] = $beneficiarios;
                
                \Log::info("Beneficiarios para {$org->Nombre_Organizacion}:", [
                    'count' => count($beneficiarios),
                    'sample' => array_slice($beneficiarios, 0, 2)
                ]);
            }

            // Obtener todos los módulos y temas desde la base de datos
            $modulos = DB::table('tbl_modulo_capacitacion')
                ->select('Id_Modulo', 'Nombre_Modulo')
                ->orderBy('Nombre_Modulo', 'asc')
                ->get();

            $temasPorModulo = [];
            foreach ($modulos as $modulo) {
                $temasPorModulo[$modulo->Id_Modulo] = DB::table('tbl_tema_modulo')
                    ->where('Id_Modulo', $modulo->Id_Modulo)
                    ->select('Id_Tema', 'Nombre_Tema')
                    ->orderBy('Nombre_Tema', 'asc')
                    ->get();
            }

            \Log::info('Datos cargados correctamente para capacitaciones:', [
                'organizaciones' => $organizaciones->count(),
                'modulos' => $modulos->count(),
                'beneficiarios_total' => array_sum(array_map('count', $beneficiariosPorOrg))
            ]);

            return view('capacitaciones.index', compact('organizaciones', 'beneficiariosPorOrg', 'modulos', 'temasPorModulo'));
            
        } catch (\Exception $e) {
            \Log::error('Error en CapacitacionController@index:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error al cargar la página de capacitaciones: ' . $e->getMessage());
        }
    }

    // Guardar registro de capacitación (miembros, módulos, temas)
    public function store(Request $request)
    {
        // Verificar permisos para crear capacitaciones
        if (!auth()->user() || !auth()->user()->tienePermiso('Capacitacion', 'Insercion')) {
            return redirect()->back()->with('error', 'No tiene permisos para crear capacitaciones');
        }

        $request->validate([
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'Fecha' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Buscar si ya existe una capacitación para esa organización y fecha
            $capacitacion = DB::table('tbl_capacitacion')
                ->where('Id_Organizacion', $request->Id_Organizacion)
                ->where('Fecha', $request->Fecha)
                ->first();

            if ($capacitacion) {
                $idCapacitacion = $capacitacion->Id_Capacitacion;
            } else {
                $idCapacitacion = DB::table('tbl_capacitacion')->insertGetId([
                    'Id_Organizacion' => $request->Id_Organizacion,
                    'Fecha' => $request->Fecha,
                    'Nombre' => 'Capacitación',
                ]);
            }

            // Usar los módulos y temas existentes en la base
            $modulos = DB::table('tbl_modulo_capacitacion')->select('Id_Modulo', 'Nombre_Modulo')->get();
            $temasPorModulo = [];
            foreach ($modulos as $modulo) {
                $temasPorModulo[$modulo->Id_Modulo] = DB::table('tbl_tema_modulo')
                    ->where('Id_Modulo', $modulo->Id_Modulo)
                    ->select('Id_Tema', 'Nombre_Tema')
                    ->get();
            }

            // Procesar los checkboxes recibidos
            // Formato esperado: recibio[beneficiario_id][modulo_id][tema_id] = 1
            $recibio = $request->input('recibio', []);
            
            // Debug: Log de los datos recibidos
            \Log::info('Datos recibio recibidos:', [
                'recibio' => $recibio,
                'count' => count($recibio)
            ]);

            $registrosInsertados = 0;
            foreach ($recibio as $idBeneficiario => $modulosSeleccionados) {
                if (!is_array($modulosSeleccionados)) continue;
                
                \Log::info("Procesando beneficiario {$idBeneficiario}:", [
                    'modulos' => array_keys($modulosSeleccionados)
                ]);
                
                foreach ($modulosSeleccionados as $idModulo => $temasSeleccionados) {
                    if (!is_array($temasSeleccionados)) continue;
                    
                    foreach ($temasSeleccionados as $idTema => $valor) {
                        if ($valor) { // Si el checkbox está marcado
                            $registro = [
                                'Id_Beneficiario' => $idBeneficiario,
                                'Id_Modulo' => $idModulo,
                                'Id_Tema' => $idTema,
                                'Recibido' => true,
                                'Fecha' => $request->Fecha
                            ];
                            
                            \Log::info('Insertando registro:', $registro);
                            
                            DB::table('tbl_capacitacion_miembro')->insert($registro);
                            $registrosInsertados++;
                        }
                    }
                }
            }
            
            \Log::info("Total registros insertados en tbl_capacitacion_miembro: {$registrosInsertados}");

            DB::commit();

            // Registrar creación de capacitación en bitácora
            $objeto = Objeto::where('Objeto', 'Capacitacion')->first();
         
            if ($objeto && Auth::check()) {
                $organizacion = DB::table('tbl_organizacion')
                    ->where('Id_Organizacion', $request->Id_Organizacion)
                    ->first();
                    
                EVENT_BITACORA(
                    Auth::user()->Id_Usuario,
                    $objeto->Id_Objeto,
                    'Nuevo',
                    "Registró una capacitación para la organización: {$organizacion->Nombre_Organizacion} en fecha: {$request->Fecha}"
                );
            }

            return redirect()->route('capacitacion.index')->with('success', 'Registro guardado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    // Obtener reporte de capacitaciones por socio
    public function getReporte(Request $request)
    {
        try {
            // Consulta base para obtener datos de capacitaciones
            $query = DB::table('tbl_capacitacion_miembro as cm')
                ->join('tbl_beneficiario as b', 'cm.Id_Beneficiario', '=', 'b.Id_Beneficiario')
                ->join('tbl_organizacion as o', 'b.Id_Organizacion', '=', 'o.Id_Organizacion')
                ->join('tbl_modulo_capacitacion as m', 'cm.Id_Modulo', '=', 'm.Id_Modulo')
                ->join('tbl_tema_modulo as t', 'cm.Id_Tema', '=', 't.Id_Tema')
                ->select(
                    'o.Nombre_Organizacion as caja_rural',
                    'b.Nombre_Beneficiario as beneficiario',
                    'cm.Fecha',
                    'm.Nombre_Modulo as modulo',
                    't.Nombre_Tema as tema'
                )
                ->where('cm.Recibido', 1); // Solo temas que realmente recibieron

            // Aplicar filtros
            if ($request->filled('organizacion')) {
                $query->where('b.Id_Organizacion', $request->organizacion);
            }

            if ($request->filled('fecha_inicio')) {
                $query->where('cm.Fecha', '>=', $request->fecha_inicio);
            }

            if ($request->filled('fecha_fin')) {
                $query->where('cm.Fecha', '<=', $request->fecha_fin);
            }

            $resultados = $query->orderBy('o.Nombre_Organizacion')
                ->orderBy('b.Nombre_Beneficiario')
                ->orderBy('cm.Fecha')
                ->get();

            // Agrupar datos por beneficiario
            $reporteAgrupado = [];

            foreach ($resultados as $item) {
                $key = $item->caja_rural . '|' . $item->beneficiario;
                
                if (!isset($reporteAgrupado[$key])) {
                    $reporteAgrupado[$key] = [
                        'caja_rural' => $item->caja_rural,
                        'beneficiario' => $item->beneficiario,
                        'fechas' => [],
                        'modulos' => [],
                        'total_temas' => 0
                    ];
                }

                // Agregar fecha si no existe
                if (!in_array($item->Fecha, $reporteAgrupado[$key]['fechas'])) {
                    $reporteAgrupado[$key]['fechas'][] = $item->Fecha;
                }

                // Agregar módulo si no existe
                if (!in_array($item->modulo, $reporteAgrupado[$key]['modulos'])) {
                    $reporteAgrupado[$key]['modulos'][] = $item->modulo;
                }

                // Incrementar contador de temas
                $reporteAgrupado[$key]['total_temas']++;
            }

            // Convertir a array indexado
            $datosReporte = array_values($reporteAgrupado);

            // Implementar paginación manual
            $page = $request->input('page', 1);
            $perPage = 10; // Registros por página
            $total = count($datosReporte);
            $offset = ($page - 1) * $perPage;
            
            // Obtener los elementos de la página actual
            $datosParaPagina = array_slice($datosReporte, $offset, $perPage);
            
            // Calcular información de paginación
            $pagination = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'from' => $total > 0 ? $offset + 1 : 0,
                'to' => min($offset + $perPage, $total)
            ];

            return response()->json([
                'success' => true,
                'data' => $datosParaPagina,
                'pagination' => $pagination
            ]);

        } catch (\Exception $e) {
            \Log::error('Error obteniendo reporte de capacitaciones:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte'
            ], 500);
        }
    }
}

