<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\UsuarioController;
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

use App\Exports\SociosExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', fn () => auth()->check() ? redirect('/dashboard') : view('welcome'))->name('home');

// Invitados
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
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

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

    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');

    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');
    Route::get('/socios/cargos', [SocioController::class, 'cargosPorCaja'])->name('socios.cargos');
    Route::get('/socios/export', [ExportSociosController::class, 'export'])->name('socios.export');
    Route::get('/socios/export-pdf', [ExportSociosPdfController::class, 'exportPdf'])->name('socios.export-pdf');

    Route::resource('ahorros', AhorroController::class);
    Route::get('/ahorros/{id}/ficha', [AhorroController::class, 'ficha'])->name('ahorros.ficha');
    Route::get('/ahorros/export-pdf', [AhorroController::class, 'exportPdf'])->name('ahorros.export-pdf');
    Route::get('/organizacion/{id}/socios', fn ($id) => response()->json([
        'total_socios' => DB::table('tbl_beneficiario')->where('Id_Organizacion', $id)->where('Tipo_De_Socio', 'Socio')->count()
    ]));
    Route::get('/organizacion/{id}/contar-socios', [AhorroController::class, 'contarSocios']);
    Route::get('/api/cajas/{id}/resumen', [AhorroController::class, 'resumen']);

    Route::resource('genero', IndicadorGeneroController::class);
    Route::resource('emprendimientos', EmprendimientoController::class);
    Route::resource('organizaciones', OrganizacionController::class)->except(['show']);

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

    Route::get('/municipios/{id}', [UbicacionController::class, 'getMunicipios'])->name('ubicacion.municipios');
    Route::get('/aldeas/{id}', [UbicacionController::class, 'getAldeas'])->name('ubicacion.aldeas');
    Route::get('/api/cajas-rurales', [OrganizacionController::class, 'obtenerCajasConSocios']);
});

// Utilidades
Route::get('/prueba', fn () => view('prueba'));
Route::get('/organizaciones/mapa', [OrganizacionController::class, 'vistaMapa'])->name('organizaciones.mapa');
