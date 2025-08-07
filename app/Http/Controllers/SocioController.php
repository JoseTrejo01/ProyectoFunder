<?php
namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;



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


        $socios = $query->with(['actividades', 'organizacion'])->paginate(10);

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
            'vicepresidente (a)',
            'Tesorero (a)',
            'Secretario (a)',
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
            'vicepresidente (a)',
            'Tesorero (a)',
            'Secretario (a)',
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
    public function cargosPorCaja()
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Cargos Directivos', 'Consultar')) {
            abort(403, 'No tienes permiso para consultar cargos directivos.');
        }
        $cajas = Socio::select('Id_Organizacion')
            ->groupBy('Id_Organizacion')
            ->get()
            ->map(function($caja) {
                $org = \App\Models\Organizacion::find($caja->Id_Organizacion);
                $caja->nombre_organizacion = $org ? $org->Nombre_Organizacion : '';
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
        return view('socios.cargos', compact('cajas', 'participacion'));
    }

}

