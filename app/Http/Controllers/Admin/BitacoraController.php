<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BitacoraController extends Controller
{
    public function verBitacora(Request $request)
    {
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
            $query->where('Fecha', '<=', $request->fecha_hasta);
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
            $query->where('Fecha', '<=', $request->fecha_hasta);
        }
        $deleted = $query->delete();
        return back()->with('success', 'Registros eliminados: ' . $deleted);
    }
}
