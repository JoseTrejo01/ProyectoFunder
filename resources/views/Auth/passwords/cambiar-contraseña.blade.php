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
    <h4 class="mt-3 text-success">Cambiar Contraseña</h4>
</div>
@stop

@section('auth_body')
<div class="login-card">
    <form method="POST" action="{{ route('password.change') }}">
        @csrf
        {{-- Nueva contraseña --}}
        <div class="input-group mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Nueva Contraseña" required autofocus>
            <div class="input-group-append">
<<<<<<< HEAD
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
=======
                <div class="input-group-text" style="cursor: pointer;" id="togglePassword">
                    <span class="fas fa-eye" id="eyeIcon"></span>
<<<<<<< HEAD
>>>>>>> 52e54cf8bd6684abc982ecd42d1a543333613d22
=======
>>>>>>> rama-bitacora
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        {{-- Confirmar contraseña --}}
        <div class="input-group mb-4">
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar Contraseña" required>
            <div class="input-group-append">
<<<<<<< HEAD
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
=======
                <div class="input-group-text" style="cursor: pointer;" id="togglePasswordConfirm">
                    <span class="fas fa-eye" id="eyeIconConfirm"></span>
<<<<<<< HEAD
>>>>>>> 52e54cf8bd6684abc982ecd42d1a543333613d22
=======
>>>>>>> rama-bitacora
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Cambiar Contraseña
                </button>
            </div>
        </div>
    </form>
</div>
@stop
<<<<<<< HEAD
=======

@section('adminlte_js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Para el campo de nueva contraseña
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
        // Para el campo de confirmar contraseña
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordInputConfirm = document.getElementById('password_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');
        togglePasswordConfirm.addEventListener('click', function () {
            const isPassword = passwordInputConfirm.type === 'password';
            passwordInputConfirm.type = isPassword ? 'text' : 'password';
            eyeIconConfirm.classList.toggle('fa-eye');
            eyeIconConfirm.classList.toggle('fa-eye-slash');
        });
    });
</script>
@stop
<<<<<<< HEAD
>>>>>>> 52e54cf8bd6684abc982ecd42d1a543333613d22
=======
>>>>>>> rama-bitacora
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
