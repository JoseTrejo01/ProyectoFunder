<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Socio;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportSociosPdfController extends Controller
{
    public function exportPdf(Request $request)
    {
        // MISMA LÓGICA DE FILTROS
        $query = Socio::query()->where('estado', 1);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nombre_Beneficiario', 'like', "%$search%")
                  ->orWhere('DNI', 'like', "%$search%")
                  ->orWhere('Telefono', 'like', "%$search%");
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
            $query->where('departamento', 'like', "%{$request->departamento}%");
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

        $socios = $query->get();

       $pdf = Pdf::loadView('socios.pdf', compact('socios'))->setPaper('A4', 'landscape');
        return $pdf->download('socios-filtrados.pdf');
    }
}
