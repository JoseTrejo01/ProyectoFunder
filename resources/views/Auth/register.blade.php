@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('adminlte_css_pre')
<style>
    body {
        background-image: url('{{ asset('./images/tractor-campo-generado-ia_268835-11230.avif') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .login-card {
        border-radius: 1.5rem;
        box-shadow: 0 12px 40px rgba(91, 142, 62, 0.3);
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    .form-animated-box {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 1s ease-out 0.2s forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-box .card-header {
        color: #2e7d32;
        /* Verde oscuro agradable */
        font-weight: bold;
        text-align: center;
    }

    .register-logo img {
        max-width: 180px;
        margin-bottom: 20px;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        border: none;
    }

    .input-group .form-control {
        border-radius: 8px 0 0 8px;
    }

    .input-group .input-group-text {
        background-color: #eaf4ec;
        color: #3a5f3a;
        border-radius: 0 8px 8px 0;
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
        background-color: #2e4b2e;
    }

    .text-primary {
        color: #3a5f3a !important;
    }

    #Usuario,
    #Nombre_Usuario {
        text-transform: uppercase;
    }
</style>
@stop

@section('auth_header')
<div class="register-logo">
    <img src="{{ asset('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo Funder">
</div>
<h4 class="text-center">{{ __('Registro') }}</h4>
@stop

@section('auth_body')
<form action="{{ route('register') }}" method="post">
    @csrf

    <!-- Caja con animación -->
    <div class="form-animated-box">

        {{-- Usuario --}}
        <div class="input-group mb-3">
            <input type="text" name="Usuario" id="Usuario" maxlength="30" class="form-control @error('Usuario') is-invalid @enderror"
                value="{{ old('Usuario') }}" placeholder="Usuario" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-user"></span></div>
            </div>
            @error('Usuario')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Nombre --}}
        <div class="input-group mb-3">
            <input type="text" name="Nombre_Usuario" id="Nombre_Usuario"
                class="form-control @error('Nombre_Usuario') is-invalid @enderror" value="{{ old('Nombre_Usuario') }}"
                placeholder="Nombre completo" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-user-tag"></span></div>
            </div>
            @error('Nombre_Usuario')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Correo --}}
        <div class="input-group mb-3">
            <input type="email" name="Correo_Electronico"
                class="form-control @error('Correo_Electronico') is-invalid @enderror"
                value="{{ old('Correo_Electronico') }}" placeholder="Correo Electrónico" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
            @error('Correo_Electronico')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
{{-- Contraseña --}}
<div class="input-group mb-3">
    <input type="password" name="Contraseña" id="Contraseña"
        maxlength="8"
        class="form-control @error('Contraseña') is-invalid @enderror" placeholder="Contraseña" required>

    {{-- Ojito para mostrar/ocultar contraseña --}}
    <div class="input-group-append">
        <div class="input-group-text" style="cursor: pointer;" id="togglePassword">
            <span class="fas fa-eye" id="eyeIcon"></span>
        </div>
    </div>

    @error('Contraseña')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>

{{-- Confirmar Contraseña --}}
<div class="input-group mb-3">
    <input type="password" name="Contraseña_confirmation" id="Contraseña_confirmation"
        maxlength="8"
        class="form-control @error('Contraseña_confirmation') is-invalid @enderror"
        placeholder="Confirmar Contraseña" required>

    {{-- Ojito para mostrar/ocultar confirmación --}}
    <div class="input-group-append">
        <div class="input-group-text" style="cursor: pointer;" id="togglePasswordConfirm">
            <span class="fas fa-eye" id="eyeIconConfirm"></span>
        </div>
    </div>

    @error('Contraseña_confirmation')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
{{-- Script para manejar los ojitos --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('Contraseña');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const confirmInput = document.getElementById('Contraseña_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        togglePasswordConfirm.addEventListener('click', function () {
            const isPassword = confirmInput.type === 'password';
            confirmInput.type = isPassword ? 'text' : 'password';
            eyeIconConfirm.classList.toggle('fa-eye');
            eyeIconConfirm.classList.toggle('fa-eye-slash');
        });
    });
</script>


        {{-- Botón --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Registrarse') }}</button>
            </div>
        </div>

    </div> <!-- fin de caja animada -->
</form>
@stop

@section('auth_footer')
<div class="mt-3 text-center">
    <a href="{{ route('login') }}" class="text-primary">
        <i class="fas fa-arrow-left"></i> {{ __('Ya tengo una cuenta') }}
    </a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const campos = ['Usuario', 'Nombre_Usuario', 'Correo_Electronico', 'Contraseña', 'Contraseña_confirmation'];

        campos.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                // Bloquear clic derecho
                campo.addEventListener('contextmenu', e => e.preventDefault());

                // Bloquear teclas rápidas (Ctrl+C, Ctrl+V, Ctrl+X, Ctrl+A)
                campo.addEventListener('keydown', e => {
                    if ((e.ctrlKey || e.metaKey) && ['c', 'v', 'x', 'a'].includes(e.key.toLowerCase())) {
                        e.preventDefault();
                    }
                });

                // Bloquear acciones de copiar, pegar, cortar o soltar
                campo.addEventListener('paste', e => e.preventDefault());
                campo.addEventListener('copy', e => e.preventDefault());
                campo.addEventListener('cut', e => e.preventDefault());
                campo.addEventListener('drop', e => e.preventDefault());
            }
        });
    });
</script>

@stop