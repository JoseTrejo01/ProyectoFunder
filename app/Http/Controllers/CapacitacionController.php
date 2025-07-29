<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapacitacionController extends Controller
{
    public function index()
    {
        $organizaciones = Organizacion::where('Estado_Organizacion', 'ACTIVO')->get();

        $beneficiariosPorOrg = [];
        foreach ($organizaciones as $org) {
            $beneficiarios = Socio::where('Id_Organizacion', $org->Id_Organizacion)
                ->where('estado', 1)
                ->select('Id_Beneficiario', 'Nombre_Beneficiario')
                ->get();
            $beneficiariosPorOrg[$org->Id_Organizacion] = $beneficiarios;
        }

        // Módulos y Temas extraídos del Excel (respetando nombres)
        $modulos = [
            'Módulo I: Organización de cajas rurales / Gobernanza' => [
                'Definición y objetivos para organizar una caja rural y capitalización de la caja rural (Una forma de fondeo en la caja rural) y plan de capitalización.',
                'Requisitos para ser socios(as) y directivos de la caja rural',
                'Deberes y derechos de los socios(as) y de los directivos',
                'Acta Constitutiva y actas de reuniones',
                'Estructura operativa de la caja rural, funciones de los directivos y comités',
                'Estatutos de la caja rural.',
                'Operaciones básicas y uso de calculadora.',
                'Manejo de libro de accionistas.',
                'Manejo de libro de entradas y salidas.',
                'Manejo de recibos de ingresos y egresos.',
                'Seguimiento y acompañamiento técnico post-capacitación.'
            ],
            'Módulo II: Cálculo de intereses y administración de ahorros' => [
                'Concepto de ahorros, motivos del porqué ahorrar (Una forma de fondeo en la caja rural) y plan de ahorros.',
                'Fórmula del interés simple.',
                'Cómo fijar una tasa de interés.',
                'Métodos para el cálculo de intereses sobre ahorros.',
                'Manejo de libro de ahorros.',
                'Reglamento de ahorros.',
                'Manejo de libreta de ahorros.',
                'Distribución de utilidades.',
                'Módulo sobre el Ahorro (Importancia del ahorro, razones para ahorrar).',
                'Seguimiento y acompañamiento técnico post-capacitación.'
            ],
            'Módulo III: Administración de préstamos' => [
                'Conceptos generales referentes a los préstamos.',
                'Reglamento de préstamos.',
                'Métodos para el cálculo de intereses sobre préstamos (Métodos sobre monto y saldos)',
                'Manejo de libro de préstamos.',
                'Elaboración de plan de pagos.',
                'Reglamento de préstamos.',
                'Proceso administrativo para el otorgamiento de préstamos (otorgamiento y recuperación de préstamos).',
                'Módulo sobre el préstamo (maneje su préstamo responsablemente).',
                'Seguimiento y acompañamiento técnico post-capacitación.'
            ],
            'Módulo IV: Elaboración de estados financieros para las cajas rurales' => [
                'Qué son los estados financieros, conceptos generales',
                'Diferencia entre un estado de resultado y un balance general.',
                'Estructura de un estado de resultado y sus cuentas.',
                'Estructura de un balance general y sus cuentas.',
                'Seguimiento y acompañamiento técnico post-capacitación.'
            ],
            'Módulo V: Aspectos legales; Obtención de personalidad jurídica y/o Actualización de Junta Directiva' => [
                'Qué es una personalidad jurídica y sus usos.',
                'Requisitos para la obtención.',
                'Ley del sector social de la economía.',
                'Discusión y aprobación de estatutos para la certificación por parte de la Secretaría de Desarrollo Económico.',
                'Trámite de registro tributario nacional (RTN)',
                'Acompañamiento técnico en trámite de personalidad jurídica y RTN.'
            ],
            'Módulo VI: Educación financiera' => [
                'Qué es educación financiera, sus beneficios.',
                'Módulo Presupuesto: Establezca metas financieras, examine cómo gasta su dinero, tome decisiones de acuerdo a sus ingresos y gastos, analizando cómo se hace el presupuesto, elabore su presupuesto.',
                'Módulo de Negociación Financiera: ¿Qué es la negociación financiera?, técnicas de negociación, preparándonos para una negociación financiera, practicando la negociación, revisando la negociación.',
                'Seguimiento y acompañamiento técnico post-capacitación.'
            ]
        ];

        return view('capacitacion.index', compact('organizaciones', 'beneficiariosPorOrg', 'modulos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'Id_Organizacion' => 'required|exists:tbl_organizacion,Id_Organizacion',
            'Nombre_Modulo' => 'required|string|max:100',
            'Nombre_Tema' => 'required|string|max:100',
            'Fecha' => 'required|date',
            'beneficiarios' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $idCapacitacion = DB::table('tbl_capacitacion')->insertGetId([
                'Id_Organizacion' => $request->Id_Organizacion,
                'Nombre_Modulo' => $request->Nombre_Modulo,
                'Nombre_Tema' => $request->Nombre_Tema,
                'Fecha' => $request->Fecha,
            ]);

            foreach ($request->beneficiarios as $idBeneficiario) {
                DB::table('tbl_capacitacion_beneficiario')->insert([
                    'Id_Capacitacion' => $idCapacitacion,
                    'Id_Beneficiario' => $idBeneficiario,
                ]);
            }

            DB::commit();
            return redirect()->route('capacitacion.index')->with('success', 'Capacitación registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar capacitación: ' . $e->getMessage());
        }
    }
}
