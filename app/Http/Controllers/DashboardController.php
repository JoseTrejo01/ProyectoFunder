<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
public function chartData(Request $request)
{
    // 1. Recibe módulos vía query (o usa los por defecto)
    $modules = $request->query('modules', ['evaluacion', 'ahorro']);
    $year    = now()->year;

    // 2. Define tus módulos válidos
    $allowed = [
        'evaluacion' => [
            'table'    => 'tbl_evaluacion',
            'date_col' => 'created_at',
            'label'    => 'Evaluaciones',
        ],
        'emprendimientos' => [
            'table'    => 'tbl_emprendimiento',
            'date_col' => 'created_at',
            'label'    => 'Emprendimiento',
        ],
        'socios' => [
            'table'    => 'tbl_beneficiario',
            'date_col' => 'created_at',
            'label'    => 'Socios',
        ],
        
    ];

    // 3. Prepara las etiquetas de mes
    $labels = [];
    for ($m = 1; $m <= 12; $m++) {
      $labels = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
           'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    }

    // 4. Construye los datasets, con CLAVE "data"
    $datasets = [];
    foreach ($modules as $module) {
        if (!isset($allowed[$module])) {
            continue;
        }
        $info = $allowed[$module];

        // Cuenta registros mes a mes
        $counts = \DB::table($info['table'])
            ->selectRaw("MONTH({$info['date_col']}) as month, COUNT(*) as total")
            ->whereYear($info['date_col'], $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Rellena array de 12 valores
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = $counts->get($m, 0);
        }

       
        $datasets[] = [
            'label'   => $info['label'],
            'data'    => $data,
            'fill'    => false,
            'tension' => 0.3,
        ];
    }

 
    return response()->json([
        'labels'   => $labels,
        'datasets'=> $datasets,
    ]);
}

    public function index()
    {
        // Importar el modelo Socio
        \App\Models\Socio::class;

        // Socios hombres (Socio, genero M)
        $hombres = \App\Models\Socio::where('Tipo_De_Socio', 'Socio')->where('genero', 'M')->count();
        // Socios mujeres (Socio, genero F)
        $mujeres = \App\Models\Socio::where('Tipo_De_Socio', 'Socio')->where('genero', 'F')->count();
        // Socios niños (Socio, edad < 18)
        $ninos = \App\Models\Socio::where('Tipo_De_Socio', 'Socio')->where('edad', '<', 18)->count();
        // No socios (Tipo_De_Socio = 'Cliente')
        $noSocios = \App\Models\Socio::where('Tipo_De_Socio', 'Cliente')->count();

        // Participación de cargos directivos (hombres y mujeres)
        $participacionCargos = [
            'presidente' => [
                'H' => \App\Models\Socio::where('Tipo_Cargo', 'Presidente(a)')->where('genero', 'M')->count(),
                'M' => \App\Models\Socio::where('Tipo_Cargo', 'Presidente(a)')->where('genero', 'F')->count(),
            ],
            'secretario' => [
                'H' => \App\Models\Socio::where('Tipo_Cargo', 'Secretario(a)')->where('genero', 'M')->count(),
                'M' => \App\Models\Socio::where('Tipo_Cargo', 'Secretario(a)')->where('genero', 'F')->count(),
            ],
            'tesorero' => [
                'H' => \App\Models\Socio::where('Tipo_Cargo', 'Tesorero(a)')->orWhere('Tipo_Cargo', 'Tesorero Consejo Admon')->where('genero', 'M')->count(),
                'M' => \App\Models\Socio::where('Tipo_Cargo', 'Tesorero(a)')->orWhere('Tipo_Cargo', 'Tesorero Consejo Admon')->where('genero', 'F')->count(),
            ],
            'presidente_credito' => [
                'H' => \App\Models\Socio::where('Tipo_Cargo', 'Presidente Comité de Crédito')->where('genero', 'M')->count(),
                'M' => \App\Models\Socio::where('Tipo_Cargo', 'Presidente Comité de Crédito')->where('genero', 'F')->count(),
            ],
            'presidente_vigilancia' => [
                'H' => \App\Models\Socio::where('Tipo_Cargo', 'Presidente Consejo Vigilancia')->where('genero', 'M')->count(),
                'M' => \App\Models\Socio::where('Tipo_Cargo', 'Presidente Consejo Vigilancia')->where('genero', 'F')->count(),
            ],
        ];

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

        return view('dashboard', [
            'hombres' => $hombres,
            'mujeres' => $mujeres,
            'ninos' => $ninos,
            'noSocios' => $noSocios,
            'participacionCargos' => $participacionCargos,
            'cargosDirectivos' => $cargosDirectivos,
        ]);
    }
}
