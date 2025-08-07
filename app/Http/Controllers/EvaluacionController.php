<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;
use App\Models\Organizacion;
use App\Models\EvaluacionActualizada;
use App\Models\Objeto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
class EvaluacionController extends Controller
{
    public function create()
    {
        // Verificar permisos para crear evaluaciones
        if (!auth()->user() || !auth()->user()->tienePermiso('Evaluacion', 'Insercion')) {
            return redirect()->back()->with('error', 'No tiene permisos para crear evaluaciones');
        }

        // Registrar acceso a creación de evaluaciones en bitácora
        $objeto = Objeto::where('Objeto', 'Evaluacion')->first();
        if (!$objeto) {
            // Si no existe el objeto "Evaluacion", usar uno genérico o crear referencia
            $objeto = Objeto::where('Objeto', 'Dashboard')->first();
        }
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario accedió a la creación de evaluaciones'
            );
        }

         $organizaciones = Organizacion::whereDoesntHave('evaluacion')->get();
        return view('evaluacion.create', compact('organizaciones'));
    }

    public function store(Request $request)
    {
        // Verificar permisos para crear evaluaciones
        if (!auth()->user() || !auth()->user()->tienePermiso('Evaluacion', 'Insercion')) {
            return redirect()->back()->with('error', 'No tiene permisos para crear evaluaciones');
        }

        $validated = $request->validate([
           'id_organizacion' => 'required|unique:tbl_evaluaciones,id_organizacion',
            'eficiencia_financiera' => 'required|in:mayor,menor',
            'apalancamiento' => 'required|in:mayor_60,30_60,menor_30',
            'sostenibilidad' => 'required|in:mayor_1,igual_1,menor_1',
            'calidad_cartera' => 'required|in:mayor_10,8_10,5_8,3_5,0_3',
        ]);

        $data = $request->all();
        $total_organizacion = 0;
        $total_gestion = 0;

        // Cálculo institucional (si está activa la evaluación completa)
        if (!empty($data['mayor_seis_meses'])) {
            $total_organizacion += !empty($data['personeria_juridica']) ? 30 : 0;
            $total_organizacion += !empty($data['personeria_tramite']) ? 10 : 0;
            $total_organizacion += !empty($data['rtn']) ? 20 : 0;
            $total_organizacion += match($data['frecuencia_reunion'] ?? '') {
                'quincenal' => 3,
                'mensual' => 1,
                default => 0,
            };
            $total_organizacion += !empty($data['actas_sesion']) ? 3 : 0;
            $total_organizacion += !empty($data['plan_trabajo']) ? 3 : 0;
            $total_organizacion += !empty($data['estatutos']) ? 3 : 0;
            $total_organizacion += !empty($data['aplican_estatutos']) ? 3 : 0;
            $total_organizacion += !empty($data['libros_contables']) ? 12.5 : 0;
            $total_organizacion += !empty($data['informes_financieros']) ? 12.5 : 0;
            $total_organizacion += !empty($data['actas_credito']) ? 5 : 0;
            $total_organizacion += !empty($data['gestion_reglamento']) ? 5 : 0;
            $total_organizacion += !empty($data['actas_fiscalizadora']) ? 5 : 0;
            $total_organizacion += !empty($data['informes_fiscalizadora']) ? 5 : 0;
        }

        $total_gestion += !empty($data['libro_prestamos']) ? 20 : 0;
        $total_gestion += !empty($data['libro_ahorros']) ? 20 : 0;
        $total_gestion += !empty($data['libro_caja']) ? 20 : 0;
        $total_gestion += !empty($data['libro_aportaciones']) ? 20 : 0;
        $total_gestion += !empty($data['libro_ahorros_prestamos']) ? 20 : 0;
        $total_gestion += !empty($data['libros_actas']) ? 20 : 0;
        $total_gestion += !empty($data['formulario_solicitud']) ? 15 : 0;
        $total_gestion += !empty($data['exigencia_garantias']) ? 15 : 0;
        $total_gestion += !empty($data['dictamen_credito']) ? 15 : 0;
        $total_gestion += !empty($data['pagare']) ? 15 : 0;
        $total_gestion += !empty($data['letra_cambio']) ? 15 : 0;

        $desempeno_institucional = $total_organizacion + $total_gestion;

        // Cálculo financiero
        $descalificado = false;
        $total_financiero = 0;

        $data['apalancamiento_financiero'] = match($data['apalancamiento']) {
            'mayor_60' => 0,
            '30_60' => 100,
            'menor_30' => 50,
        };

        $data['sostenibilidad_financiera'] = match($data['sostenibilidad']) {
            'mayor_1' => 100,
            'igual_1' => 50,
            'menor_1' => 0,
        };

        if ($data['eficiencia_financiera'] === 'mayor') {
            $total_financiero += 100;
        }

        $total_financiero += $data['apalancamiento_financiero'];
        $total_financiero += $data['sostenibilidad_financiera'];

        $total_financiero += match($data['calidad_cartera']) {
            'mayor_10' => $descalificado = true ? 0 : 0,
            '8_10' => 20,
            '5_8' => 30,
            '3_5' => 50,
            '0_3' => 100,
        };

        if ($data['calidad_cartera'] === 'mayor_10') {
            $descalificado = true;
            $total_financiero = 0;
        }

        $calificacion_total = $desempeno_institucional + $total_financiero;

        $categoria = $descalificado ? 'D' :
            ($calificacion_total >= 270 ? 'A' :
            ($calificacion_total >= 240 ? 'B' :
            ($calificacion_total >= 200 ? 'C' : 'D')));

        $evaluacion = Evaluacion::create([
            ...$data,
            'total_organizacion' => $total_organizacion,
            'total_gestion' => $total_gestion,
            'total_componentes' => $total_organizacion + $total_gestion,
            'desempeno_institucional' => $desempeno_institucional,
            'total_financiero' => $total_financiero,
            'calificacion_total' => $calificacion_total,
            'categoria' => $categoria,
        ]);

        // Registrar creación de evaluación en bitácora
        $objeto = Objeto::where('Objeto', 'Evaluacion')->first();
        if (!$objeto) {
            $objeto = Objeto::where('Objeto', 'Dashboard')->first();
        }
        if ($objeto && Auth::check()) {
            $organizacion = Organizacion::find($data['id_organizacion']);
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                "Creó una evaluación para la organización: {$organizacion->Nombre_Organizacion} con categoría: {$categoria}"
            );
        }

        // Guardar evaluación estática (congelada)
        EvaluacionActualizada::create([
            'evaluacion_id' => $evaluacion->Id_Evaluacion,
            'id_organizacion' => $data['id_organizacion'],
            'total_organizacion' => $total_organizacion,
            'total_gestion' => $total_gestion,
            'total_componentes' => $total_organizacion + $total_gestion,
            'desempeno_institucional' => $desempeno_institucional,
            'eficiencia_financiera' => $data['eficiencia_financiera'],
            'apalancamiento_financiero' => $data['apalancamiento_financiero'],
            'sostenibilidad_financiera' => $data['sostenibilidad_financiera'],
            'calidad_cartera' => $data['calidad_cartera'],
            'total_financiero' => $total_financiero,
            'calificacion_total' => $calificacion_total,
            'categoria' => $categoria,
        ]);

        return redirect()->route('evaluacion.index')->with('success', 'Evaluación guardada correctamente.');
    }


