@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
    /* -------------------- FONDO + BLUR -------------------- */
    body {
        position: relative;
        min-height: 100vh;
        background: url('{{ asset('images/funder2.png') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
    }
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 0;
    }

    /* -------------------- TARJETA LOGIN -------------------- */
    .login-card, .login-logo { position: relative; z-index: 2; }

    .login-card {
        width: 100%;
        max-width: 420px;
        background-color: rgba(255,255,255,0.92);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 12px 40px rgba(0,0,0,0.35);
        animation: fadeInUp .8s ease forwards;
        opacity: 0;
        transform: translateY(25px);
    }

    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    @keyframes shake { 0%,100%{transform:translateX(0);} 20%,60%{transform:translateX(-10px);} 40%,80%{transform:translateX(10px);} }

    .shake { animation: shake .4s ease; }

    .login-logo img {
        width: 110px;
        max-width: 40vw;
        margin-bottom: 10px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));
        animation: fadeInLogo 1s ease;
    }

    @keyframes fadeInLogo {
        from { opacity: 0; transform: scale(0.9); }
        to   { opacity: 1; transform: scale(1); }
    }

    /* -------------------- INPUTS -------------------- */
    #Usuario { text-transform: uppercase; }

    .input-group-text {
        background-color: #000;
        color: #fff;
        border: none;
        border-radius: 0.5rem 0 0 0.5rem;
    }

    .form-control {
        border: 1px solid rgb(48,75,102);
        border-radius: 0 0.5rem 0.5rem 0;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: border-color .2s, background .2s;
    }

    .form-control:focus {
        border-color: #000;
        box-shadow: 0 0 0 .2rem rgba(91,142,62,0.25);
    }

    .valid-input { border-color: #2ecc71 !important; background: #eafaf1; }
    .invalid-input { border-color: #e74c3c !important; background: #fdecea; }

    #capsWarning {
        font-size: .85rem;
        color: #e74c3c;
        margin-top: 5px;
        display: none;
    }

    /* -------------------- BOTONES -------------------- */
    .btn-primary {
        background-color: #5B8E3E !important;
        border-color: #5B8E3E !important;
        border-radius: .75rem;
        font-weight: bold;
        transition: .2s ease;
    }

    .btn-primary:hover {
        background-color: #2ed473 !important;
        transform: translateY(-2px);
    }

    /* -------------------- RESPONSIVE -------------------- */

    /* Móviles muy pequeños */
    @media (max-width: 380px) {
        .login-card {
            padding: 1.4rem;
            border-radius: 1rem;
        }
        .login-logo img {
            width: 85px;
        }
        .form-control {
            font-size: 0.9rem;
            padding: 0.65rem;
        }
        .btn-primary {
            font-size: 0.9rem;
        }
    }

    /* Tablets */
    @media (min-width: 768px) and (max-width: 1024px) {
        .login-card {
            max-width: 480px;
            padding: 2.3rem;
        }
        .login-logo img {
            width: 130px;
        }
    }

    /* Monitores grandes */
    @media (min-width: 1400px) {
        .login-card {
            max-width: 450px;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .login-card {
            background-color: rgba(20,20,20,0.85);
            color: #fff;
        }
        .form-control {
            background: #222;
            color: #fff;
            border-color: #555;
        }
        .form-control:focus {
            border-color: #2ed473;
            box-shadow: 0 0 0 .2rem rgba(46,212,115,0.25);
        }
        .input-group-text {
            background: #2ed473;
        }
    }
</style>
@stop


@section('auth_header')
<div class="login-logo text-center">
    <img src="{{ asset('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER">
    <h4 class="mt-3 text-success fw-bold">Iniciar Sesión</h4>
</div>
@stop


@section('auth_body')
<div class="login-card" id="loginCard">
    <form action="{{ route('login') }}" method="post" id="loginForm" autocomplete="off">
        @csrf

        <!-- Usuario -->
        <div class="input-group mb-3">
            <input type="text" name="Usuario" id="Usuario" maxlength="30"
                   class="form-control" placeholder="Usuario">
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-user"></span></div>
            </div>
        </div>

        <!-- Contraseña -->
        <div class="input-group mb-2">
            <input type="password" name="Contraseña" id="Contraseña" maxlength="8"
                   class="form-control" placeholder="Contraseña">
            <div class="input-group-append">
                <button type="button" class="input-group-text" id="togglePassword">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div id="capsWarning"><i class="fas fa-exclamation-triangle"></i> Mayúsculas activadas</div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
        </div>
    </form>
</div>
@stop


@section('auth_footer')
<div class="mt-3 text-center">
    <a href="{{ route('password.request') }}" class="text-primary fw-bold">
        <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
    </a>
</div>

<div class="mt-2 text-center">
    <span class="text-muted">¿No tienes cuenta?</span>
    <a href="{{ route('register') }}" class="text-primary fw-bold">
        <i class="fas fa-user-plus"></i> Regístrate aquí
    </a>
</div>
@stop


@section('adminlte_js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* Mostrar/Ocultar contraseña */
    const toggle = document.getElementById('togglePassword');
    const pass   = document.getElementById('Contraseña');
    const icon   = document.getElementById('eyeIcon');

    toggle.addEventListener('click', () => {
        const show = pass.type === 'password';
        pass.type = show ? 'text' : 'password';
        icon.classList.toggle('fa-eye-slash', show);
        icon.classList.toggle('fa-eye', !show);
    });

    /* Validación visual */
    function validarCampo(el) {
        if (el.value.trim().length > 0) {
            el.classList.add('valid-input');
            el.classList.remove('invalid-input');
        } else {
            el.classList.add('invalid-input');
            el.classList.remove('valid-input');
        }
    }

    ['Usuario','Contraseña'].forEach(id => {
        const input = document.getElementById(id);
        input.addEventListener('input', () => validarCampo(input));
    });

    /* Caps Lock */
    pass.addEventListener('keyup', e => {
        document.getElementById('capsWarning').style.display =
            e.getModifierState("CapsLock") ? "block" : "none";
    });

    /* Bloquear copiar/pegar */
    ['Usuario','Contraseña'].forEach(id => {
        const el = document.getElementById(id);
        ['paste','copy','cut','drop','dragstart'].forEach(evt =>
            el.addEventListener(evt, e => e.preventDefault())
        );
    });

});
</script>
@stop
