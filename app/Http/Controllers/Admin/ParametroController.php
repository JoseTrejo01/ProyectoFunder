<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parametro;

class ParametroController extends Controller
{
    public function index()
    {
        $parametros = Parametro::with('usuario')->get();
        return view('admin.parametros', compact('parametros'));
    }

 
    public function update(Request $request, $id)
    {
        $parametro = Parametro::findOrFail($id);
        $parametro->Valor = $request->input('Valor');
        $parametro->Fecha_Modificacion = now();
        $parametro->save();
        return redirect()->route('parametros.index')->with('success', 'Parámetro actualizado correctamente');
    }
}
