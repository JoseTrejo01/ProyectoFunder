<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
<<<<<<< HEAD
=======
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
use Illuminate\Support\Facades\DB;

// Auth
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

<<<<<<< HEAD
// Admin
=======
use App\Http\Controllers\Admin\PermisoController;
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\UsuarioController;
<<<<<<< HEAD

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
=======
use App\Http\Controllers\Admin\ParametroController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\ObjetoController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ExportSociosController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\EmprendimientoController;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\AhorroController;
use App\Http\Controllers\IndicadorGeneroController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\CoordenadasMapaController;
use App\Http\Controllers\ExportSociosPdfController;

>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
use App\Exports\SociosExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

<<<<<<< HEAD
// Página de inicio
Route::get('/', fn () => auth()->check() ? redirect('/dashboard') : view('welcome'))->name('home');

// ===================== RUTAS PARA INVITADOS =====================
=======
Route::get('/', fn () => auth()->check() ? redirect('/dashboard') : view('welcome'))->name('home');

// Invitados
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
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

<<<<<<< HEAD
// ===================== RUTAS PARA USUARIOS AUTENTICADOS =====================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
=======
// Verificación de correo
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Correo verificado correctamente. Ya puedes iniciar sesión.');
    })->middleware(['signed'])->name('verification.verify');
});

// Autenticados y verificados
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cambio de contraseña
    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

<<<<<<< HEAD
    // Permisos y Bitácora
=======
    // Módulos administrativos
    Route::prefix('admin')->group(function () {
        Route::prefix('database')->group(function () {
            Route::get('/', [DatabaseController::class, 'index'])->name('admin.database');
            Route::post('/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
            Route::post('/restore', [DatabaseController::class, 'restore'])->name('admin.database.restore');
        });

        Route::resource('usuarios', UsuarioController::class)->except(['show']);

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
    });

    Route::prefix('parametros')->group(function () {
        Route::get('/', [ParametroController::class, 'index'])->name('parametros.index');
        Route::post('/', [ParametroController::class, 'store'])->name('parametros.store');
        Route::put('/{id}', [ParametroController::class, 'update'])->name('parametros.update');
        Route::delete('/{id}', [ParametroController::class, 'destroy'])->name('parametros.destroy');
    });

>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');

<<<<<<< HEAD
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
=======
    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');
    Route::get('/socios/cargos', [SocioController::class, 'cargosPorCaja'])->name('socios.cargos');
    Route::get('/socios/export', [ExportSociosController::class, 'export'])->name('socios.export');
    Route::get('/socios/export-pdf', [ExportSociosPdfController::class, 'exportPdf'])->name('socios.export-pdf');


>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
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

<<<<<<< HEAD
    // Créditos / préstamos
=======
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
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

<<<<<<< HEAD
    // Ubicación
    Route::get('/municipios/{id}', [UbicacionController::class, 'getMunicipios'])->name('ubicacion.municipios');
    Route::get('/aldeas/{id}', [UbicacionController::class, 'getAldeas'])->name('ubicacion.aldeas');

    // Capacitaciones
    Route::get('/capacitacion', [CapacitacionController::class, 'index'])->name('capacitacion.index');
    Route::post('/capacitacion/guardar', [CapacitacionController::class, 'guardar'])->name('capacitacion.guardar');

    // Auxiliares
    Route::get('/organizacion/{id}/nombre', function ($id) {
        $organizacion = DB::table('tbl_organizaciones')->where('Id_Organizacion', $id)->first();
        return response()->json(['nombre' => $organizacion->Nombre_Organizacion ?? '']);
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
=======
    Route::get('/municipios/{id}', [UbicacionController::class, 'getMunicipios'])->name('ubicacion.municipios');
    Route::get('/aldeas/{id}', [UbicacionController::class, 'getAldeas'])->name('ubicacion.aldeas');
    Route::get('/api/cajas-rurales', [OrganizacionController::class, 'obtenerCajasConSocios']);
});

// Utilidades
Route::get('/prueba', fn () => view('prueba'));
Route::get('/organizaciones/mapa', [OrganizacionController::class, 'vistaMapa'])->name('organizaciones.mapa');
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
