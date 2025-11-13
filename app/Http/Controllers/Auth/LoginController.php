<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Parametro;
use App\Models\Objeto;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'Usuario'     => ['required', 'string', 'max:30'],
            'Contraseña'  => ['required', 'string', 'size:8', 'regex:/^\S*$/u']
        ], [
            'Usuario.required'    => 'El campo usuario es obligatorio',
            'Usuario.max'         => 'El usuario no puede tener más de 30 caracteres.',
            'Contraseña.required' => 'El campo contraseña es obligatorio',
            'Contraseña.size'     => 'La contraseña debe tener exactamente 8 caracteres.',
            'Contraseña.regex'    => 'La contraseña no puede contener espacios'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Normalizar usuario
        $usuario = strtoupper($request->Usuario);
        $user = User::where('Usuario', $usuario)->first();

        // Usuario no existe
        if (!$user) {
            return back()->withErrors(['Usuario' => 'Usuario/contraseña inválidos'])->withInput();
        }

        // Límite de intentos fallidos desde parámetros (por defecto 3)
        $limiteIntentos = Parametro::where('Nombre_Parametro', 'ADMIN_INTENTOS_INVALIDOS')->value('Valor') ?? 3;

        // Verificar si el usuario está bloqueado
        if (strtoupper(trim($user->Estado_Usuario)) === 'BLOQUEADO') {
            return back()->withErrors([
                'Usuario' => 'Tu cuenta ha sido bloqueada por múltiples intentos fallidos.'
            ])->withInput();
        }

        // Verificar si el usuario está pendiente de aprobación
        if ($user->Id_Rol == 3) {
            return back()->withErrors([
                'Usuario' => 'Tu usuario está pendiente de aprobación. Contacta a la administración.'
            ])->withInput();
        }

        // Verificar si el usuario está activo o es nuevo
        if (!in_array(strtoupper(trim($user->Estado_Usuario)), ['ACTIVO', 'NUEVO'])) {
            return back()->withErrors([
                'Usuario' => 'El usuario no está activo'
            ])->withInput();
        }

        // Verificar contraseña
        if (!Hash::check($request->Contraseña, $user->Contraseña)) {

            // === MANEJO DE INTENTOS FALLIDOS ===
            $user->Intentos_Fallidos = ($user->Intentos_Fallidos ?? 0) + 1;

            if ($user->Intentos_Fallidos >= $limiteIntentos) {
                $user->Estado_Usuario = 'BLOQUEADO';

                // Registrar en bitácora el bloqueo
                if (function_exists('EVENT_BITACORA')) {
                    $objeto = Objeto::where('Objeto', 'Login')->first();
                    EVENT_BITACORA(
                        $user->Id_Usuario,
                        $objeto->Id_Objeto ?? 1,
                        'Bloqueo',
                        'Cuenta bloqueada por intentos fallidos de inicio de sesión'
                    );
                }
            }

            $user->save();

            return back()->withErrors([
                'Usuario' => 'Usuario/contraseña inválidos'
            ])->withInput();
        }

        // === LOGIN CORRECTO: resetear intentos fallidos ===
        $user->Intentos_Fallidos = 0;
        $user->save();

        // Inicio de sesión exitoso
        Auth::login($user);
        $request->session()->regenerate();

        // Manejar primer ingreso (cambio de contraseña obligatorio)
        if ($user->Primer_Ingreso == 1) {
            $user->Primer_Ingreso = 0;
            $user->save();
            return redirect()->route('password.change');
        }

        // Bitácora de ingreso (dejas tu registro tal cual)
        EVENT_BITACORA($user->Id_Usuario, 1, 'Ingreso', 'El usuario ha iniciado sesión.');

        // 🔸 NUEVO: marcar que venimos de un login para que el Dashboard
        // NO registre "Ingreso" cuando entremos por el redirect inicial
        $request->session()->put('skip_dashboard_log', true);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        EVENT_BITACORA(Auth::user()->Id_Usuario, 2, 'Salida', 'El usuario ha cerrado sesión.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }

    public function showChangePasswordForm()
    {
        return view('Auth.passwords.cambiar-contraseña');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->Contraseña = Hash::make($request->password);
        $user->Estado_Usuario = 'ACTIVO';
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Contraseña cambiada correctamente. Por favor, inicia sesión.');
    }
}
