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

use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SociosExport;
use Maatwebsite\Excel\Facades\Excel;

// Ruta de bienvenida - redirige usuarios autenticados al dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
})->name('home');

// RUTAS PARA USUARIOS NO AUTENTICADOS
Route::middleware('guest')->group(function () {

    // Registro
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Logout
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

    // Vista para verificar el correo
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Ruta que se llama al hacer clic en el enlace del correo
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Marca el correo como verificado

    Auth::logout(); // Cierra la sesión

    return redirect()->route('login')->with('success', 'Correo verificado correctamente. Ya puedes iniciar sesión.');

// RUTAS PARA USUARIOS AUTENTICADOS Y VERIFICADOS
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

    // cambio de contraseña obligatorio
    Route::get('/cambiar-contraseña', [App\Http\Controllers\Auth\LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [App\Http\Controllers\Auth\LoginController::class, 'changePassword'])->name('password.change');

    // permisos, bitácora, roles, objetos
    Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
    Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
    Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
    Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');
    Route::post('/roles/store', [GestionController::class, 'storeRol'])->name('roles.store');
    Route::post('/objetos/store', [GestionController::class, 'storeObjeto'])->name('objetos.store');
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // rutas de socios
    Route::resource('socios', App\Http\Controllers\SocioController::class)->except(['show']);

    // ficha del socio
    Route::get('/socios/{id}/ficha', [App\Http\Controllers\SocioController::class, 'ficha'])->name('socios.ficha');

    // exportar socios a excel
    Route::get('/socios/export', function (Request $request) {
        $filters = $request->only('search','genero','localidad','tipo');
        return Excel::download(new SociosExport($filters), 'socios.xlsx');
    })->name('socios.export');

    // exportar socios a pdf
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
});
Route::get('/socios/export', [App\Http\Controllers\ExportSociosController::class, 'export'])->name('socios.export');
//reactivas socios
Route::post('/socios/{id}/reactivar', [App\Http\Controllers\SocioController::class, 'reactivar'])->name('socios.reactivar');
