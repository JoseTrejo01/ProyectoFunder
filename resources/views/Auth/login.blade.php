@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('adminlte_css_pre')
<style>
    body {
        background: url('{{ asset('./images/tractor-campo-generado-ia_268835-11230.avif') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
    }


    .login-card {
        background-color: rgba(248, 243, 243, 0.97);
        border-radius: 1.5rem;

        box-shadow: 0 12px 40px rgba(91, 142, 62, 0.3);
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-logo img {
        width: 120px;
        margin-bottom: 10px;
    }

    #Usuario {
        text-transform: uppercase;
    }

    .input-group-text {
        background-color: rgb(0, 0, 0);
        color: rgb(0, 0, 0);
        border: none;
        border-radius: 0.5rem 0 0 0.5rem;
    }

    .form-control {
        border: 1px solidrgb(48, 75, 102);
        border-radius: 0 0.5rem 0.5rem 0;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: rgb(0, 0, 0);
        box-shadow: 0 0 0 0.2rem rgba(162, 201, 78, 0.25);
    }

    .btn-primary {
        background-color: #5B8E3E !important;
        /* verde principal */
        border-color: #5B8E3E !important;
        color: white !important;
        border-radius: 0.75rem;
        font-weight: bold;
        font-size: 1.05rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background-color: rgb(46, 212, 115);
        transform: translateY(-2px);
    }

    .btn-primary:active {
        background-color: #2D6A4F !important;
        /* verde oscuro */
        border-color: #2D6A4F !important;
        transform: translateY(-2px);
        color: white !important;
    }

    a:focus {
        color: #1B4332 !important;
        text-decoration: underline;
    }

    .text-primary {
        color: #5B8E3E !important;
    }

    a {
        color: rgb(0, 0, 0);
        text-decoration: none;
        transition: color 0.2s ease, text-decoration 0.2s ease;
    }

    a:hover {
        color: rgb(0, 0, 0);
        text-decoration: underline;
    }

    .invalid-feedback {
        font-size: 0.875rem;
    }

    .small-text {
        font-size: 0.9rem;
    }

    .input-group {
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.06);
        border-radius: 0.5rem;
        overflow: hidden;
    }
</style>
@stop




@section('auth_header')
<div class="login-logo text-center">
    <img src="{{ asset('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER">
    <h4 class="mt-3 text-success">Iniciar Sesión</h4>
</div>
@stop

@section('auth_body')
<div class="login-card">
    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Campo Usuario --}}
        <div class="input-group mb-3">
            <input type="text" name="Usuario" id="Usuario" maxlength="30" class="form-control @error('Usuario') is-invalid @enderror"
                value="{{ old('Usuario') }}" placeholder="Usuario" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>

            @error('Usuario')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
       {{-- Campo Contraseña --}}
<div class="input-group mb-4">
    <input type="password" name="Contraseña" id="Contraseña" maxlength="8"
        class="form-control @error('Contraseña') is-invalid @enderror" placeholder="Contraseña">

    {{-- Ojito para mostrar/ocultar contraseña --}}
    <div class="input-group-append">
        <div class="input-group-text" style="cursor: pointer;" id="togglePassword">
            <span class="fas fa-eye" id="eyeIcon"></span>
        </div>
    </div>

    @error('Contraseña')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

{{-- Script directo para cambiar el tipo de input --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('Contraseña');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // Cambiar el ícono
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    });
</script>


        {{-- Botón de ingreso --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Iniciar sesión') }}
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('auth_footer')
<div class="mt-3 text-center">
    <a href="{{ route('password.request') }}" class="text-primary">
        <i class="fas fa-key"></i> {{ __('¿Olvidaste tu contraseña?') }}
    </a>
</div>

<div class="mt-2 text-center">
    <span class="text-muted">{{ __('¿No tienes cuenta?') }}</span>
    <a href="{{ route('register') }}" class="text-primary">
        <i class="fas fa-user-plus"></i> {{ __('Regístrate aquí') }}
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const campos = ['Usuario', 'Contraseña'];

    campos.forEach(id => {
        const campo = document.getElementById(id);

        if (campo) {
            // Bloquear clic derecho
            campo.addEventListener('contextmenu', e => e.preventDefault());

            // Bloquear combinaciones de teclado (Ctrl+C, Ctrl+V, Ctrl+X)
            campo.addEventListener('keydown', e => {
                if ((e.ctrlKey || e.metaKey) && ['c', 'v', 'x', 'a'].includes(e.key.toLowerCase())) {
                    e.preventDefault();
                }
            });

            // Bloquear pegar (mouse, teclado o drag)
            campo.addEventListener('paste', e => e.preventDefault());
            campo.addEventListener('copy', e => e.preventDefault());
            campo.addEventListener('cut', e => e.preventDefault());
            campo.addEventListener('drop', e => e.preventDefault());
        }
    });
});
</script>



@stop