<?php

namespace App\Http\Controllers;

use App\Models\Ahorro;
use App\Models\Beneficiario;
use App\Models\Organizacion;
use Illuminate\Http\Request;

class AhorroController extends Controller
{
    // Mostrar lista de ahorros recientes
    public function index()
    {
        $ahorros = Ahorro::with('beneficiario')->orderBy('Fecha', 'desc')->paginate(10);
        return view('ahorros.index', compact('ahorros'));
    }

    // Formulario para crear un nuevo ahorro
    public function create()
    {
        $organizaciones = Organizacion::all();
        return view('ahorros.create', compact('organizaciones'));
    }

    // Guardar un nuevo ahorro
    public function store(Request $request)
    {
        $request->validate([
            'Id_Beneficiario' => 'required|exists:tbl_beneficiario,id_Beneficiario',
            'Monto' => 'required|numeric|min:0',
            'Fecha' => 'required|date',
        ]);

        Ahorro::create($request->all());
        return redirect()->route('ahorros.index')->with('success', 'Ahorro registrado correctamente');
    }

    // Mostrar resumen general por organización
   public function resumenGeneral()
{
    // Carga todas las organizaciones y sus beneficiarios y ahorros de manera eficiente
    $cajas = Organizacion::with(['beneficiarios.ahorros'])->get();
    $resumen = [];

    foreach ($cajas as $caja) {
        // Filtra beneficiarios por tipo
        $socios = $caja->beneficiarios->where('Tipo_De_Socio', 'Socio');
        $noSocios = $caja->beneficiarios->where('Tipo_De_Socio', 'No Socio');

        // Separa a los no socios por edad
        $noSociosAdultos = $noSocios->where('Edad', '>=', 18);
        $ninos = $noSocios->where('Edad', '<', 18);

        // Cálculos para Socios
        $ahorrosSocios = $socios->flatMap->ahorros->sum('Monto');
        $numSocios = $socios->count();
        $promedioSocios = ($numSocios > 0) ? $ahorrosSocios / $numSocios : 0;

        // Cálculos para No Socios Adultos
        $ahorrosNoSociosAdultos = $noSociosAdultos->flatMap->ahorros->sum('Monto');
        $numNoSociosAdultos = $noSociosAdultos->count();
        $promedioNoSociosAdultos = ($numNoSociosAdultos > 0) ? $ahorrosNoSociosAdultos / $numNoSociosAdultos : 0;

        // Cálculos para Niños
        $ahorrosNinos = $ninos->flatMap->ahorros->sum('Monto');
        $numNinos = $ninos->count();
        $promedioNinos = ($numNinos > 0) ? $ahorrosNinos / $numNinos : 0;

        // Cálculos para el Subtotal de No Socios
        $ahorrosSubtotalNoSocios = $ahorrosNoSociosAdultos + $ahorrosNinos;
        $numSubtotalNoSocios = $numNoSociosAdultos + $numNinos;
        $promedioSubtotalNoSocios = ($numSubtotalNoSocios > 0) ? $ahorrosSubtotalNoSocios / $numSubtotalNoSocios : 0;

        // Cálculos para el Total de la Caja
        $ahorrosTotal = $ahorrosSocios + $ahorrosSubtotalNoSocios;
        $numTotal = $numSocios + $numSubtotalNoSocios;
        $promedioTotal = ($numTotal > 0) ? $ahorrosTotal / $numTotal : 0;

        $resumen[] = [
            'caja' => $caja->Nombre_Organizacion,
            'num_socios' => $numSocios,
            'ahorros_socios' => $ahorrosSocios,
            'promedio_socios' => $promedioSocios,
            'num_adultos' => $numNoSociosAdultos,
            'ahorros_adultos' => $ahorrosNoSociosAdultos,
            'promedio_adultos' => $promedioNoSociosAdultos,
            'num_ninos' => $numNinos,
            'ahorros_ninos' => $ahorrosNinos,
            'promedio_ninos' => $promedioNinos,
            'num_subtotal_no_socios' => $numSubtotalNoSocios,
            'ahorros_subtotal_no_socios' => $ahorrosSubtotalNoSocios,
            'promedio_subtotal_no_socios' => $promedioSubtotalNoSocios,
            'num_total' => $numTotal,
            'ahorros_total' => $ahorrosTotal,
            'promedio_total' => $promedioTotal,
        ];
    }

    return view('ahorros.resumen', compact('resumen'));

    } // Aquí faltaba una llave de cierre
    
