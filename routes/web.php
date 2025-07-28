<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Auth
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Admin
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\UsuarioController;

// Módulos principales
use App\Http\Controllers\AhorroController;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\CoordenadasMapaController;
use App\Http\Controllers\EmprendimientoController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\ExportEvaluacionesController;
use App\Http\Controllers\ExportSociosController;
use App\Http\Controllers\ExportSociosPdfController;
use App\Http\Controllers\IndicadorGeneroController;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\CriterioController;

// Librerías externas
use Barryvdh\DomPDF\Facade\Pdf;
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

// ===================== RUTAS PARA USUARIOS AUTENTICADOS =====================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cambio de contraseña
    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

    // Gestión de permisos y bitácora
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');

    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');

    // Gestión de roles y objetos
    Route::post('/roles/store', [GestionController::class, 'storeRol'])->name('roles.store');
    Route::post('/objetos/store', [GestionController::class, 'storeObjeto'])->name('objetos.store');

    // Usuarios
    Route::resource('admin/usuarios', UsuarioController::class)->except(['show', 'create', 'edit']);

    // Criterios y Evaluaciones
    Route::resource('criterio', CriterioController::class);
    Route::resource('evaluacion', EvaluacionController::class);
    Route::get('/evaluacion/exportar-excel', [ExportEvaluacionesController::class, 'export'])->name('evaluacion.export');

    // Socios
    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');
    Route::get('/socios/export', function (Request $request) {
        $filters = $request->only('search', 'genero', 'localidad', 'tipo');
        return Excel::download(new SociosExport($filters), 'socios.xlsx');
    })->name('socios.export');
    Route::get('/socios/export-pdf', [ExportSociosPdfController::class, 'exportPdf'])->name('socios.export-pdf');

    // Ahorros
    Route::resource('ahorros', AhorroController::class);
    Route::get('/ahorros/{id}/ficha', [AhorroController::class, 'ficha'])->name('ahorros.ficha');
    Route::get('/ahorros/export-pdf', [AhorroController::class, 'exportPdf'])->name('ahorros.export-pdf');
    Route::get('/ahorros/caja/{id}/listado', [AhorroController::class, 'listarPorCaja']);
    Route::get('/api/organizacion/{id}/beneficiarios', [AhorroController::class, 'sociosPorCaja']);
    Route::get('/organizacion/{id}/contar-socios', [AhorroController::class, 'contarSocios']);
    Route::get('/api/cajas/{id}/resumen', [AhorroController::class, 'resumen']);
    Route::get('/api/ahorros/caja/{id}/socios', [AhorroController::class, 'obtenerSocios'])->name('ahorros.socios');

    // Género, emprendimientos y organizaciones
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

    // Créditos / préstamos
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
    Route::post('/pagos/{id}/marcar-pagado', [PagoController::class, 'marcarPagado'])->name('pagos.marcarPagado');

    // Ubicación
    Route::get('/municipios/{id}', [UbicacionController::class, 'getMunicipios'])->name('ubicacion.municipios');
    Route::get('/aldeas/{id}', [UbicacionController::class, 'getAldeas'])->name('ubicacion.aldeas');

    // Capacitaciones
    Route::get('/capacitacion', [CapacitacionController::class, 'index'])->name('capacitacion.index');
    Route::post('/capacitacion/guardar', [CapacitacionController::class, 'guardar'])->name('capacitacion.guardar');

    // Auxiliar
    Route::get('/organizacion/{id}/nombre', function ($id) {
        $organizacion = DB::table('tbl_organizaciones')
            ->where('Id_Organizacion', $id)
            ->first();
        return response()->json([
            'nombre' => $organizacion->Nombre_Organizacion ?? ''
        ]);
    });

    Route::get('/actividades/{id}', function ($id) {
        $actividades = DB::table('tbl_actividad_economica')
            ->where('Id_Beneficiario', $id)
            ->pluck('Rubro', 'Id_Actividad');
        return response()->json($actividades);
    });
});

// Ruta de prueba
Route::get('/prueba', fn () => view('prueba'));