public function exportPdf(Request $request)
{
    $evaluaciones = Evaluacion::with(['organizacion.aldea.municipio.departamento'])
        ->when($request->input('search'), function ($query) use ($request) {
            $query->whereHas('organizacion', function ($q) use ($request) {
                $q->where('Nombre_Organizacion', 'like', '%' . $request->search . '%');
            });
        })
        ->get();

    $actualizadas = EvaluacionActualizada::with('organizacion.aldea.municipio.departamento')
        ->when($request->input('search'), function ($query) use ($request) {
            $query->whereHas('organizacion', function ($q) use ($request) {
                $q->where('Nombre_Organizacion', 'like', '%' . $request->search . '%');
            });
        })
        ->get();

    // Transformaciones opcionales para mostrar porcentajes y categorías
    $evaluaciones->transform(function ($eval) {
        $eval->porcentaje_institucional = min(100, round(($eval->desempeno_institucional / 315) * 100, 2));
        $eval->porcentaje_financiero = min(100, round(($eval->total_financiero / 400) * 100, 2));
        $eval->calificacion_total_pct = round($eval->porcentaje_institucional + $eval->porcentaje_financiero, 2);
        $total = $eval->calificacion_total_pct;
        $eval->categoria_calculada = match (true) {
            $total >= 90 => 'A',
            $total >= 71 => 'B',
            $total >= 50 => 'C',
            default => 'D',
        };
        return $eval;
    });

    // Cargar vista y generar PDF
    $pdf = pdf::loadView('evaluacion.reporte_pdf', [
        'evaluaciones' => $evaluaciones,
        'actualizadas' => $actualizadas,
        'pdf' => true,
    ]);
    $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
    $pdf->getDomPDF()->set_option('isPhpEnabled', true);
    return $pdf->stream('reporte_evaluaciones.pdf');
}

