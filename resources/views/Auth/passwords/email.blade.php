@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
<style>
    /* ============  FONDO + BLUR  ============ */
    body {
        background: url('{{ asset('images/funder2.png') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
        position: relative;
        overflow-x: hidden;
    }

    /* Capa difuminada accesible */
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.30); 
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: -1;
    }

    /* ============  TARJETA  ============ */
    .login-card {
        background-color: rgba(255, 255, 255, 0.90);
        border-radius: 1.5rem;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
        transform: translateY(30px);
        padding: 2rem;
        border: 1px solid rgba(0, 0, 0, 0.15);
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ============  LOGO  ============ */
    .login-logo img {
        width: 120px;
        margin-bottom: 10px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }

    /* ============  INPUTS  ============ */
    .input-group {
        border-radius: 0.6rem;
        overflow: hidden;
        border: 1px solid #1a1a1a;
        background: white;
    }

    .input-group-text {
        background-color: #000;
        color: white;
        padding: 0.75rem;
        border: none !important;
    }

    .form-control {
        border: none !important;
        padding: 0.9rem 1rem;
        font-size: 1rem;
        background: white;
    }

    .form-control:focus {
        outline: none;
        box-shadow: inset 0 0 0 2px #000;
    }

    /* ============  BOTÓN PRINCIPAL  ============ */
    .btn-primary {
        background-color: #5B8E3E !important;
        border-color: #5B8E3E !important;
        color: white !important;
        border-radius: 0.75rem;
        font-weight: bold;
        padding: 0.85rem;
        font-size: 1.08rem;
        transition: 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #3b6c2a !important;
        transform: translateY(-2px);
    }

    .btn-primary:active {
        background-color: #24481a !important;
        transform: translateY(1px);
    }

    /* ============  ERRORES  ============ */
    .invalid-feedback {
        font-size: 0.85rem;
        color: #b30000;
        font-weight: 600;
    }

    /* Link volver al login */
    a {
        font-weight: 600;
        color: #003366 !important;
    }

    a:hover {
        text-decoration: underline;
    }

</style>
@stop

@section('auth_header')
<div class="login-logo text-center">
    <img src="{{ asset('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER">
    <h4 class="mt-3 text-success fw-bold">Recuperar Contraseña</h4>
</div>
@stop

@section('auth_body')
<div class="login-card">

    @if(session('status'))
        <div class="alert alert-success shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.send') }}">
        @csrf

        <div class="input-group mb-3">
            <input type="text"
                   name="Usuario"
                   id="Usuario"
                   class="form-control @error('Usuario') is-invalid @enderror"
                   value="{{ old('Usuario') }}"
                   placeholder="Usuario"
                   style="text-transform: uppercase;"
                   autofocus>

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

        <button type="submit" class="btn btn-primary w-100 shadow-sm">
            Enviar enlace de recuperación
        </button>

    </form>

</div>
@stop

@section('auth_footer')
<div class="mt-3 text-center">
    <a href="{{ route('login') }}">
        Volver al login
    </a>
</div>
@stop

@section('adminlte_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const usuarioInput = document.getElementById('Usuario');

        if (usuarioInput) {
            usuarioInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
            });
        }
    });
</script>
@stop
