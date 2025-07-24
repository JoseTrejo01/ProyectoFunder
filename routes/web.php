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
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\CoordenadasMapaController;
use App\Http\Controllers\ExportSociosPdfController;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\EmprendimientoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DashboardController;

use App\Exports\SociosExport;
use Maatwebsite\Excel\Facades\Excel;

// Página principal
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cambio de contraseña
    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

    // Permisos y Bitácora
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');

    // Usuarios
    Route::resource('admin/usuarios', UsuarioController::class)->except(['show']);

    // Socios
    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');

    // Exportar socios a Excel
    Route::get('/socios/export', function (Request $request) {
        $filters = $request->only('search', 'genero', 'localidad', 'tipo');
        return Excel::download(new SociosExport($filters), 'socios.xlsx');
    })->name('socios.export');

    // Exportar PDF
    Route::get('/socios/export-pdf', [ExportSociosPdfController::class, 'exportPdf'])->name('socios.export-pdf');

    // Ahorros
    Route::resource('ahorros', AhorroController::class);
    Route::get('/ahorros/{id}/ficha', [AhorroController::class, 'ficha'])->name('ahorros.ficha');
    Route::get('/ahorros/export-pdf', [AhorroController::class, 'exportPdf'])->name('ahorros.export-pdf');

    // API para contar socios
    Route::get('/organizacion/{id}/socios', function ($id) {
        $total = DB::table('tbl_beneficiario')
            ->where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Socio')
            ->count();
        return response()->json(['total_socios' => $total]);
    });

    Route::get('/organizacion/{id}/contar-socios', [AhorroController::class, 'contarSocios']);
    Route::get('/api/cajas/{id}/resumen', [AhorroController::class, 'resumen']);

    // Indicadores de género
    Route::resource('genero', IndicadorGeneroController::class);

    // Emprendimientos
    Route::resource('emprendimientos', EmprendimientoController::class);

    // Organizaciones
    Route::resource('organizaciones', OrganizacionController::class)->except(['show']);

    // Préstamos
    Route::get('/creditos', [PrestamoController::class, 'index'])->name('creditos');
    Route::get('/creditos/pendientes', [PrestamoController::class, 'pendientes'])->name('creditos.pendientes');
    Route::get('/creditos/reportes', [PrestamoController::class, 'reportes'])->name('creditos.reportes');
    Route::get('/prestamos/crear', [PrestamoController::class, 'create'])->name('prestamos.create');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
    Route::put('/prestamos/{id}/aprobar', [PrestamoController::class, 'aprobar'])->name('prestamos.aprobar');
    Route::put('/prestamos/{id}/rechazar', [PrestamoController::class, 'rechazar'])->name('prestamos.rechazar');
    Route::post('/prestamos/{id}/desembolsar', [PrestamoController::class, 'desembolsar'])->name('prestamos.desembolsar');

    // Pagos
    Route::get('/prestamos/{id}/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/prestamos/{id}/pagos/crear', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/prestamos/{id}/pagos', [PagoController::class, 'store'])->name('pagos.store');

    // AJAX ubicación
    Route::get('/municipios/{id}', [UbicacionController::class, 'getMunicipios'])->name('ubicacion.municipios');
    Route::get('/aldeas/{id}', [UbicacionController::class, 'getAldeas'])->name('ubicacion.aldeas');

    // API: coordenadas del mapa
    Route::get('/api/cajas-rurales', [OrganizacionController::class, 'obtenerCajasConSocios']);
});

// Vistas auxiliares
Route::get('/prueba', fn () => view('prueba'));
Route::get('/organizaciones/mapa', [OrganizacionController::class, 'vistaMapa'])->name('organizaciones.mapa');