public function index(Request $request)
{
    $search = $request->input('search');

    // Evaluaciones iniciales con filtro por nombre de organización
    $evaluaciones = Evaluacion::with(['organizacion.aldea.municipio.departamento', 'actualizada'])
        ->when($search, function ($query) use ($search) {
            $query->whereHas('organizacion', function ($q) use ($search) {
                $q->where('Nombre_Organizacion', 'like', '%' . $search . '%');
            });
        })
        ->get();

    // Evaluaciones actualizadas con mismo filtro
    $actualizadas = EvaluacionActualizada::with(['organizacion.aldea.municipio.departamento'])
        ->when($search, function ($query) use ($search) {
            $query->whereHas('organizacion', function ($q) use ($search) {
                $q->where('Nombre_Organizacion', 'like', '%' . $search . '%');
            });
        })
        ->get();

    // Transformación de datos
    $evaluaciones->transform(function ($eval) {
        $eval->porcentaje_institucional = min(100, round(($eval->desempeno_institucional / 315) * 100, 2));
        $eval->porcentaje_financiero = min(100, round(($eval->total_financiero / 400) * 100, 2));
        $eval->calificacion_total_pct = round($eval->porcentaje_institucional + $eval->porcentaje_financiero, 2);

        $total = $eval->calificacion_total_pct;
        $eval->categoria_calculada = match (true) {
            $total >= 90 => 'A',
            $total >= 71 => 'B',
            $total >= 50 => 'C',
            default => 'D',
        };

        return $eval;
    });

    return view('evaluacion.index', compact('evaluaciones', 'actualizadas'));
}


    public function edit($id)
    {
        $evaluacion = Evaluacion::with('organizacion')->findOrFail($id);
        $organizaciones = Organizacion::all();
        return view('evaluacion.edit', compact('evaluacion', 'organizaciones'));
    }
