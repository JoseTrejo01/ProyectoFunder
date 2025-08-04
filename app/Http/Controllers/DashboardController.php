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
        return view('dashboard');
    }
}
