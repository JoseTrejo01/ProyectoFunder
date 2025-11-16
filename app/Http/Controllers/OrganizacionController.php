<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Organizacion;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Aldea;
use App\Models\CoordenadaMunicipio;
use Barryvdh\DomPDF\Facade\Pdf;

class OrganizacionController extends Controller
{
    public function index()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar organizaciones.');
        }

        $objeto = \App\Models\Objeto::where('Objeto', 'Organizaciones')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(auth()->user()->Id_Usuario, $objeto->Id_Objeto, 'Ingreso', 'El usuario ingresó a la gestión de organizaciones');
        }

        $user = auth()->user();
        $rol = $user->rol->Rol ?? null;
        $organizaciones = ($rol === 'TECNICO DE CAMPO')
            ? Organizacion::where('Estado_Organizacion', 'ACTIVO')->with(['aldea.municipio.departamento'])->get()
            : Organizacion::with(['aldea.municipio.departamento'])->get();

        $departamentos = Departamento::all();
        $municipios = Municipio::all();
        $municipiosPorDepto = $municipios->groupBy('Id_Departamento');

        $coordenadas = DB::table('tbl_coordenadas_municipio')
            ->select('Id_Municipio', 'coordenada_x', 'coordenada_y')
            ->get()
            ->keyBy('Id_Municipio');

        return view('organizaciones.index', compact('organizaciones', 'departamentos', 'municipiosPorDepto', 'coordenadas'));
    }

    public function store(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Insercion')) {
            abort(403, 'No tienes permiso para crear organizaciones.');
        }

        $request->validate([
            'Nombre_Organizacion' => 'required|string|max:100',
            'departamento' => 'required|exists:tbl_departamento,Id_Departamento',
            'municipio' => 'required|exists:tbl_municipio,Id_Municipio',
            'Nombre_Aldea' => 'required|string|max:60',
            'coordenada_x' => 'required|numeric',
            'coordenada_y' => 'required|numeric',
            'tiene_personeria_juridica' => 'required|boolean',
            'fecha_personeria_juridica' => 'nullable|date|required_if:tiene_personeria_juridica,1',
            'tiene_rtn' => 'required|boolean',
            'rtn' => 'nullable|string|max:20|required_if:tiene_rtn,1',
            'tiene_cuenta_bancaria' => 'required|boolean',
        ]);

        $aldea = Aldea::firstOrCreate([
            'Nombre_Aldea' => $request->Nombre_Aldea,
            'Id_Municipio' => $request->municipio,
        ]);

        $org = Organizacion::create([
            'Id_Aldea' => $aldea->Id_Aldea,
            'Nombre_Organizacion' => $request->Nombre_Organizacion,
            'Estado_Organizacion' => 'ACTIVO',
            'Id_Usuario' => auth()->id() ?? 1,
            'tiene_personeria_juridica' => $request->tiene_personeria_juridica,
            'fecha_personeria_juridica' => $request->tiene_personeria_juridica ? $request->fecha_personeria_juridica : null,
            'tiene_rtn' => $request->tiene_rtn,
            'rtn' => $request->tiene_rtn ? $request->rtn : null,
            'tiene_cuenta_bancaria' => $request->tiene_cuenta_bancaria,
        ]);

        $objeto = \App\Models\Objeto::where('Objeto', 'Organizaciones')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Creó una nueva organización: ' . $org->Nombre_Organizacion
            );
        }

        DB::table('tbl_coordenadas_municipio')->updateOrInsert(
            [
                'Id_Municipio' => $request->municipio,
                'Id_Departamento' => $request->departamento
            ],
            [
                'coordenada_x' => $request->coordenada_x,
                'coordenada_y' => $request->coordenada_y
            ]
        );

        return redirect()->route('organizaciones.index')->with('success', 'Organización registrada correctamente');
    }

    public function edit($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Actualizacion')) {
            abort(403, 'No tienes permiso para editar organizaciones.');
        }

        $organizacion = Organizacion::findOrFail($id);
        $departamentos = Departamento::all();
        $municipiosPorDepto = Municipio::all()->groupBy('Id_Departamento');

        return view('organizaciones.edit', compact('organizacion', 'departamentos', 'municipiosPorDepto'));
    }

    /**
     * Función para inactivar una organización.
     * MODIFICADA: Tipo de acción en bitácora cambiado a 'Eliminación'.
     */
    public function destroy($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Eliminacion')) {
            abort(403, 'No tienes permiso para eliminar organizaciones.');
        }

        $org = Organizacion::findOrFail($id);
        $org->Estado_Organizacion = 'INACTIVO';
        $org->save();

        $objeto = \App\Models\Objeto::where('Objeto', 'Organizaciones')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(auth()->user()->Id_Usuario, $objeto->Id_Objeto, 'Eliminación', 'Inactivó la organización: ' . $org->Nombre_Organizacion);
        }

        return redirect()->route('organizaciones.index')->with('success', 'Organización inactivada correctamente');
    }

    /**
     * Función para actualizar los datos de una organización.
     * CORREGIDA: Incluye la actualización de coordenadas y la bitácora detallada.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Actualizacion')) {
            abort(403, 'No tienes permiso para actualizar organizaciones.');
        }

        $request->validate([
            'Nombre_Organizacion' => 'required|string|max:100',
            'departamento' => 'required|exists:tbl_departamento,Id_Departamento',
            'municipio' => 'required|exists:tbl_municipio,Id_Municipio',
            'Nombre_Aldea' => 'required|string|max:60',
            // ⭐️ Se añaden validaciones para las coordenadas
            'coordenada_x' => 'required|numeric', 
            'coordenada_y' => 'required|numeric', 
            'Estado_Organizacion' => 'required|in:ACTIVO,INACTIVO',
            'tiene_personeria_juridica' => 'required|boolean',
            'fecha_personeria_juridica' => 'nullable|date|required_if:tiene_personeria_juridica,1',
            'tiene_rtn' => 'required|boolean',
            'rtn' => 'nullable|string|max:20|required_if:tiene_rtn,1',
            'tiene_cuenta_bancaria' => 'required|boolean',
        ]);

        $org = Organizacion::findOrFail($id);
        $nombreOrgAnterior = $org->Nombre_Organizacion; // Guardamos el nombre anterior para la bitácora

        $aldea = Aldea::firstOrCreate([
            'Nombre_Aldea' => $request->Nombre_Aldea,
            'Id_Municipio' => $request->municipio,
        ]);

        // 1. ACTUALIZACIÓN DE LA ORGANIZACIÓN
        $org->Id_Aldea = $aldea->Id_Aldea;
        $org->Nombre_Organizacion = $request->Nombre_Organizacion;
        $org->Estado_Organizacion = $request->Estado_Organizacion;
        $org->tiene_personeria_juridica = $request->tiene_personeria_juridica;
        $org->fecha_personeria_juridica = $request->tiene_personeria_juridica ? $request->fecha_personeria_juridica : null;
        $org->tiene_rtn = $request->tiene_rtn;
        $org->rtn = $request->tiene_rtn ? $request->rtn : null;
        $org->tiene_cuenta_bancaria = $request->tiene_cuenta_bancaria;
        $org->save();

        // 2. ⭐️ ACTUALIZACIÓN DE COORDENADAS (Solución para la base de datos)
        DB::table('tbl_coordenadas_municipio')->updateOrInsert(
            [
                'Id_Municipio' => $request->municipio,
                'Id_Departamento' => $request->departamento
            ],
            [
                'coordenada_x' => $request->coordenada_x,
                'coordenada_y' => $request->coordenada_y
            ]
        );

        // 3. ⭐️ REGISTRO EN LA BITÁCORA (Solución para la bitácora)
        $objeto = \App\Models\Objeto::where('Objeto', 'Organizaciones')->first();
        if ($objeto && auth()->check()) {
            $descripcion = 'Actualizó la organización ID: ' . $id . '. De "' . $nombreOrgAnterior . '" a "' . $org->Nombre_Organizacion . '". Estado: ' . $org->Estado_Organizacion . '.';
            
            EVENT_BITACORA(
                auth()->user()->Id_Usuario, 
                $objeto->Id_Objeto, 
                'Actualización', 
                $descripcion
            );
        }
        // ⭐️ FIN REGISTRO EN LA BITÁCORA

        return redirect()->route('organizaciones.index')->with('success', 'Organización actualizada correctamente');
    }

    public function vistaMapa()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar organizaciones.');
        }

        $organizaciones = Organizacion::with([
            'aldea.municipio.coordenada',
            'aldea.municipio.departamento'
        ])->withCount('socios')->get();

        $departamentos = Departamento::all();
        $municipios = Municipio::all();

        $municipiosPorDepto = $municipios->groupBy('Id_Departamento')->map(function ($group) {
            return $group->map(function ($muni) {
                return [
                    'Id_Municipio' => $muni->Id_Municipio,
                    'Nombre_Municipio' => $muni->Nombre_Municipio
                ];
            })->values();
        });

        return view('organizaciones.mapa', compact('organizaciones', 'departamentos', 'municipios', 'municipiosPorDepto'));
    }

    public function obtenerCajasConSocios()
    {
        $cajas = Organizacion::withCount('socios')
            ->with(['aldea.municipio.departamento'])
            ->get()
            ->map(function ($caja) {
                return [
                    'nombre' => $caja->Nombre_Organizacion,
                    'aldea' => optional($caja->aldea)->Nombre_Aldea,
                    'municipio' => optional($caja->aldea->municipio)->Nombre_Municipio ?? '',
                    'departamento' => optional($caja->aldea->municipio->departamento)->Nombre_Departamento ?? '',
                    'estado' => $caja->Estado_Organizacion,
                    'lat' => optional(optional($caja->aldea)->municipio)->coordenada->coordenada_y,
                    'lng' => optional(optional($caja->aldea)->municipio)->coordenada->coordenada_x,
                    'total_socios' => $caja->socios_count,
                ];
            });

        return response()->json($cajas);
    }

    public function exportarPDF()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Organizaciones', 'Consultar')) {
            abort(403, 'No tienes permiso para exportar organizaciones.');
        }

        $organizaciones = Organizacion::where('Estado_Organizacion', 'ACTIVO')
                                     ->with(['aldea.municipio.departamento'])
                                     ->get();

        $pdf = Pdf::loadView('organizaciones.pdf', [
            'organizaciones' => $organizaciones,
            'pdf' => true, 
        ])
             ->setPaper('a4', 'landscape');
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isPhpEnabled', true);

        // Registrar en bitácora
        $objeto = \App\Models\Objeto::where('Objeto', 'Organizaciones')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Reporte',
                'Exportó reporte PDF de organizaciones'
            );
        }

        return $pdf->download('reporte_organizaciones.pdf');
    }
}