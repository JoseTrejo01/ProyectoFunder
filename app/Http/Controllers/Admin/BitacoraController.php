<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BitacoraController extends Controller
{
    public function verBitacora(Request $request)
    {
        // Registrar evento de acceso a la bitácora
        if (Auth::check()) {
            EVENT_BITACORA(Auth::user()->Id_Usuario, 4, 'Ingreso', 'El usuario accedió a la bitácora.');
        }

        $query = Bitacora::with(['usuario', 'objeto']);

        // Filtros
        if ($request->filled('usuario')) {
            $query->whereHas('usuario', function($q) use ($request) {
                $q->where('Nombre_Usuario', 'like', '%'.$request->usuario.'%');
            });
        }
        if ($request->filled('objeto')) {
            $query->whereHas('objeto', function($q) use ($request) {
                $q->where('Objeto', 'like', '%'.$request->objeto.'%');
            });
        }
        if ($request->filled('accion')) {
            $query->where('Accion', 'like', '%'.$request->accion.'%');
        }
        // 🔹 NUEVO: filtro por descripción
        if ($request->filled('descripcion')) {
            $query->where('Descripcion', 'like', '%'.$request->descripcion.'%');
        }
        if ($request->filled('fecha_desde')) {
            $query->where('Fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            // Si la fecha_hasta no tiene hora, agregar 23:59:59 para incluir todo el día
            $fechaHasta = $request->fecha_hasta;
            if (strlen($fechaHasta) === 10) { // formato YYYY-MM-DD
                $fechaHasta .= ' 23:59:59';
            }
            $query->where('Fecha', '<=', $fechaHasta);
        }

        $registros = $query->orderBy('Fecha', 'desc')->get();
        return view('admin.ver_bitacora', compact('registros'));
    }

    public function borrarBitacora(Request $request)
    {
        if (!auth()->user() || !auth()->user()->tienePermiso('Bitacora', 'Eliminacion')) {
            abort(403, 'No tienes permiso para borrar la bitácora.');
        }

        $query = Bitacora::query();

        if ($request->filled('usuario')) {
            $query->whereHas('usuario', function($q) use ($request) {
                $q->where('Nombre_Usuario', 'like', '%'.$request->usuario.'%');
            });
        }
        if ($request->filled('objeto')) {
            $query->whereHas('objeto', function($q) use ($request) {
                $q->where('Objeto', 'like', '%'.$request->objeto.'%');
            });
        }
        if ($request->filled('accion')) {
            $query->where('Accion', 'like', '%'.$request->accion.'%');
        }
        // 🔹 NUEVO: filtro por descripción
        if ($request->filled('descripcion')) {
            $query->where('Descripcion', 'like', '%'.$request->descripcion.'%');
        }
        if ($request->filled('fecha_desde')) {
            $query->where('Fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            // Si la fecha_hasta no tiene hora, agregar 23:59:59 para incluir todo el día
            $fechaHasta = $request->fecha_hasta;
            if (strlen($fechaHasta) === 10) { // formato YYYY-MM-DD
                $fechaHasta .= ' 23:59:59';
            }
            $query->where('Fecha', '<=', $fechaHasta);
        }

        $deleted = $query->delete();
        return back()->with('success', 'Registros eliminados: ' . $deleted);
    }

    public function exportarPDF(Request $request)
    {
        if (!auth()->user()) {
            abort(403, 'No tienes permiso para exportar la bitácora.');
        }

        $query = Bitacora::with(['usuario', 'objeto']);

        // Reaplicar filtros
        if ($request->filled('usuario')) {
            $query->whereHas('usuario', function($q) use ($request) {
                $q->where('Nombre_Usuario', 'like', '%'.$request->usuario.'%');
            });
        }
        if ($request->filled('objeto')) {
            $query->whereHas('objeto', function($q) use ($request) {
                $q->where('Objeto', 'like', '%'.$request->objeto.'%');
            });
        }
        if ($request->filled('accion')) {
            $query->where('Accion', 'like', '%'.$request->accion.'%');
        }
        // 🔹 NUEVO: filtro por descripción
        if ($request->filled('descripcion')) {
            $query->where('Descripcion', 'like', '%'.$request->descripcion.'%');
        }
        if ($request->filled('fecha_desde')) {
            $query->where('Fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $fechaHasta = $request->fecha_hasta;
            if (strlen($fechaHasta) === 10) {
                $fechaHasta .= ' 23:59:59';
            }
            $query->where('Fecha', '<=', $fechaHasta);
        }

        $registros = $query->orderBy('Fecha', 'desc')->get();

        $pdf = Pdf::loadView('admin.reportes.bitacora_pdf', [
            'registros' => $registros,
            'pdf' => true,
        ])->setPaper('a4', 'landscape');

        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isPhpEnabled', true);

        return $pdf->download('reporte_bitacora.pdf');
    }
}
