<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapacitacionController extends Controller
{
    // Vista principal: Selección de caja rural, módulos, temas y miembros
    public function index()
    {
        // Obtener organizaciones activas
        $organizaciones = DB::table('tbl_organizacion')->where('Estado_Organizacion', 'ACTIVO')->get();

        // Obtener beneficiarios por organización
        $beneficiariosPorOrg = [];
        foreach ($organizaciones as $org) {
            $beneficiariosPorOrg[$org->Id_Organizacion] = DB::table('tbl_beneficiario')
                ->where('Id_Organizacion', $org->Id_Organizacion)
                ->where('estado', 1)
                ->select('Id_Beneficiario', 'Nombre_Beneficiario')
                ->get();
        }

        // Obtener todos los módulos y temas desde la base de datos, sin orden específico (la vista controla el orden)
        $modulos = DB::table('tbl_modulo_capacitacion')
            ->select('Id_Modulo', 'Nombre_Modulo')
            ->get();

        $temasPorModulo = [];
        foreach ($modulos as $modulo) {
            $temasPorModulo[$modulo->Id_Modulo] = DB::table('tbl_tema_modulo')
                ->where('Id_Modulo', $modulo->Id_Modulo)
                ->select('Id_Tema', 'Nombre_Tema')
                ->orderBy('Id_Tema', 'asc')
                ->get();
        }

        return view('capacitaciones.index', compact('organizaciones', 'beneficiariosPorOrg', 'modulos', 'temasPorModulo'));
}

    // Guardar registro de capacitación (miembros, módulos, temas)
    public function store(Request $request)
    {
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
            // Nuevo formato: recibio[beneficiario_id][modulo_id][tema_id] = true
            $recibio = $request->input('recibio', []);

            foreach ($recibio as $idBeneficiario => $modulosSeleccionados) {
                if (!is_array($modulosSeleccionados)) continue;
                foreach ($modulosSeleccionados as $idModulo => $temasSeleccionados) {
                    if (!is_array($temasSeleccionados)) continue;
                    foreach ($temasSeleccionados as $idTema => $valor) {
                        if ($valor) { // Si el checkbox está marcado
                            DB::table('tbl_capacitacion_miembro')->insert([
                                'Id_Beneficiario' => $idBeneficiario,
                                'Id_Modulo' => $idModulo,
                                'Id_Tema' => $idTema,
                                'Recibido' => true,
                                'Fecha' => $request->Fecha
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('capacitacion.index')->with('success', 'Registro guardado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }

    }

    // Busca el módulo y tema por el índice de la fila en la tabla
    // Ya no se requiere buscar por índice, ahora se usan los IDs directamente
    }

