@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('adminlte_css_pre')
<style>

    /* -------------------- FONDO -------------------- */
    body {
        position: relative;
        min-height: 100vh;
        background: url('{{ asset('images/funder2.png') }}') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1.5rem;
        font-family: 'Segoe UI', sans-serif;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 0;
    }

    /* -------------------- TARJETA -------------------- */
    .login-card, .register-logo {
        position: relative;
        z-index: 2;
    }

    .login-card {
        width: 100%;
        max-width: 480px;
        background-color: rgba(255,255,255,0.92);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 12px 40px rgba(0,0,0,0.25);
        animation: fadeInUp .8s ease forwards;
        opacity: 0;
        transform: translateY(25px);
    }

    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* -------------------- LOGO -------------------- */
    .register-logo img {
        width: 160px;
        max-width: 45vw;
        margin-bottom: 12px;
        filter: drop-shadow(0 3px 5px rgba(0,0,0,0.4));
    }

    /* -------------------- INPUTS -------------------- */
    .input-group-text {
        background-color: #eaf4ec;
        color: #3a5f3a;
        border-radius: 0 10px 10px 0;
    }

    .form-control {
        border-radius: 10px 0 0 10px;
        border: 1px solid rgb(48,75,102);
        padding: 0.75rem 1rem;
    }

    .form-control:focus {
        border-color: #000;
        box-shadow: 0 0 0 .2rem rgba(91,142,62,0.25);
    }

    #Usuario, #Nombre_Usuario {
        text-transform: uppercase;
    }

    /* -------------------- BOTÓN -------------------- */
    .btn-primary {
        background-color: #5B8E3E !important;
        border-color: #5B8E3E !important;
        border-radius: 0.75rem;
        font-weight: bold;
        font-size: 1.05rem;
        transition: .25s ease;
    }

    .btn-primary:hover {
        background-color: #2e4b2e !important;
        transform: translateY(-2px);
    }

    /* -------------------- RESPONSIVE -------------------- */

    /* Celulares pequeños */
    @media (max-width: 420px) {
        .login-card {
            padding: 1.3rem;
            border-radius: 1rem;
        }
        .register-logo img {
            width: 120px;
        }
        .form-control {
            font-size: .9rem;
            padding: .6rem .9rem;
        }
        .btn-primary {
            font-size: .9rem;
        }
    }

    /* Tablets */
    @media (min-width: 768px) and (max-width: 1024px) {
        .login-card {
            max-width: 520px;
            padding: 2.3rem;
        }
        .register-logo img {
            width: 190px;
        }
    }

    /* Monitores grandes */
    @media (min-width: 1440px) {
        .login-card {
            max-width: 500px;
        }
    }

    /* MODO OSCURO */
    @media (prefers-color-scheme: dark) {
        .login-card {
            background-color: rgba(25,25,25,0.85);
            color: #fff;
        }
        .form-control {
            background: #222;
            color: #fff;
            border-color: #666;
        }
        .input-group-text {
            background-color: #2ed473;
            color: #000;
        }
    }

</style>
@stop

@section('auth_header')
<div class="register-logo text-center">
    <img src="{{ asset('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo Funder">
</div>
<h4 class="text-center fw-bold text-success">Registro</h4>
@stop

@section('auth_body')
<form action="{{ route('register') }}" method="post">
    @csrf

    <div class="form-animated-box">

        {{-- Usuario --}}
        <div class="input-group mb-3">
            <input type="text" name="Usuario" id="Usuario" maxlength="30"
                   class="form-control @error('Usuario') is-invalid @enderror"
                   value="{{ old('Usuario') }}" placeholder="Usuario" required autofocus>
            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
            @error('Usuario') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
        </div>

        {{-- Nombre --}}
        <div class="input-group mb-3">
            <input type="text" name="Nombre_Usuario" id="Nombre_Usuario" maxlength="40"
                   class="form-control @error('Nombre_Usuario') is-invalid @enderror"
                   placeholder="Nombre completo" required>
            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user-tag"></span></div></div>
        </div>

        {{-- Correo --}}
        <div class="input-group mb-3">
            <input type="email" name="Correo_Electronico" id="Correo_Electronico"
                   class="form-control @error('Correo_Electronico') is-invalid @enderror"
                   placeholder="Correo Electrónico" required>
            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>

            <div class="invalid-feedback d-none" id="correo-error-registro">
                Solo se permiten correos gmail.com o hotmail.com
            </div>
        </div>

        {{-- Contraseña --}}
        <div class="input-group mb-3">
            <input type="password" name="Contraseña" id="Contraseña" maxlength="8"
                   class="form-control @error('Contraseña') is-invalid @enderror"
                   placeholder="Contraseña" required>

            <div class="input-group-append">
                <div class="input-group-text" id="togglePassword" style="cursor:pointer;">
                    <span class="fas fa-eye" id="eyeIcon"></span>
                </div>
            </div>
        </div>

        {{-- Confirmar --}}
        <div class="input-group mb-3">
            <input type="password" name="Contraseña_confirmation" id="Contraseña_confirmation"
                   maxlength="8" class="form-control" placeholder="Confirmar contraseña" required>

            <div class="input-group-append">
                <div class="input-group-text" id="togglePasswordConfirm" style="cursor:pointer;">
                    <span class="fas fa-eye" id="eyeIconConfirm"></span>
                </div>
            </div>
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>

    </div>
</form>
@stop

@section('auth_footer')
<div class="mt-3 text-center">
    <a href="{{ route('login') }}" class="text-primary">
        <i class="fas fa-arrow-left"></i> ¿Ya tienes una cuenta?
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* -------------------- Ojitos -------------------- */
    const toggle = (btnId, inputId, iconId) => {
        document.getElementById(btnId).addEventListener('click', () => {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            const isPass = input.type === "password";

            input.type = isPass ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash', isPass);
            icon.classList.toggle('fa-eye', !isPass);
        });
    };

    toggle('togglePassword','Contraseña','eyeIcon');
    toggle('togglePasswordConfirm','Contraseña_confirmation','eyeIconConfirm');

    /* -------------------- Validación de correo -------------------- */
    const correo = document.getElementById('Correo_Electronico');
    const error  = document.getElementById('correo-error-registro');
    correo.addEventListener('input', () => {
        let val = correo.value.toLowerCase();
        let ok  = val.endsWith("@gmail.com") || val.endsWith("@hotmail.com");

        correo.classList.toggle("is-invalid", !ok);
        error.classList.toggle("d-none", ok);
    });

    /* -------------------- Bloqueo copy/paste -------------------- */
    ['Usuario','Nombre_Usuario'].forEach(id => {
        const el = document.getElementById(id);
        ['paste','copy','cut','drop','dragstart'].forEach(evt =>
            el.addEventListener(evt, e => e.preventDefault())
        );
    });

});
</script>
@stop
