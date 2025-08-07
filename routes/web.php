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
use App\Http\Controllers\InformeFinancieroController; // <- agregado

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
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS ADICIONALES
|--------------------------------------------------------------------------
*/
// Informe financiero público
Route::get('/informe-financiero', [InformeFinancieroController::class, 'mostrarInforme'])->name('informe.financiero.show');
Route::get('/informe-financiero/export', [InformeFinancieroController::class, 'exportInformeFinancieroExcel'])->name('informe.financiero.export');
Route::get('/informe-financiero/pdf', [InformeFinancieroController::class, 'exportInformeFinancieroPDF'])->name('informe.financiero.pdf');

// Ruta de prueba
Route::get('/prueba', fn () => view('prueba'))->name('prueba');

// Alias opcional a dashboard (evita colisión de nombre)
Route::get('/home', function () { return redirect()->route('dashboard'); })->name('home.dashboard');

/*
|--------------------------------------------------------------------------
| RUTAS PARA INVITADOS (no autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Registro
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Recuperación de contraseña con OTP
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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cambio de contraseña
    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

    // Permisos y Bitácora
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');

    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');

    // Gestión (Roles y Objetos)
    Route::post('/roles/store', [GestionController::class, 'storeRol'])->name('roles.store');
    Route::post('/objetos/store', [GestionController::class, 'storeObjeto'])->name('objetos.store');

    // Evaluaciones
    Route::resource('criterio', CriterioController::class);
    Route::resource('evaluacion', EvaluacionController::class);
    Route::get('/evaluaciones/exportar-pdf', [EvaluacionController::class, 'exportPdf'])->name('evaluacion.exportarPDF');

    // Administración de usuarios
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Socios (CRUD excepto show)
    Route::resource('socios', SocioController::class)->except(['show']);
    Route::get('/socios/{id}/ficha', [SocioController::class, 'ficha'])->name('socios.ficha');
    Route::post('/socios/{id}/reactivar', [SocioController::class, 'reactivar'])->name('socios.reactivar');

    // Exportaciones de Socios (Excel y PDF) con filtros
    Route::get('/socios/export', function (Request $request) {
        $filters = $request->only('search', 'genero', 'localidad', 'tipo');
        return Excel::download(new SociosExport($filters), 'socios.xlsx');
    })->name('socios.export');

    Route::get('/socios/export-pdf', function (Request $request) {
        $query = Socio::query()->where('estado', 1);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nombre_Beneficiario', 'like', "%{$search}%")
                  ->orWhere('DNI', 'like', "%{$search}%")
                  ->orWhere('Telefono', 'like', "%{$search}%");
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

    // Ahorros
    Route::get('/ahorros', [AhorroController::class, 'index'])->name('ahorros.index');
    Route::get('/ahorros/create', [AhorroController::class, 'create'])->name('ahorros.create');
    Route::post('/ahorros', [AhorroController::class, 'store'])->name('ahorros.store');

    // APIs Ahorros
    Route::get('/api/ahorros/caja/{id}/resumen', [AhorroController::class, 'resumenCaja'])->name('ahorros.resumen');
    Route::get('/api/ahorros/caja/{id}/socios', [AhorroController::class, 'obtenerSocios'])->name('ahorros.socios');

    // API adicional: contar socios por organización (JSON)
    Route::get('/organizacion/{id}/socios', function ($id) {
        $total = DB::table('tbl_beneficiario')
            ->where('Id_Organizacion', $id)
            ->where('Tipo_De_Socio', 'Socio')
            ->count();

        return response()->json(['total_socios' => $total]);
    })->name('organizacion.socios.count');

    // Indicadores de Género como recurso separado para evitar colisión con /genero
    Route::resource('indicadores-genero', IndicadorGeneroController::class);

    // Emprendimientos y Organizaciones
    Route::resource('emprendimientos', EmprendimientoController::class)->except(['show']);
    Route::get('emprendimientos/export/pdf', [EmprendimientoController::class, 'exportPdf'])->name('emprendimientos.export.pdf');

    Route::resource('organizaciones', OrganizacionController::class)->except(['show']);
    Route::get('/organizaciones/mapa', [OrganizacionController::class, 'vistaMapa'])->name('organizaciones.mapa');

    // Indicadores de género (tablero simple)
    Route::get('/genero', [GeneroController::class, 'index'])->name('genero.index');
    Route::get('/genero/datos', [GeneroController::class, 'obtenerDatos'])->name('genero.datos');
});

/*
|--------------------------------------------------------------------------
| FALLBACK: si la ruta no existe, redirige a home/login
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('home');
});