    public function reportePDF()
    {
        $ahorros = Ahorro::with('beneficiario')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ahorros.reporte', compact('ahorros'));
        return $pdf->download('reporte_ahorros.pdf');
    }
    
    public function guardarEdicion(Request $request)
{
    // Validar que existan los datos
    $request->validate([
        'id' => 'required|array',
        'num_socios' => 'required|array',
        'ahorros_socios' => 'required|array',
        'num_adultos' => 'required|array',
        'ahorros_adultos' => 'required|array',
        'num_ninos' => 'required|array',
        'ahorros_ninos' => 'required|array',
    ]);

    $ids = $request->id;

    foreach ($ids as $index => $ahorroId) {
        $ahorro = Ahorro::find($ahorroId);
        if ($ahorro) {
            // Actualizar valores de socios
            $ahorro->Num_Socios = $request->num_socios[$index] ?? 0;
            $ahorro->Total_Socios = $request->ahorros_socios[$index] ?? 0;
            $ahorro->Promedio_Socios = $ahorro->Num_Socios > 0 ? $ahorro->Total_Socios / $ahorro->Num_Socios : 0;

            // Actualizar valores de adultos no socios
            $ahorro->Num_Adultos = $request->num_adultos[$index] ?? 0;
            $ahorro->Total_Adultos = $request->ahorros_adultos[$index] ?? 0;
            $ahorro->Promedio_Adultos = $ahorro->Num_Adultos > 0 ? $ahorro->Total_Adultos / $ahorro->Num_Adultos : 0;

            // Actualizar valores de niños no socios
            $ahorro->Num_Ninos = $request->num_ninos[$index] ?? 0;
            $ahorro->Total_Ninos = $request->ahorros_ninos[$index] ?? 0;
            $ahorro->Promedio_Ninos = $ahorro->Num_Ninos > 0 ? $ahorro->Total_Ninos / $ahorro->Num_Ninos : 0;

            // Subtotales no socios
            $ahorro->Num_NoSocios = $ahorro->Num_Adultos + $ahorro->Num_Ninos;
            $ahorro->Total_NoSocios = $ahorro->Total_Adultos + $ahorro->Total_Ninos;
            $ahorro->Promedio_NoSocios = $ahorro->Num_NoSocios > 0 ? $ahorro->Total_NoSocios / $ahorro->Num_NoSocios : 0;

            // Totales generales
            $ahorro->Num_Total = $ahorro->Num_Socios + $ahorro->Num_NoSocios;
            $ahorro->Total_General = $ahorro->Total_Socios + $ahorro->Total_NoSocios;
            $ahorro->Promedio_Total = $ahorro->Num_Total > 0 ? $ahorro->Total_General / $ahorro->Num_Total : 0;

            $ahorro->save();
        }
    }

    return redirect()->route('ahorros.resumen')->with('success', 'Ahorros actualizados correctamente.');
}

    public function edit(Ahorro $ahorro)
    {
        $cajas = Organizacion::all(); // Traer todas las cajas rurales
        return view('ahorros.edit', compact('ahorro', 'cajas'));
    }

    public function update(Request $request, Ahorro $ahorro)
    {
        $request->validate([
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'Id_Beneficiario' => 'required|exists:tbl_beneficiario,Id_Beneficiario',
            'Monto' => 'required|numeric|min:0.000001',
            'Fecha' => 'required|date',
        ]);

        $ahorro->update($request->only('Id_Organizacion', 'Id_Beneficiario', 'Monto', 'Fecha'));
        return redirect()->route('ahorros.index')->with('success', 'Ahorro actualizado correctamente.');
    }
}