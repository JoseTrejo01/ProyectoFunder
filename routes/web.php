<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Controllers - Auth
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Controllers - Admin
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ParametroController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\ObjetoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ExportReportesController;

// Controllers - Módulos
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ExportSociosController;
use App\Http\Controllers\ExportSociosPdfController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\EmprendimientoController;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\AhorroController;
use App\Http\Controllers\IndicadorGeneroController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\CoordenadasMapaController;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\ExportEvaluacionesController;
use App\Http\Controllers\CriterioController;
use App\Http\Controllers\InformeFinancieroController;

// Librerías
use App\Exports\SociosExport;
use Maatwebsite\Excel\Facades\Excel;

// Página de inicio
Route::get('/', fn () => auth()->check() ? redirect('/dashboard') : view('welcome'))->name('home');

// ===================== RUTAS PARA INVITADOS =====================
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Recuperación de contraseña
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/reset', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('otp.send');
    Route::get('password/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('otp.form');
    Route::post('password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');
    Route::get('password/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('password/reset-password', [ResetPasswordController::class, 'reset'])->name('otp.reset.password');
    Route::get('password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('otp.resend');
});

// ===================== VERIFICACIÓN DE CORREO =====================
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Correo verificado correctamente. Ya puedes iniciar sesión.');
    })->middleware(['signed'])->name('verification.verify');
});

// ===================== RUTAS AUTENTICADOS =====================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cambio de contraseña
    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

    // Parámetros
    Route::prefix('parametros')->group(function () {
        Route::get('/', [ParametroController::class, 'index'])->name('parametros.index');
        Route::post('/', [ParametroController::class, 'store'])->name('parametros.store');
        Route::put('/{id}', [ParametroController::class, 'update'])->name('parametros.update');
        Route::delete('/{id}', [ParametroController::class, 'destroy'])->name('parametros.destroy');
    });

    // Admin
    Route::prefix('admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class)->except(['show']);

        Route::prefix('database')->group(function () {
            Route::get('/', [DatabaseController::class, 'index'])->name('admin.database');
            Route::post('/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
            Route::post('/restore', [DatabaseController::class, 'restore'])->name('admin.database.restore');
        });

        Route::prefix('roles')->group(function () {
            Route::get('/', [RolController::class, 'index'])->name('roles.index');
            Route::post('/', [RolController::class, 'store'])->name('roles.store');
            Route::put('/{id}', [RolController::class, 'update'])->name('roles.update');
            Route::delete('/{id}', [RolController::class, 'destroy'])->name('roles.destroy');
            Route::post('/store', [GestionController::class, 'storeRol'])->name('roles.store.gestion');
        });

        Route::prefix('objetos')->group(function () {
            Route::get('/', [ObjetoController::class, 'index'])->name('objetos.index');
            Route::post('/', [ObjetoController::class, 'store'])->name('objetos.store');
            Route::put('/{id}', [ObjetoController::class, 'update'])->name('objetos.update');
            Route::delete('/{id}', [ObjetoController::class, 'destroy'])->name('objetos.destroy');
            Route::post('/store', [GestionController::class, 'storeObjeto'])->name('objetos.store.gestion');
        });

        Route::prefix('reportes')->group(function () {
            Route::get('/', [ReporteController::class, 'index'])->name('admin.reportes.index');
            Route::get('/cajas', [ReporteController::class, 'cajas'])->name('admin.reportes.cajas');
            Route::get('/cajas/export', [ExportReportesController::class, 'exportCajas'])->name('admin.reportes.cajas.export');
            Route::get('/cargos', [ReporteController::class, 'cargos'])->name('admin.reportes.cargos');
            Route::get('/cargos/export', [ExportReportesController::class, 'exportCargos'])->name('admin.reportes.cargos.export');
            Route::get('/export-capacitaciones', [ReporteController::class, 'exportCapacitacionesExcel'])->name('reporte.exportCapacitacionesExcel');
        });
    });

    // Resto de módulos
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');

    Route::resource('criterio', CriterioController::class);
    Route::resource('evaluacion', EvaluacionController::class);
<<<<<<< HEAD
=======
   Route::get('dashboard/chart-data', [DashboardController::class, 'chartData'])
     ->name('dashboard.chart-data');

    // Exportación a Excel 
