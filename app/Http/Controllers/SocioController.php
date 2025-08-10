<?php
namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;



class SocioController extends Controller
{
    // listar
    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Socios / Clientes', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar socios/clientes.');
        }
        $query = Socio::query()
            ->when(
                $request->filled('estado'),
                fn($q) => $q->where('estado', $request->estado),
                fn($q) => $q->where('estado', 1) // por defecto activos
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nombre_Beneficiario', 'like', "%$search%")
                  ->orWhere('DNI', 'like', "%$search%")
                  ->orWhere('Telefono', 'like', "%$search%") ;
            });
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('localidad')) {
            $query->where('comunidad', 'like', "%{$request->localidad}%");
        }

        if ($request->filled('tipo')) {
            $query->where('Tipo_De_Socio', 'like', "%{$request->tipo}%");
        }
        if ($request->filled('departamento')) {
    $query->where('departamento', $request->departamento);
}

        if ($request->filled('estado_civil')) {
            $query->where('estado_civil', $request->estado_civil);
        }

        if ($request->filled('nivel_educativo')) {
            $query->where('nivel_educativo', $request->nivel_educativo);
        }
        if ($request->filled('edad')) {
    $query->where('edad', $request->edad);
}


        $socios = $query->with(['actividades', 'organizacion'])->paginate(50); // Aumentado a 50 registros por página

        $objeto = \App\Models\Objeto::where('Objeto', 'Socios / Clientes')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario ingresó a la gestión de socios/clientes'
            );
        }

        // Lista de cargos directivos válidos para selects
        $cargosDirectivos = [
            'Presidente(a)',
            'vicepresidente(a)',
            'Tesorero(a)',
            'Secretario(a)',
            'Vocal I',
            'Vocal II',
            'Vocal III',
            'Presidente Consejo Admon',
            'Secretario Consejo Admon',
            'Tesorero Consejo Admon',
            'Presidente Comité de Crédito',
            'Presidente Consejo Vigilancia',
        ];
        return view('socios.index', compact('socios', 'cargosDirectivos'));
    }

    // mostrar formulario de creación
    public function create()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Socios / Clientes', 'Insercion')) {
            abort(403, 'No tienes permiso para crear socios/clientes.');
        }
        $organizaciones = \App\Models\Organizacion::with(['aldea.municipio.departamento'])->get();
        // Lista de cargos directivos válidos para selects
        $cargosDirectivos = [
            'Presidente(a)',
            'vicepresidente(a)',
            'Tesorero(a)',
            'Secretario(a)',
            'Vocal I',
            'Vocal II',
            'Vocal III',
            'Presidente Consejo Admon',
            'Secretario Consejo Admon',
            'Tesorero Consejo Admon',
            'Presidente Comité de Crédito',
            'Presidente Consejo Vigilancia',
        ];
        return view('socios.create', compact('organizaciones', 'cargosDirectivos'));
    }

    // guardar nuevo socio
    public function store(Request $request)
{
    if (!auth()->user() || !auth()->user()->tienePermiso('Socios / Clientes', 'Insercion')) {
        abort(403, 'No tienes permiso para crear socios/clientes.');
    }

    $validated = $request->validate([
        'Id_Organizacion'       => 'required|integer',
        'Nombre_Beneficiario'   => 'required|string|max:150|regex:/^[A-ZÁÉÍÓÚÑ][a-zA-ZáéíóúñÁÉÍÓÚÑ\s]{0,149}$/',
        'DNI'                   => 'required|regex:/^\d{13}$/|unique:tbl_beneficiario,DNI',
        'genero'                => 'required|in:M,F',
        'fecha_nacimiento'      => 'required|date|before:-18 years',
        'edad'                  => 'nullable|integer|min:18|max:100',
        'estado_civil'          => 'nullable|string|max:50',
        'etnia'                 => 'nullable|string|max:100',
        'nivel_educativo'       => 'nullable|string|max:100',
        'medio_comunicacion'    => 'nullable|string|max:100',
        'departamento'          => 'nullable|string|max:100',
        'municipio'             => 'nullable|string|max:100',
        'comunidad'             => 'nullable|string|max:100',
        'direccion'             => 'nullable|string|max:150',
        'Telefono'              => 'nullable|regex:/^\d{4}-\d{4}$/',
        'actividad_economica'   => 'nullable|string|max:150',
        'actividad_no_agricola' => 'nullable|string|max:150',
        'Tipo_Cargo'            => 'nullable|string|max:100',
        'Tipo_De_Socio'         => 'nullable|string|max:100',
        'categoria'             => 'nullable|string|max:100',
        'estado'                => 'required|boolean',
    ]);

    try {
        // Crear socio
        $socio = Socio::create($validated);

        // Registrar bitácora
        $objeto = \App\Models\Objeto::where('Objeto', 'Socios / Clientes')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Creó un nuevo socio/cliente: ' . $socio->Nombre_Beneficiario
            );
        }

        // Guardar actividades económicas si existen
        if ($request->has('actividades') && is_array($request->actividades)) {
            foreach ($request->actividades as $i => $actividad) {
                \DB::table('tbl_actividad_economica')->insert([
                    'Id_Beneficiario' => $socio->Id_Beneficiario,
                    'Tipo'            => $actividad['tipo'],
                    'Numero'          => $i + 1,
                    'Rubro'           => $actividad['rubro'],
                    'Unidad_Medida'   => $actividad['unidad'],
                    'Cantidad'        => $actividad['cantidad'],
                ]);
            }
        }

        return redirect()->route('socios.index')->with('success', 'Socio creado correctamente.');
    } catch (\Exception $e) {
        return back()
            ->withErrors(['error' => 'Ocurrió un error al guardar el socio: ' . $e->getMessage()])
            ->withInput(); // ✅ conserva los datos en el formulario
    }
}


    // actualizar socio
    public function update(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Socios / Clientes', 'Actualizacion')) {
            abort(403, 'No tienes permiso para actualizar socios/clientes.');
        }
        $validated = $request->validate([
            // 'Id_Organizacion'       => 'required|integer',
            'Nombre_Beneficiario'   => 'required|max:150',
            'DNI' => [
    'required',
    'regex:/^\d{13,14}$/',
    Rule::unique('tbl_beneficiario', 'DNI')->ignore($id, 'Id_Beneficiario'),],
            'genero'                => 'required|in:M,F',
            'Nombre_Caja'           => 'nullable|max:150',
            'fecha_nacimiento'      => 'nullable|date',
            'edad'                  => 'nullable|integer',
            'estado_civil'          => 'nullable|max:50',
            'etnia'                 => 'nullable|max:100',
            'nivel_educativo'       => 'nullable|max:100',
            'medio_comunicacion'    => 'nullable|max:100',
            'departamento'          => 'nullable|max:100',
            'municipio'             => 'nullable|max:100',
            'comunidad'             => 'nullable|max:100',
            'direccion'             => 'nullable|max:150',
            'Telefono'              => 'nullable|max:20',
            'actividad_economica'   => 'nullable|max:150',
            'actividad_no_agricola' => 'nullable|max:150',
            'Tipo_Cargo'            => 'nullable|max:100',
            'Tipo_De_Socio'         => 'nullable|max:100',
            'categoria'             => 'nullable|max:100',
            'estado'                => 'boolean'
        ]);

        $socio = Socio::findOrFail($id);
        $socio->update($validated);

        $objeto = \App\Models\Objeto::where('Objeto', 'Socios / Clientes')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Update',
                'Actualizó el socio/cliente: ' . $socio->Nombre_Beneficiario
            );
        }

        // Actualizar actividades económicas
        if ($request->has('actividades')) {
            $idsEnviados = [];
            foreach ($request->actividades as $i => $actividad) {
                // Si existe Id_Actividad, actualizar; si no, crear
                if (!empty($actividad['id'])) {
                    \DB::table('tbl_actividad_economica')
                        ->where('Id_Actividad', $actividad['id'])
                        ->update([
                            'Tipo' => $actividad['tipo'],
                            'Numero' => $actividad['numero'],
                            'Rubro' => $actividad['rubro'],
                            'Unidad_Medida' => $actividad['unidad'],
                            'Cantidad' => $actividad['cantidad'],
                        ]);
                    $idsEnviados[] = $actividad['id'];
                } else {
                    $nuevoId = \DB::table('tbl_actividad_economica')->insertGetId([
                        'Id_Beneficiario' => $socio->Id_Beneficiario,
                        'Tipo' => $actividad['tipo'],
                        'Numero' => $actividad['numero'],
                        'Rubro' => $actividad['rubro'],
                        'Unidad_Medida' => $actividad['unidad'],
                        'Cantidad' => $actividad['cantidad'],
                    ]);
                    $idsEnviados[] = $nuevoId;
                }
            }
            // Eliminar actividades que no fueron enviadas
            \DB::table('tbl_actividad_economica')
                ->where('Id_Beneficiario', $socio->Id_Beneficiario)
                ->whereNotIn('Id_Actividad', $idsEnviados)
                ->delete();
        } else {
            // Si no se envió ninguna actividad, eliminar todas
            \DB::table('tbl_actividad_economica')
                ->where('Id_Beneficiario', $socio->Id_Beneficiario)
                ->delete();
        }

        return redirect()->route('socios.index')
            ->with('success', 'Socio actualizado correctamente.');
    }

    // eliminar (baja lógica)
    public function destroy($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Socios / Clientes', 'Eliminacion')) {
            abort(403, 'No tienes permiso para eliminar socios/clientes.');
        }
        $socio = Socio::findOrFail($id);
        $socio->update(['estado' => 0]);

        $objeto = \App\Models\Objeto::where('Objeto', 'Socios / Clientes')->first();
        if ($objeto && auth()->check()) {
            EVENT_BITACORA(
                auth()->user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Delete',
                'Inactivó el socio/cliente: ' . $socio->Nombre_Beneficiario
            );
        }

        return redirect()->route('socios.index')
            ->with('success', 'Socio inactivado correctamente.');
    }

    // ficha de socio
    public function ficha($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Socios / Clientes', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar socios/clientes.');
        }
        $socio = Socio::findOrFail($id);
        return view('socios.ficha', compact('socio'));
    }

    // reactivar socio
    public function reactivar($id)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Socios / Clientes', 'Actualizacion')) {
            abort(403, 'No tienes permiso para actualizar socios/clientes.');
        }
        $socio = Socio::findOrFail($id);
        $socio->estado = 1;
        $socio->save();

        return redirect()->route('socios.index')
            ->with('success', 'Socio reactivado correctamente.');
    }


        // Vista de distribución de cargos por caja rural
    public function cargosPorCaja(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Cargos Directivos', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar cargos directivos.');
        }

        $query = Socio::select('tbl_beneficiario.Id_Organizacion')
            ->groupBy('tbl_beneficiario.Id_Organizacion');

        // Aplicar filtro por departamento si existe
        if ($request->filled('departamento')) {
            $query->join('tbl_organizacion as org', 'tbl_beneficiario.Id_Organizacion', '=', 'org.Id_Organizacion')
                  ->join('tbl_aldea as a', 'org.Id_Aldea', '=', 'a.Id_Aldea')
                  ->join('tbl_municipio as m', 'a.Id_Municipio', '=', 'm.Id_Municipio')
                  ->join('tbl_departamento as d', 'm.Id_Departamento', '=', 'd.Id_Departamento')
                  ->where('d.Nombre_Departamento', $request->departamento);
        }

        $cajas = $query->get()
            ->map(function($caja) {
                $org = \App\Models\Organizacion::with(['aldea.municipio.departamento'])->find($caja->Id_Organizacion);
                $caja->nombre_organizacion = $org ? $org->Nombre_Organizacion : '';
                $caja->departamento = $org && $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento 
                    ? $org->aldea->municipio->departamento->Nombre_Departamento : 'N/D';
                
                $caja->presidente_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Presidente(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->presidente_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Presidente(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vicepresidente_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'vicepresidente(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->vicepresidente_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'vicepresidente(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->tesorero_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Tesorero(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->tesorero_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Tesorero(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->secretario_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Secretario(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->secretario_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Secretario(a)')
                    ->where('genero', 'F')
                    ->exists();
                
                // Vocal I
                $vocal1_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal I')
                    ->where('genero', 'M')
                    ->exists();
                $vocal1_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal I')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal1 = $vocal1_h && $vocal1_m ? 'H/M' : ($vocal1_h ? 'H' : ($vocal1_m ? 'M' : ''));

                // Vocal II
                $vocal2_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal II')
                    ->where('genero', 'M')
                    ->exists();
                $vocal2_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal II')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal2 = $vocal2_h && $vocal2_m ? 'H/M' : ($vocal2_h ? 'H' : ($vocal2_m ? 'M' : ''));

                // Vocal III
                $vocal3_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal III')
                    ->where('genero', 'M')
                    ->exists();
                $vocal3_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal III')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal3 = $vocal3_h && $vocal3_m ? 'H/M' : ($vocal3_h ? 'H' : ($vocal3_m ? 'M' : ''));
                
                return $caja;
            });

        // Lista de departamentos para el filtro
        $departamentos = \App\Models\Departamento::orderBy('Nombre_Departamento')->pluck('Nombre_Departamento');

        // Calcular resumen por departamentos (solo si no hay filtro aplicado)
        $resumenDepartamentos = [];
        if (!$request->filled('departamento')) {
            $todasLasCajas = Socio::select('Id_Organizacion')
                ->groupBy('Id_Organizacion')
                ->get()
                ->map(function($caja) {
                    $org = \App\Models\Organizacion::with(['aldea.municipio.departamento'])->find($caja->Id_Organizacion);
                    $caja->departamento = $org && $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento 
                        ? $org->aldea->municipio->departamento->Nombre_Departamento : 'N/D';
                    
                    $caja->tiene_presidente = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                        ->where('Tipo_Cargo', 'Presidente(a)')
                        ->exists();
                    $caja->tiene_vicepresidente = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                        ->where('Tipo_Cargo', 'vicepresidente(a)')
                        ->exists();
                    $caja->tiene_secretario = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                        ->where('Tipo_Cargo', 'Secretario(a)')
                        ->exists();
                    $caja->tiene_tesorero = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                        ->where('Tipo_Cargo', 'Tesorero(a)')
                        ->exists();
                    $caja->vocales = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                        ->whereIn('Tipo_Cargo', ['Vocal I', 'Vocal II', 'Vocal III'])
                        ->count();
                    
                    return $caja;
                });

            foreach ($todasLasCajas->groupBy('departamento') as $dep => $cajasDep) {
                $resumenDepartamentos[$dep] = [
                    'total_cajas' => $cajasDep->count(),
                    'presidentes' => $cajasDep->where('tiene_presidente', true)->count(),
                    'vicepresidentes' => $cajasDep->where('tiene_vicepresidente', true)->count(),
                    'secretarios' => $cajasDep->where('tiene_secretario', true)->count(),
                    'tesoreros' => $cajasDep->where('tiene_tesorero', true)->count(),
                    'vocales' => $cajasDep->sum('vocales'),
                    'total_cargos' => $cajasDep->where('tiene_presidente', true)->count() +
                                     $cajasDep->where('tiene_vicepresidente', true)->count() +
                                     $cajasDep->where('tiene_secretario', true)->count() +
                                     $cajasDep->where('tiene_tesorero', true)->count() +
                                     $cajasDep->sum('vocales')
                ];
            }
        }

        // Cálculo de participación por cargo y género
        $participacion = [
            'presidente' => [
                'H' => Socio::where('Tipo_Cargo', 'Presidente(a)')->where('genero', 'M')->count(),
                'M' => Socio::where('Tipo_Cargo', 'Presidente(a)')->where('genero', 'F')->count(),
            ],
            'secretario' => [
                'H' => Socio::where('Tipo_Cargo', 'Secretario(a)')->where('genero', 'M')->count(),
                'M' => Socio::where('Tipo_Cargo', 'Secretario(a)')->where('genero', 'F')->count(),
            ],
            'tesorero' => [
                'H' => Socio::where('Tipo_Cargo', 'Tesorero(a)')->where('genero', 'M')->count(),
                'M' => Socio::where('Tipo_Cargo', 'Tesorero(a)')->where('genero', 'F')->count(),
            ],
            'presidente_credito' => [
                'H' => Socio::where('Tipo_Cargo', 'Presidente Comité de Crédito')->where('genero', 'M')->count(),
                'M' => Socio::where('Tipo_Cargo', 'Presidente Comité de Crédito')->where('genero', 'F')->count(),
            ],
            'presidente_vigilancia' => [
                'H' => Socio::where('Tipo_Cargo', 'Presidente Consejo de Vigilancia')->where('genero', 'M')->count(),
                'M' => Socio::where('Tipo_Cargo', 'Presidente Consejo de Vigilancia')->where('genero', 'F')->count(),
            ],
        ];
        
        return view('cargos_directivos.index', compact('cajas', 'participacion', 'departamentos', 'resumenDepartamentos'));
    }

    // Exportar cargos directivos a Excel
    public function exportCargos(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Cargos Directivos', 'Consultar')) {
            abort(403, 'No tienes permiso para exportar cargos directivos.');
        }

        $query = Socio::select('Id_Organizacion')
            ->groupBy('Id_Organizacion');

        // Aplicar filtro por departamento si existe
        if ($request->filled('departamento')) {
            $query->join('organizacion as org', 'tbl_beneficiario.Id_Organizacion', '=', 'org.Id_Organizacion')
                  ->join('aldea as a', 'org.Id_Aldea', '=', 'a.Id_Aldea')
                  ->join('municipio as m', 'a.Id_Municipio', '=', 'm.Id_Municipio')
                  ->join('departamento as d', 'm.Id_Departamento', '=', 'd.Id_Departamento')
                  ->where('d.Nombre_Departamento', $request->departamento);
        }

        // Obtener datos (misma lógica que cargosPorCaja)
        $cajas = $query->get()
            ->map(function($caja) {
                $org = \App\Models\Organizacion::with(['aldea.municipio.departamento'])->find($caja->Id_Organizacion);
                $caja->nombre_organizacion = $org ? $org->Nombre_Organizacion : '';
                $caja->departamento = $org && $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento 
                    ? $org->aldea->municipio->departamento->Nombre_Departamento : 'N/D';
                
                $caja->presidente_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Presidente(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->presidente_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Presidente(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vicepresidente_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'vicepresidente(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->vicepresidente_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'vicepresidente(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->tesorero_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Tesorero(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->tesorero_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Tesorero(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->secretario_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Secretario(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->secretario_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Secretario(a)')
                    ->where('genero', 'F')
                    ->exists();
                
                // Vocal I
                $vocal1_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal I')
                    ->where('genero', 'M')
                    ->exists();
                $vocal1_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal I')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal1 = $vocal1_h && $vocal1_m ? 'H/M' : ($vocal1_h ? 'H' : ($vocal1_m ? 'M' : ''));

                // Vocal II
                $vocal2_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal II')
                    ->where('genero', 'M')
                    ->exists();
                $vocal2_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal II')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal2 = $vocal2_h && $vocal2_m ? 'H/M' : ($vocal2_h ? 'H' : ($vocal2_m ? 'M' : ''));

                // Vocal III
                $vocal3_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal III')
                    ->where('genero', 'M')
                    ->exists();
                $vocal3_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal III')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal3 = $vocal3_h && $vocal3_m ? 'H/M' : ($vocal3_h ? 'H' : ($vocal3_m ? 'M' : ''));
                
                return $caja;
            });

        // Crear Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $titulo = 'FUNDER - Distribución de Cargos por Caja Rural';
        if ($request->filled('departamento')) {
            $titulo .= ' - ' . $request->departamento;
        }
        
        $sheet->setCellValue('A1', $titulo);
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $headers = [
            'A3' => 'No.',
            'B3' => 'Departamento',
            'C3' => 'Nombre de la Caja Rural',
            'D3' => 'Presidente(a)',
            'E3' => 'Vicepresidente(a)',
            'F3' => 'Secretario(a)',
            'G3' => 'Tesorero(a)',
            'H3' => 'Vocal I',
            'I3' => 'Vocal II',
            'J3' => 'Vocal III'
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Datos agrupados por departamento
        $row = 4;
        $count = 1;
        $currentDep = '';
        $cajasAgrupadas = $cajas->groupBy('departamento');
        
        foreach ($cajasAgrupadas as $departamento => $cajasDep) {
            // Encabezado del departamento
            if (!$request->filled('departamento')) {
                $sheet->setCellValue('A' . $row, $departamento);
                $sheet->mergeCells('A' . $row . ':J' . $row);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $row)->getFill()->getStartColor()->setARGB('FFE6E6E6');
                $row++;
            }
            
            foreach ($cajasDep as $caja) {
                $sheet->setCellValue('A' . $row, $count++);
                $sheet->setCellValue('B' . $row, $caja->departamento);
                $sheet->setCellValue('C' . $row, $caja->nombre_organizacion);
                
                $presidente = '';
                if ($caja->presidente_h && $caja->presidente_m) $presidente = 'H/M';
                elseif ($caja->presidente_h) $presidente = 'H';
                elseif ($caja->presidente_m) $presidente = 'M';
                $sheet->setCellValue('D' . $row, $presidente);
                
                $vicepresidente = '';
                if ($caja->vicepresidente_h && $caja->vicepresidente_m) $vicepresidente = 'H/M';
                elseif ($caja->vicepresidente_h) $vicepresidente = 'H';
                elseif ($caja->vicepresidente_m) $vicepresidente = 'M';
                $sheet->setCellValue('E' . $row, $vicepresidente);
                
                $secretario = '';
                if ($caja->secretario_h && $caja->secretario_m) $secretario = 'H/M';
                elseif ($caja->secretario_h) $secretario = 'H';
                elseif ($caja->secretario_m) $secretario = 'M';
                $sheet->setCellValue('F' . $row, $secretario);
                
                $tesorero = '';
                if ($caja->tesorero_h && $caja->tesorero_m) $tesorero = 'H/M';
                elseif ($caja->tesorero_h) $tesorero = 'H';
                elseif ($caja->tesorero_m) $tesorero = 'M';
                $sheet->setCellValue('G' . $row, $tesorero);
                
                $sheet->setCellValue('H' . $row, $caja->vocal1 ?: '');
                $sheet->setCellValue('I' . $row, $caja->vocal2 ?: '');
                $sheet->setCellValue('J' . $row, $caja->vocal3 ?: '');
                
                $row++;
            }
            
            // Agregar resumen del departamento si no hay filtro
            if (!$request->filled('departamento')) {
                $totalCajas = $cajasDep->count();
                $presidentes = $cajasDep->filter(fn($c) => $c->presidente_h || $c->presidente_m)->count();
                $vicepresidentes = $cajasDep->filter(fn($c) => $c->vicepresidente_h || $c->vicepresidente_m)->count();
                $secretarios = $cajasDep->filter(fn($c) => $c->secretario_h || $c->secretario_m)->count();
                $tesoreros = $cajasDep->filter(fn($c) => $c->tesorero_h || $c->tesorero_m)->count();
                $vocales = $cajasDep->filter(fn($c) => $c->vocal1 || $c->vocal2 || $c->vocal3)->count();
                
                $sheet->setCellValue('B' . $row, 'TOTAL ' . $departamento . ':');
                $sheet->setCellValue('C' . $row, $totalCajas . ' cajas');
                $sheet->setCellValue('D' . $row, $presidentes);
                $sheet->setCellValue('E' . $row, $vicepresidentes);
                $sheet->setCellValue('F' . $row, $secretarios);
                $sheet->setCellValue('G' . $row, $tesoreros);
                $sheet->setCellValue('H' . $row, $vocales);
                
                $sheet->getStyle('B' . $row . ':H' . $row)->getFont()->setBold(true);
                $row += 2; // Espacio adicional entre departamentos
            }
        }

        // Ajustar ancho de columnas
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Bordes
        $lastRow = $row - 1;
        $sheet->getStyle('A3:J' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'cargos_directivos';
        if ($request->filled('departamento')) {
            $fileName .= '_' . str_replace(' ', '_', $request->departamento);
        }
        $fileName .= '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return response()->stream(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    // Exportar cargos directivos a PDF
    public function exportCargosPdf(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Cargos Directivos', 'Consultar')) {
            abort(403, 'No tienes permiso para exportar cargos directivos.');
        }

        $query = Socio::select('Id_Organizacion')
            ->groupBy('Id_Organizacion');

        // Aplicar filtro por departamento si existe
        if ($request->filled('departamento')) {
            $query->join('organizacion as org', 'tbl_beneficiario.Id_Organizacion', '=', 'org.Id_Organizacion')
                  ->join('aldea as a', 'org.Id_Aldea', '=', 'a.Id_Aldea')
                  ->join('municipio as m', 'a.Id_Municipio', '=', 'm.Id_Municipio')
                  ->join('departamento as d', 'm.Id_Departamento', '=', 'd.Id_Departamento')
                  ->where('d.Nombre_Departamento', $request->departamento);
        }

        // Obtener datos (misma lógica que cargosPorCaja)
        $cajas = $query->get()
            ->map(function($caja) {
                $org = \App\Models\Organizacion::with(['aldea.municipio.departamento'])->find($caja->Id_Organizacion);
                $caja->nombre_organizacion = $org ? $org->Nombre_Organizacion : '';
                $caja->departamento = $org && $org->aldea && $org->aldea->municipio && $org->aldea->municipio->departamento 
                    ? $org->aldea->municipio->departamento->Nombre_Departamento : 'N/D';
                
                $caja->presidente_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Presidente(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->presidente_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Presidente(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vicepresidente_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'vicepresidente(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->vicepresidente_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'vicepresidente(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->tesorero_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Tesorero(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->tesorero_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Tesorero(a)')
                    ->where('genero', 'F')
                    ->exists();
                $caja->secretario_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Secretario(a)')
                    ->where('genero', 'M')
                    ->exists();
                $caja->secretario_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Secretario(a)')
                    ->where('genero', 'F')
                    ->exists();
                
                // Vocal I
                $vocal1_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal I')
                    ->where('genero', 'M')
                    ->exists();
                $vocal1_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal I')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal1 = $vocal1_h && $vocal1_m ? 'H/M' : ($vocal1_h ? 'H' : ($vocal1_m ? 'M' : ''));

                // Vocal II
                $vocal2_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal II')
                    ->where('genero', 'M')
                    ->exists();
                $vocal2_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal II')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal2 = $vocal2_h && $vocal2_m ? 'H/M' : ($vocal2_h ? 'H' : ($vocal2_m ? 'M' : ''));

                // Vocal III
                $vocal3_h = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal III')
                    ->where('genero', 'M')
                    ->exists();
                $vocal3_m = Socio::where('Id_Organizacion', $caja->Id_Organizacion)
                    ->where('Tipo_Cargo', 'Vocal III')
                    ->where('genero', 'F')
                    ->exists();
                $caja->vocal3 = $vocal3_h && $vocal3_m ? 'H/M' : ($vocal3_h ? 'H' : ($vocal3_m ? 'M' : ''));
                
                return $caja;
            });

        // Calcular estadísticas por departamento
        $resumenDepartamentos = [];
        if (!$request->filled('departamento')) {
            $cajasAgrupadas = $cajas->groupBy('departamento');
            foreach ($cajasAgrupadas as $dep => $cajasDep) {
                $resumenDepartamentos[$dep] = [
                    'total_cajas' => $cajasDep->count(),
                    'presidentes' => $cajasDep->filter(fn($c) => $c->presidente_h || $c->presidente_m)->count(),
                    'vicepresidentes' => $cajasDep->filter(fn($c) => $c->vicepresidente_h || $c->vicepresidente_m)->count(),
                    'secretarios' => $cajasDep->filter(fn($c) => $c->secretario_h || $c->secretario_m)->count(),
                    'tesoreros' => $cajasDep->filter(fn($c) => $c->tesorero_h || $c->tesorero_m)->count(),
                    'vocales' => $cajasDep->filter(fn($c) => $c->vocal1 || $c->vocal2 || $c->vocal3)->count()
                ];
            }
        }

        $data = [
            'cajas' => $cajas,
            'resumenDepartamentos' => $resumenDepartamentos,
            'fecha' => date('d/m/Y'),
            'titulo' => 'Distribución de Cargos por Caja Rural',
            'filtro_departamento' => $request->departamento
        ];

        $pdf = PDF::loadView('cargos_directivos.pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $fileName = 'cargos_directivos';
        if ($request->filled('departamento')) {
            $fileName .= '_' . str_replace(' ', '_', $request->departamento);
        }
        $fileName .= '_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($fileName);
    }

}

