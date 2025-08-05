@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
<style>
    body {
        background: url('{{ asset('./images/funder2.png') }}') no-repeat center center fixed;
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

    .input-group-text {
        background-color: rgb(0, 0, 0);
        color: rgb(0, 0, 0);
        border: none;
        border-radius: 0.5rem 0 0 0.5rem;
    }

    .form-control {
        border: 1px solid rgb(48, 75, 102);
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
        border-color: #2D6A4F !important;
        transform: translateY(-2px);
        color: white !important;
    }

    .btn-secondary {
        border-radius: 0.75rem;
        font-weight: bold;
    }

    .invalid-feedback {
        font-size: 0.875rem;
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
    <h4 class="mt-3 text-success">Verificar Código OTP</h4>
</div>
@stop

@section('auth_body')
<div class="login-card">
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <div class="form-animated-box">
            <div class="input-group mb-3">
                <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror"
                       placeholder="Ingrese el código OTP" required>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-key"></span></div>
                </div>
                @error('otp')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-block">Verificar</button>
            <a href="{{ route('otp.resend') }}" class="btn btn-secondary btn-block mt-2">Reenviar código</a>
            <a href="{{ route('login') }}" class="btn btn-link btn-block">Volver al login</a>
        </div>
    </form>
</div>
@stop
