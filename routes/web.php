<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\BitacoraController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\UsuarioController;


// Ruta de bienvenida - redirige usuarios autenticados al dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
})->name('home');

// Rutas para usuarios NO autenticados (guest)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Rutas de recuperación de contraseña
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    //Rutas dependiendo el rol
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'general'])->name('dashboard');
   
});

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