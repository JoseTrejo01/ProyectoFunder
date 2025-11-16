@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
<style>
    body {
        position: relative;
        margin: 0;
        min-height: 100vh;
        background: url('{{ asset('images/funder2.png') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
        overflow: hidden;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(7px);
        -webkit-backdrop-filter: blur(7px);
        z-index: 0;
    }

    .login-card, .login-logo {
        position: relative;
        z-index: 1;
    }

    .login-card {
        background-color: rgba(248, 243, 243, 0.97);
        border-radius: 1.5rem;
        box-shadow: 0 12px 40px rgba(91, 142, 62, 0.3);
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
        transform: translateY(30px);
        padding: 2rem;
    }

    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

    .login-logo img { width: 120px; margin-bottom: 10px; }

    .input-group-text {
        background-color: #000;
        color: #fff;
        border: none;
        border-radius: 0.5rem 0 0 0.5rem;
    }

    .input-group {
        box-shadow: 0 3px 6px rgba(0,0,0,0.06);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .form-control {
        border: 1px solid #304b66;
        border-radius: 0 0.5rem 0.5rem 0;
        padding: .75rem 1rem;
    }

    .btn-primary {
        background-color: #5B8E3E !important;
        border-color: #5B8E3E !important;
        font-weight: bold;
        border-radius: .75rem;
    }

    .btn-secondary {
        border-radius: .75rem;
        font-weight: 600;
    }

    .disabled-btn { pointer-events: none; opacity: .6; }

</style>
@stop

@section('auth_header')
<div class="login-logo text-center">
    <img src="{{ asset('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER">
    <h4 class="mt-3 text-success fw-bold">Verificar Código OTP</h4>
</div>
@stop

@section('auth_body')
<div class="login-card">
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <div class="input-group mb-2">
            <input type="text" name="otp" id="otp"
                   class="form-control @error('otp') is-invalid @enderror"
                   placeholder="Ingrese el código OTP"
                   maxlength="6" inputmode="numeric" pattern="[0-9]*" required>

            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-key"></span></div>
            </div>
        </div>

        @error('otp')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <button type="submit" class="btn btn-primary btn-block mt-3">Verificar</button>

        {{-- Botón con contador --}}
        <button type="button" id="resendBtn"
                onclick="window.location='{{ route('otp.resend') }}'"
                class="btn btn-secondary btn-block mt-2">
            Reenviar código <span id="counter"></span>
        </button>

        <a href="{{ route('login') }}" class="btn btn-link btn-block mt-2">Volver al login</a>
    </form>
</div>
@stop

@section('adminlte_js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---------------------------
    // VALIDACIÓN DE OTP
    // ---------------------------
    const otp = document.getElementById('otp');
    otp.addEventListener('input', () => {
        otp.value = otp.value.replace(/\D+/g, '').slice(0, 6);
    });


    // ---------------------------
    // CONTADOR PARA REENVÍO
    // ---------------------------
    const resendBtn = document.getElementById('resendBtn');
    const counter = document.getElementById('counter');

    const WAIT_TIME = 30;
    let now = Math.floor(Date.now() / 1000);

    let lastSent = localStorage.getItem('otp_last_sent');
    if (!lastSent) {
        localStorage.setItem('otp_last_sent', now);
        lastSent = now;
    }

    let elapsed = now - lastSent;

    if (elapsed < WAIT_TIME) {
        resendBtn.classList.add("disabled-btn");
        let remaining = WAIT_TIME - elapsed;
        counter.textContent = `(${remaining}s)`;

        const interval = setInterval(() => {
            remaining--;
            counter.textContent = `(${remaining}s)`;

            if (remaining <= 0) {
                clearInterval(interval);
                resendBtn.classList.remove("disabled-btn");
                counter.textContent = "";
            }
        }, 1000);
    }


    // ---------------------------
    // SWEETALERT2 AL REENVIAR OTP
    // ---------------------------
    @if(session('otp_sent'))
        Swal.fire({
            icon: 'success',
            title: 'Código reenviado',
            text: 'El nuevo código OTP ha sido enviado a su correo.',
            confirmButtonColor: '#5B8E3E'
        });
    @endif

});
</script>
@stop
