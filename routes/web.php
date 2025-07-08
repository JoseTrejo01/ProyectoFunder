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

// Ruta de bienvenida - redirige usuarios autenticados al dashboard
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : view('welcome');
})->name('home');

// ========================== RUTAS PARA INVITADOS ==========================
Route::middleware('guest')->group(function () {
    // Registro
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Logout accesible desde ambos estados por seguridad
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Recuperación de contraseña con OTP
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/reset', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('otp.send');

    Route::get('password/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('otp.form');
    Route::post('password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');

    Route::get('password/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('password/reset-password', [ResetPasswordController::class, 'reset'])->name('otp.reset.password');

    Route::get('password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('otp.resend');

    // Verificación de correo
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill(); // Marca el correo como verificado
        Auth::logout(); // Cierra la sesión
        return redirect()->route('login')->with('success', 'Correo verificado correctamente. Ya puedes iniciar sesión.');
    })->middleware(['signed'])->name('verification.verify');
});

// ====================== RUTAS PARA USUARIOS AUTENTICADOS ======================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout (por si aún en sesión)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Cambio obligatorio de contraseña
    Route::get('/cambiar-contraseña', [LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [LoginController::class, 'changePassword'])->name('password.change');

    // Gestión de base de datos
    Route::get('/admin/database', [DatabaseController::class, 'index'])->name('admin.database');
    Route::post('/admin/database/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
    Route::post('/admin/database/restore', [DatabaseController::class, 'restore'])->name('admin.database.restore');

    // Gestión de roles
    Route::prefix('admin/roles')->group(function () {
        Route::get('/', [RolController::class, 'index'])->name('roles.index');
        Route::post('/', [RolController::class, 'store'])->name('roles.store');
        Route::put('/{id}', [RolController::class, 'update'])->name('roles.update');
        Route::delete('/{id}', [RolController::class, 'destroy'])->name('roles.destroy');
    });

    // Gestión de objetos
    Route::prefix('admin/objetos')->group(function () {
        Route::get('/', [ObjetoController::class, 'index'])->name('objetos.index');
        Route::post('/', [ObjetoController::class, 'store'])->name('objetos.store');
        Route::put('/{id}', [ObjetoController::class, 'update'])->name('objetos.update');
        Route::delete('/{id}', [ObjetoController::class, 'destroy'])->name('objetos.destroy');
    });
});

// ======================== RUTAS ADMINISTRATIVAS ===========================
Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

Route::get('/parametros', [ParametroController::class, 'index'])->name('parametros.index');
Route::post('/parametros', [ParametroController::class, 'store'])->name('parametros.store');
Route::put('/parametros/{id}', [ParametroController::class, 'update'])->name('parametros.update');
Route::delete('/parametros/{id}', [ParametroController::class, 'destroy'])->name('parametros.destroy');

// Permisos y Bitácora
Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');

Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');
