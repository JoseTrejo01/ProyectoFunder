<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\ParametroController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\ObjetoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ExportSociosController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\PagoController;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SociosExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\EmprendimientoController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\ExportEvaluacionesController;
use App\Http\Controllers\CriterioController;
// Página de bienvenida
Route::get('/', fn () => auth()->check() ? redirect('/dashboard') : view('welcome'))->name('home');

// =================== RUTAS PARA INVITADOS ===================
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Recuperación con OTP
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/reset', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('otp.send');
    Route::get('password/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('otp.form');
    Route::post('password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');
    Route::get('password/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('password/reset-password', [ResetPasswordController::class, 'reset'])->name('otp.reset.password');
    Route::get('password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('otp.resend');

    // Verificación de correo
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Correo verificado correctamente. Ya puedes iniciar sesión.');
    })->middleware(['signed'])->name('verification.verify');
});

// ================ RUTAS PARA USUARIOS AUTENTICADOS =================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cambio obligatorio de contraseña
    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

    // Base de datos
    Route::prefix('admin/database')->group(function () {
        Route::get('/', [DatabaseController::class, 'index'])->name('admin.database');
        Route::post('/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
        Route::post('/restore', [DatabaseController::class, 'restore'])->name('admin.database.restore');
    });

    // Roles
    Route::prefix('admin/roles')->group(function () {
        Route::get('/', [RolController::class, 'index'])->name('roles.index');
        Route::post('/', [RolController::class, 'store'])->name('roles.store');
        Route::put('/{id}', [RolController::class, 'update'])->name('roles.update');
        Route::delete('/{id}', [RolController::class, 'destroy'])->name('roles.destroy');
        Route::post('/store', [GestionController::class, 'storeRol'])->name('roles.store.gestion');
    });

    // Objetos
    Route::prefix('admin/objetos')->group(function () {
        Route::get('/', [ObjetoController::class, 'index'])->name('objetos.index');
        Route::post('/', [ObjetoController::class, 'store'])->name('objetos.store');
        Route::put('/{id}', [ObjetoController::class, 'update'])->name('objetos.update');
        Route::delete('/{id}', [ObjetoController::class, 'destroy'])->name('objetos.destroy');
        Route::post('/store', [GestionController::class, 'storeObjeto'])->name('objetos.store.gestion');
    });

    // Permisos y bitácora
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');

    // Usuarios
    Route::prefix('admin/usuarios')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    });

    // Parámetros
    Route::prefix('parametros')->group(function () {
        Route::get('/', [ParametroController::class, 'index'])->name('parametros.index');
        Route::post('/', [ParametroController::class, 'store'])->name('parametros.store');
        Route::put('/{id}', [ParametroController::class, 'update'])->name('parametros.update');
        Route::delete('/{id}', [ParametroController::class, 'destroy'])->name('parametros.destroy');
    });

    // Criterio
    Route::resource('criterio', CriterioController::class);

    // Evaluación (CRUD)
    Route::resource('evaluacion', EvaluacionController::class);
   Route::get('dashboard/chart-data', [DashboardController::class, 'chartData'])
     ->name('dashboard.chart-data');

    // Exportación a Excel 
    Route::get('/evaluacion/exportar-excel', [ExportEvaluacionesController::class, 'export'])->name('evaluacion.export');


    // Socios
    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');

    // Emprendimientos
    Route::resource('emprendimientos', EmprendimientoController::class);

    // AJAX: Selects dependientes de ubicación
    Route::get('/municipios/{id}', [UbicacionController::class, 'getMunicipios'])->name('ubicacion.municipios');
    Route::get('/aldeas/{id}', [UbicacionController::class, 'getAldeas'])->name('ubicacion.aldeas');

    // Exportación socios
    Route::get('/socios/export', fn (Request $request) => Excel::download(new SociosExport($request->only('search', 'genero', 'localidad', 'tipo')), 'socios.xlsx'))->name('socios.export');

    Route::get('/socios/export-pdf', function (Request $request) {
        $query = \App\Models\Socio::query()->where('estado', 1);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('Nombre_Beneficiario', 'like', "%{$request->search}%")
                  ->orWhere('DNI', 'like', "%{$request->search}%")
                  ->orWhere('Telefono', 'like', "%{$request->search}%");
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

    // Préstamos
    Route::get('/creditos', [PrestamoController::class, 'index'])->name('creditos');
    Route::get('/prestamos/crear', [PrestamoController::class, 'create'])->name('prestamos.create');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamos.store');
    Route::get('/creditos/pendientes', [PrestamoController::class, 'pendientes'])->name('creditos.pendientes');
    Route::put('/prestamos/{id}/aprobar', [PrestamoController::class, 'aprobar'])->name('prestamos.aprobar');
    Route::put('/prestamos/{id}/rechazar', [PrestamoController::class, 'rechazar'])->name('prestamos.rechazar');
    Route::post('/prestamos/{id}/desembolsar', [PrestamoController::class, 'desembolsar'])->name('prestamos.desembolsar');

    // Pagos
    Route::get('/prestamos/{id}/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/prestamos/{id}/pagos/crear', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/prestamos/{id}/pagos', [PagoController::class, 'store'])->name('pagos.store');
});
