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
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\DatabaseController;

use App\Http\Controllers\Admin\ParametroController;


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

    // Logout (accesible por seguridad desde ambos estados)
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



// RUTAS PARA USUARIOS AUTENTICADOS Y VERIFICADOS
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    // Rutas para cambio de contraseña obligatorio
    Route::get('/cambiar-contraseña', [App\Http\Controllers\Auth\LoginController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/cambiar-contraseña', [App\Http\Controllers\Auth\LoginController::class, 'changePassword'])->name('password.change');

    // Pantalla de gestión de base de datos
    Route::get('/admin/database', [DatabaseController::class, 'index'])->name('admin.database');
    Route::post('/admin/database/backup', [DatabaseController::class, 'backup'])->name('admin.database.backup');
    Route::post('/admin/database/restore', [DatabaseController::class, 'restore'])->name('admin.database.restore');
});

Route::get('/asignar-permisos', [PermisoController::class, 'showForm'])->name('asignar.permisos.form');
Route::post('/asignar-permisos', [PermisoController::class, 'asignarPermisos'])->name('asignar.permisos');
Route::get('/ver-bitacora', [BitacoraController::class, 'verBitacora'])->name('ver.bitacora');
Route::post('/ver-bitacora/borrar', [BitacoraController::class, 'borrarBitacora'])->name('bitacora.borrar');


// RUTAS DE MANTENIMIENTO: ROLES Y OBJETOS
Route::middleware(['auth', 'verified'])->group(function () {
    // Roles
    Route::get('/admin/roles', [\App\Http\Controllers\Admin\RolController::class, 'index'])->name('roles.index');
    Route::post('/admin/roles', [\App\Http\Controllers\Admin\RolController::class, 'store'])->name('roles.store');
    Route::put('/admin/roles/{id}', [\App\Http\Controllers\Admin\RolController::class, 'update'])->name('roles.update');
    Route::delete('/admin/roles/{id}', [\App\Http\Controllers\Admin\RolController::class, 'destroy'])->name('roles.destroy');

    // Objetos
    Route::get('/admin/objetos', [\App\Http\Controllers\Admin\ObjetoController::class, 'index'])->name('objetos.index');
    Route::post('/admin/objetos', [\App\Http\Controllers\Admin\ObjetoController::class, 'store'])->name('objetos.store');
    Route::put('/admin/objetos/{id}', [\App\Http\Controllers\Admin\ObjetoController::class, 'update'])->name('objetos.update');
    Route::delete('/admin/objetos/{id}', [\App\Http\Controllers\Admin\ObjetoController::class, 'destroy'])->name('objetos.destroy');
});


Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::post('/admin/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');


Route::get('/parametros', [ParametroController::class, 'index'])->name('parametros.index');
Route::post('/parametros', [ParametroController::class, 'store'])->name('parametros.store');
Route::put('/parametros/{id}', [ParametroController::class, 'update'])->name('parametros.update');
Route::delete('/parametros/{id}', [ParametroController::class, 'destroy'])->name('parametros.destroy');
