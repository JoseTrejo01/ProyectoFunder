<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Controllers - Auth
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Controllers - Admin
use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ParametroController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\ObjetoController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\ExportReportesController;

// Controllers - Módulos
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AhorroController;
use App\Http\Controllers\IndicadorGeneroController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\CriterioController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\EmprendimientoController;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\InformeFinancieroController;

// Models
use App\Models\Socio;

// Export / PDF
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SociosExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| RUTA PÚBLICA
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS ADICIONALES
|--------------------------------------------------------------------------
*/
Route::get('/informe-financiero', [InformeFinancieroController::class, 'mostrarInforme'])->name('informe.financiero.show');
Route::get('/informe-financiero/export', [InformeFinancieroController::class, 'exportInformeFinancieroExcel'])->name('informe.financiero.export');
Route::get('/informe-financiero/pdf', [InformeFinancieroController::class, 'exportInformeFinancieroPDF'])->name('informe.financiero.pdf');

Route::get('/prueba', fn () => view('prueba'))->name('prueba');
Route::get('/home', fn () => redirect()->route('dashboard'))->name('home.dashboard');

/*
|--------------------------------------------------------------------------
| RUTAS PARA INVITADOS
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/reset', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('otp.send');

    Route::get('password/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('otp.form');
    Route::post('password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');

    Route::get('password/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('password/reset-password', [ResetPasswordController::class, 'reset'])->name('otp.reset.password');

    Route::get('password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('otp.resend');
});

/*
|--------------------------------------------------------------------------
| RUTAS AUTENTICADAS + VERIFICADAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard y logout
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
        Route::get('/usuarios/exportar/pdf', [UsuarioController::class, 'exportarPDF'])->name('usuarios.exportar.pdf');

        Route::get('/database', [DatabaseController::class, 'index'])->name('admin.database');
        Route::post('/database/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
        Route::post('/database/restore', [DatabaseController::class, 'restore'])->name('admin.database.restore');

        Route::prefix('roles')->group(function () {
            Route::get('/', [RolController::class, 'index'])->name('roles.index');
            Route::post('/', [RolController::class, 'store'])->name('roles.store');
            Route::put('/{id}', [RolController::class, 'update'])->name('roles.update');
            Route::delete('/{id}', [RolController::class, 'destroy'])->name('roles.destroy');
            Route::post('/store', [GestionController::class, 'storeRol'])->name('roles.store.gestion');
        });
        Route::get('/roles/exportar/pdf', [RolController::class, 'exportarPDF'])->name('roles.exportar.pdf');

        Route::prefix('objetos')->group(function () {
            Route::get('/', [ObjetoController::class, 'index'])->name('objetos.index');
            Route::post('/', [ObjetoController::class, 'store'])->name('objetos.store');
            Route::put('/{id}', [ObjetoController::class, 'update'])->name('objetos.update');
            Route::delete('/{id}', [ObjetoController::class, 'destroy'])->name('objetos.destroy');
            Route::post('/store', [GestionController::class, 'storeObjeto'])->name('objetos.store.gestion');
            Route::get('/exportar-pdf', [ObjetoController::class, 'exportarPDF'])->name('objetos.exportar-pdf');
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

    // Permisos y bitácora
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');
    Route::get('/bitacora/exportar/pdf', [BitacoraController::class, 'exportarPDF'])->name('bitacora.exportar.pdf');

    // Evaluaciones
    Route::resource('criterio', CriterioController::class);
    Route::resource('evaluacion', EvaluacionController::class);
    Route::get('/evaluaciones/exportar-pdf', [EvaluacionController::class, 'exportPdf'])->name('evaluacion.exportarPDF');

    // Usuarios (gestión directa)
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Socios
    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');

    Route::get('/socios/export', function (Request $request) {
        return Excel::download(new SociosExport($request->only('search', 'genero', 'localidad', 'tipo')), 'socios.xlsx');
    })->name('socios.export');

    Route::get('/socios/export-pdf', function (Request $request) {
        $query = Socio::query()->where('estado', 1);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('Nombre_Beneficiario', 'like', "%{$request->search}%")
                  ->orWhere('DNI', 'like', "%{$request->search}%")
                  ->orWhere('Telefono', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('genero')) $query->where('genero', $request->genero);
        if ($request->filled('localidad')) $query->where('direccion', 'like', "%{$request->localidad}%");
        if ($request->filled('tipo')) $query->where('Tipo_De_Socio', 'like', "%{$request->tipo}%");

        return Pdf::loadView('socios.pdf', ['socios' => $query->get()])->download('socios.pdf');
    })->name('socios.export-pdf');

    // Ahorros
    Route::get('/ahorros', [AhorroController::class, 'index'])->name('ahorros.index');
    Route::get('/ahorros/create', [AhorroController::class, 'create'])->name('ahorros.create');
    Route::post('/ahorros', [AhorroController::class, 'store'])->name('ahorros.store');
    Route::get('/api/ahorros/caja/{id}/resumen', [AhorroController::class, 'resumenCaja'])->name('ahorros.resumen');
    Route::get('/api/ahorros/caja/{id}/socios', [AhorroController::class, 'obtenerSocios'])->name('ahorros.socios');

    // Conteo de socios
    Route::get('/organizacion/{id}/socios', fn ($id) =>
        response()->json(['total_socios' =>
            DB::table('tbl_beneficiario')->where('Id_Organizacion', $id)->where('Tipo_De_Socio', 'Socio')->count()
        ])
    )->name('organizacion.socios.count');

    // Indicadores de Género
    Route::resource('indicadores-genero', IndicadorGeneroController::class);

    // Emprendimientos y Organizaciones
    Route::resource('emprendimientos', EmprendimientoController::class)->except(['show']);
    Route::get('emprendimientos/export/pdf', [EmprendimientoController::class, 'exportPdf'])->name('emprendimientos.export.pdf');
    Route::resource('organizaciones', OrganizacionController::class)->except(['show']);
    Route::get('/organizaciones/mapa', [OrganizacionController::class, 'vistaMapa'])->name('organizaciones.mapa');
<<<<<<< HEAD
=======
    Route::get('/organizaciones/exportar/pdf', [OrganizacionController::class, 'exportarPDF'])->name('organizaciones.exportar.pdf');
    Route::get('/api/cajas/{id}/socios', function ($id) {
        return App\Models\Socio::select('Id_Beneficiario', 'Nombre_Beneficiario as Nombre')
            ->where('Id_Organizacion', $id)
            ->where('estado', 1)
            ->get();
    });
    Route::get('/api/cajas-rurales', [OrganizacionController::class, 'obtenerCajasConSocios']);
>>>>>>> ce587a70e838cb449571acb0003f38fa1aa4bbf5

    // Tablero de género
    Route::get('/genero', [GeneroController::class, 'index'])->name('genero.index');
    Route::get('/genero/datos', [GeneroController::class, 'obtenerDatos'])->name('genero.datos');
});

/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(fn () => redirect()->route('home'));
