<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