>>>>>>> bc713d5e6cb391f2e180dc9d81a79cb57559ef99
    Route::get('/evaluacion/exportar-excel', [ExportEvaluacionesController::class, 'export'])->name('evaluacion.export');

    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/cargos', [SocioController::class, 'cargosPorCaja'])->name('socios.cargos');
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');
    Route::get('/socios/export', function (Request $request) {
        $filters = $request->only('search', 'genero', 'localidad', 'tipo');
        return Excel::download(new SociosExport($filters), 'socios.xlsx');
    })->name('socios.export');
    Route::get('/socios/export-pdf', [ExportSociosPdfController::class, 'exportPdf'])->name('socios.export-pdf');

    Route::resource('ahorros', AhorroController::class);
    Route::get('/ahorros/{id}/ficha', [AhorroController::class, 'ficha'])->name('ahorros.ficha');
    Route::get('/ahorros/export-pdf', [AhorroController::class, 'exportPdf'])->name('ahorros.export-pdf');
    Route::get('/ahorros/caja/{id}/listado', [AhorroController::class, 'listarPorCaja']);
    Route::get('/api/organizacion/{id}/beneficiarios', [AhorroController::class, 'sociosPorCaja']);
    Route::get('/organizacion/{id}/contar-socios', [AhorroController::class, 'contarSocios']);
    Route::get('/api/cajas/{id}/resumen', [AhorroController::class, 'resumen']);
    Route::get('/api/ahorros/caja/{id}/socios', [AhorroController::class, 'obtenerSocios'])->name('ahorros.socios');

    Route::resource('genero', IndicadorGeneroController::class);
    Route::resource('emprendimientos', EmprendimientoController::class);
    Route::resource('organizaciones', OrganizacionController::class)->except(['show']);
    Route::get('/organizaciones/mapa', [OrganizacionController::class, 'vistaMapa'])->name('organizaciones.mapa');
    Route::get('/api/cajas/{id}/socios', function ($id) {
        return App\Models\Socio::select('Id_Beneficiario', 'Nombre_Beneficiario as Nombre')
            ->where('Id_Organizacion', $id)
            ->where('estado', 1)
            ->get();
    });
    Route::get('/api/cajas-rurales', [OrganizacionController::class, 'obtenerCajasConSocios']);

    Route::get('/creditos', [PrestamoController::class, 'index'])->name('creditos');
    Route::get('/creditos/pendientes', [PrestamoController::class, 'pendientes'])->name('creditos.pendientes');
    Route::get('/creditos/reportes', [PrestamoController::class, 'reportes'])->name('creditos.reportes');
    Route::get('/prestamos/crear', [PrestamoController::class, 'create'])->name('prestamos.create');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
    Route::put('/prestamos/{id}/aprobar', [PrestamoController::class, 'aprobar'])->name('prestamos.aprobar');
    Route::put('/prestamos/{id}/rechazar', [PrestamoController::class, 'rechazar'])->name('prestamos.rechazar');
    Route::post('/prestamos/{id}/desembolsar', [PrestamoController::class, 'desembolsar'])->name('prestamos.desembolsar');

    Route::get('/prestamos/{id}/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/prestamos/{id}/pagos/crear', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/prestamos/{id}/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::post('/pagos/{id}/marcar-pagado', [PagoController::class, 'marcarPagado'])->name('pagos.marcarPagado');

    Route::get('/municipios/{id}', [UbicacionController::class, 'getMunicipios'])->name('ubicacion.municipios');
    Route::get('/aldeas/{id}', [UbicacionController::class, 'getAldeas'])->name('ubicacion.aldeas');

    Route::get('/capacitacion', [CapacitacionController::class, 'index'])->name('capacitacion.index');
    Route::post('/capacitacion/guardar', [CapacitacionController::class, 'guardar'])->name('capacitacion.guardar');
    Route::post('/capacitaciones/guardar', [CapacitacionController::class, 'store'])->name('capacitacion.store');

    // Auxiliares
    Route::get('/organizacion/{id}/nombre', function ($id) {
        $organizacion = DB::table('tbl_organizaciones')->where('Id_Organizacion', $id)->first();
        return response()->json(['nombre' => $organizacion->Nombre_Organizacion ?? '']);
    });
    Route::get('/actividades/{id}', function ($id) {
        return DB::table('tbl_actividad_economica')
            ->where('Id_Beneficiario', $id)
            ->pluck('Rubro', 'Id_Actividad');
    });
});

// Informe financiero público
Route::get('/informe-financiero', [InformeFinancieroController::class, 'mostrarInforme']);
Route::get('/informe-financiero/export', [InformeFinancieroController::class, 'exportInformeFinancieroExcel'])->name('informe-financiero.export');

// Ruta de prueba
Route::get('/prueba', fn () => view('prueba'));