public function update(Request $request, $id)
{
    $evaluacion = Evaluacion::findOrFail($id);
    $data = $request->all();

    // Recalcular total_organizacion
    $total_organizacion = 0;
    if (!empty($data['mayor_seis_meses'])) {
        $total_organizacion += !empty($data['personeria_juridica']) ? 30 : 0;
        $total_organizacion += !empty($data['personeria_tramite']) ? 10 : 0;
        $total_organizacion += !empty($data['rtn']) ? 20 : 0;
        $total_organizacion += match($data['frecuencia_reunion'] ?? '') {
            'quincenal' => 3,
            'mensual' => 1,
            default => 0,
        };
        $total_organizacion += !empty($data['actas_sesion']) ? 3 : 0;
        $total_organizacion += !empty($data['plan_trabajo']) ? 3 : 0;
        $total_organizacion += !empty($data['estatutos']) ? 3 : 0;
        $total_organizacion += !empty($data['aplican_estatutos']) ? 3 : 0;
        $total_organizacion += !empty($data['libros_contables']) ? 12.5 : 0;
        $total_organizacion += !empty($data['informes_financieros']) ? 12.5 : 0;
        $total_organizacion += !empty($data['actas_credito']) ? 5 : 0;
        $total_organizacion += !empty($data['gestion_reglamento']) ? 5 : 0;
        $total_organizacion += !empty($data['actas_fiscalizadora']) ? 5 : 0;
        $total_organizacion += !empty($data['informes_fiscalizadora']) ? 5 : 0;
    }

    // Recalcular total_gestion
    $total_gestion = 0;
    $total_gestion += !empty($data['libro_prestamos']) ? 20 : 0;
    $total_gestion += !empty($data['libro_ahorros']) ? 20 : 0;
    $total_gestion += !empty($data['libro_caja']) ? 20 : 0;
    $total_gestion += !empty($data['libro_aportaciones']) ? 20 : 0;
    $total_gestion += !empty($data['libro_ahorros_prestamos']) ? 20 : 0;
    $total_gestion += !empty($data['libros_actas']) ? 20 : 0;
    $total_gestion += !empty($data['formulario_solicitud']) ? 15 : 0;
    $total_gestion += !empty($data['exigencia_garantias']) ? 15 : 0;
    $total_gestion += !empty($data['dictamen_credito']) ? 15 : 0;
    $total_gestion += !empty($data['pagare']) ? 15 : 0;
    $total_gestion += !empty($data['letra_cambio']) ? 15 : 0;

    $total_componentes = $total_organizacion + $total_gestion;
    $desempeno_institucional = $total_componentes;

    // Recalcular financiero
    $descalificado = false;
    $total_financiero = 0;

    $data['apalancamiento_financiero'] = match($data['apalancamiento'] ?? '') {
        'mayor_60' => 0,
        '30_60' => 100,
        'menor_30' => 50,
        default => 0,
    };

    $data['sostenibilidad_financiera'] = match($data['sostenibilidad'] ?? '') {
        'mayor_1' => 100,
        'igual_1' => 50,
        'menor_1' => 0,
        default => 0,
    };

    if (($data['eficiencia_financiera'] ?? '') === 'mayor') {
        $total_financiero += 100;
    }

    $total_financiero += $data['apalancamiento_financiero'];
    $total_financiero += $data['sostenibilidad_financiera'];

    $total_financiero += match($data['calidad_cartera'] ?? '') {
        'mayor_10' => $descalificado = true ? 0 : 0,
        '8_10' => 20,
        '5_8' => 30,
        '3_5' => 50,
        '0_3' => 100,
        default => 0,
    };

    if (($data['calidad_cartera'] ?? '') === 'mayor_10') {
        $descalificado = true;
        $total_financiero = 0;
    }

    $calificacion_total = $desempeno_institucional + $total_financiero;

    $categoria = $descalificado ? 'D' :
        ($calificacion_total >= 270 ? 'A' :
        ($calificacion_total >= 240 ? 'B' :
        ($calificacion_total >= 200 ? 'C' : 'D')));

    // Actualizar evaluación
    $evaluacion->update([
        ...$data,
        'total_organizacion' => $total_organizacion,
        'total_gestion' => $total_gestion,
        'total_componentes' => $total_componentes,
        'desempeno_institucional' => $desempeno_institucional,
        'total_financiero' => $total_financiero,
        'calificacion_total' => $calificacion_total,
        'categoria' => $categoria,
    ]);
    $evaluacion->touch();
    // Actualizar EvaluacionActualizada si existe
    if ($evaluacion->evaluacionActualizada) {
        $evaluacion->evaluacionActualizada->update([
            'total_organizacion' => $total_organizacion,
            'total_gestion' => $total_gestion,
            'total_componentes' => $total_componentes,
            'desempeno_institucional' => $desempeno_institucional,
            'eficiencia_financiera' => $data['eficiencia_financiera'] ?? null,
            'apalancamiento_financiero' => $data['apalancamiento_financiero'],
            'sostenibilidad_financiera' => $data['sostenibilidad_financiera'],
            'calidad_cartera' => $data['calidad_cartera'] ?? null,
            'total_financiero' => $total_financiero,
            'calificacion_total' => $calificacion_total,
            'categoria' => $categoria,
        ]);
    }
    
    return redirect()->route('evaluacion.index')->with('success', 'Evaluación actualizada correctamente.');
}

public function destroy($id)
{
    // Elimina primero cualquier evaluación actualizada relacionada
    EvaluacionActualizada::where('evaluacion_id', $id)->delete();

    // Luego elimina la evaluación principal
    Evaluacion::destroy($id);

    return redirect()->route('evaluacion.index')->with('success', 'Evaluación eliminada correctamente.');
}
}
