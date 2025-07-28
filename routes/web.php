<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\UsuarioController;

use App\Http\Controllers\AhorroController;
use App\Http\Controllers\IndicadorGeneroController;
use App\Http\Controllers\SocioController;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SociosExport;
use Maatwebsite\Excel\Facades\Excel;

// Ruta pública de bienvenida o home
Route::get('/', function () {
    if (auth()->check()) return redirect('/dashboard');
    return view('welcome');
})->name('home');

// RUTAS PARA USUARIOS NO AUTENTICADOS
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/reset', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('otp.send');

    Route::get('password/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('otp.form');
    Route::post('password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');

    Route::get('password/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('password/reset-password', [ResetPasswordController::class, 'reset'])->name('otp.reset.password');

    Route::get('password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('otp.resend');
});

// RUTAS PARA USUARIOS AUTENTICADOS Y VERIFICADOS
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Logout (repetido aquí para middleware)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cambiar contraseña
    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

    // Gestión permisos y bitácora
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');

    // Roles y objetos
    Route::post('/roles/store', [GestionController::class, 'storeRol'])->name('roles.store');
    Route::post('/objetos/store', [GestionController::class, 'storeObjeto'])->name('objetos.store');

    // Administración usuarios
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Socios (CRUD excepto show)
    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');

    // Exportaciones socios Excel y PDF
    Route::get('/socios/export', function (Request $request) {
        $filters = $request->only('search','genero','localidad','tipo');
        return Excel::download(new SociosExport($filters), 'socios.xlsx');
    })->name('socios.export');

    Route::get('/socios/export-pdf', function (Request $request) {
        $query = \App\Models\Socio::query()->where('estado', 1);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Nombre_Beneficiario', 'like', "%$search%")
                  ->orWhere('DNI', 'like', "%$search%")
                  ->orWhere('Telefono', 'like', "%$search%");
            });
        }
        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }
        if ($request->filled('localidad')) {
            $query->where('direccion', 'like', "%{$request->localidad}%");
        }
        if ($request->filled('tipo')) {
            $query->where('Tipo_De_Socio', 'like', "%{$request->tipo}%");
        }

        $socios = $query->get();
        $pdf = Pdf::loadView('socios.pdf', compact('socios'));
        return $pdf->download('socios.pdf');
    })->name('socios.export-pdf');

    // Módulo AHORROS
    Route::get('/ahorros', [AhorroController::class, 'index'])->name('ahorros.index');
    Route::get('/api/ahorros/caja/{id}/resumen', [AhorroController::class, 'resumenCaja'])->name('ahorros.resumen');
    Route::get('/api/ahorros/caja/{id}/socios', [AhorroController::class, 'obtenerSocios'])->name('ahorros.socios');
    Route::get('/ahorros/create', [AhorroController::class, 'create'])->name('ahorros.create');
    Route::post('/ahorros', [AhorroController::class, 'store'])->name('ahorros.store');
    Route::get('/api/cajas/{id}/beneficiarios', [AhorroController::class, 'obtenerSocios']);
    Route::get('/ahorros/caja/{id}/listado', [AhorroController::class, 'listado'])->name('ahorros.listado');

    // API adicional para contar socios por organizacion (si lo usas)
    Route::get('/organizacion/{id}/socios', function ($id) {
        $total = DB::table('tbl_beneficiario')
            ->where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Socio')
            ->count();

        return response()->json(['total_socios' => $total]);
    });

    // Indicadores de género
    Route::resource('genero', IndicadorGeneroController::class);

});
