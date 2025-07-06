<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatabaseController extends Controller
{
    public function index()
    {
        // Solo administradores
        if (!auth()->user() || !auth()->user()->tienePermiso('Usuarios', 'Consultar')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para gestionar la base de datos']);
        }
        return view('admin.database');
    }


public function backup()
{
    $db = env('DB_DATABASE');
    $user = env('DB_USERNAME');
    $pass = env('DB_PASSWORD');
    $host = env('DB_HOST', '127.0.0.1');
    $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
    $filename = 'backup_' . date('Ymd_His') . '.sql';
    $path = storage_path('app/' . $filename);
    $passPart = $pass ? "-p$pass" : "";
    $command = "\"$mysqldump\" -h $host -u $user $passPart $db > \"$path\" 2>&1";
    $result = null;
    $output = null;
    exec($command, $output, $result);
    if ($result === 0) {
        return response()->download($path)->deleteFileAfterSend(true);
    } else {
        return back()->with('error', 'Error al generar el backup: ' . implode(' ', $output));
    }
}

public function restore(Request $request)
{
    $request->validate([
        'backup_file' => 'required|file|mimes:sql',
    ]);
    $file = $request->file('backup_file');
    $path = $file->getRealPath();
    $db = env('DB_DATABASE');
    $user = env('DB_USERNAME');
    $pass = env('DB_PASSWORD');
    $host = env('DB_HOST', '127.0.0.1');
    $mysql = 'C:\\xampp\\mysql\\bin\\mysql.exe';
    $passPart = $pass ? "-p$pass" : "";
    $command = "\"$mysql\" -h $host -u $user $passPart $db < \"$path\" 2>&1";
    $result = null;
    $output = null;
    exec($command, $output, $result);
    if ($result === 0) {
        return back()->with('success', 'Base de datos restaurada correctamente.');
    } else {
        return back()->with('error', 'Error al restaurar la base de datos: ' . implode(' ', $output));
    }
}
}
