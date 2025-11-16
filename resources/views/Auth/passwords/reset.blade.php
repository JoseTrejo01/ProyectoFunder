@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('plugins.FontAwesome', true)

@section('adminlte_css_pre')
<style>

    /* ===========================
       → FONDO + BLUR ACCESIBLE
    ============================ */
    body {
        background-image: url('{{ asset('images/funder2.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        min-height: 100vh;
    }

    /* Capa difuminada */
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(7px);
        -webkit-backdrop-filter: blur(7px);
        z-index: -1;
    }

    /* ===========================
       → CAJA DEL FORMULARIO
    ============================ */
    .form-animated-box {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp .9s ease-out .15s forwards;
        background: rgba(255, 255, 255, 0.92);
        padding: 2rem;
        border-radius: 1.3rem;
        box-shadow: 0 10px 35px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.3);
    }

    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===========================
       → BOTÓN PRINCIPAL
    ============================ */
    .btn-primary {
        background-color: #5B8E3E !important;
        border-color: #5B8E3E !important;
        color: white !important;
        border-radius: 0.75rem;
        font-weight: 700;
        padding: 0.85rem;
        font-size: 1.08rem;
        letter-spacing: 0.5px;
        transition: all 0.25s ease;
    }

    .btn-primary:hover {
        background-color: #2f6f24 !important;
        transform: translateY(-2px);
    }

    .btn-primary:active {
        transform: translateY(1px);
        background-color: #234f1a !important;
    }

    /* ===========================
       → INPUTS Y GRUPOS
    ============================ */
    .input-group {
        border-radius: 0.6rem;
        overflow: hidden;
        background: #fff;
        border: 1px solid #ced4da;
        transition: all 0.25s ease;
    }

    .input-group:hover {
        border-color: #3a3a3a;
    }

    .input-group-text {
        background-color: #f8f9fa;
        cursor: pointer;
        border: none !important;
        padding: 0.85rem;
        color: #333;
    }

    .form-control {
        border: none !important;
        padding: 0.85rem 1rem;
        font-size: 1rem;
    }

    .form-control:focus {
        outline: none;
        box-shadow: inset 0 0 0 2px #1a1a1a;
    }

    /* ===========================
       → ACCESIBILIDAD
    ============================ */
    .invalid-feedback {
        font-size: 0.85rem;
        color: #b40000;
        font-weight: 600;
        margin-top: 4px;
    }

    a {
        font-weight: 600;
        color: #e6f7ff !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.6);
    }

    a:hover {
        text-decoration: underline;
    }
</style>
@stop

@section('auth_header', __('Restablecer Contraseña'))

@section('auth_body')
<form method="POST" action="{{ route('otp.reset.password') }}" id="resetForm" novalidate>
    @csrf

    <div class="form-animated-box">

        {{-- EMAIL (solo lectura) --}}
        <div class="input-group mb-3">
            <input type="email"
                   class="form-control @error('Correo_Electronico') is-invalid @enderror"
                   name="Correo_Electronico"
                   value="{{ $email ?? old('Correo_Electronico') }}"
                   required autocomplete="email"
                   readonly aria-readonly="true">
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
            @error('Correo_Electronico')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- NUEVA CONTRASEÑA --}}
        <div class="input-group mb-3">
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   placeholder="Nueva Contraseña (8 caracteres)"
                   required minlength="8" maxlength="8" pattern="\S{8}"
                   autocomplete="new-password">
            <div class="input-group-append">
                <button type="button" class="input-group-text" id="togglePassword" aria-label="Mostrar contraseña">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            <div class="invalid-feedback">
                La contraseña debe tener exactamente 8 caracteres y sin espacios.
            </div>
        </div>

        {{-- CONFIRMAR CONTRASEÑA --}}
        <div class="input-group mb-3">
            <input id="password-confirm" type="password"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   name="password_confirmation"
                   placeholder="Confirmar Contraseña"
                   required minlength="8" maxlength="8" pattern="\S{8}"
                   autocomplete="new-password">
            <div class="input-group-append">
                <button type="button" class="input-group-text" id="togglePasswordConfirm" aria-label="Mostrar confirmación">
                    <i class="fas fa-eye" id="eyeIconConfirm"></i>
                </button>
            </div>
            <div class="invalid-feedback">
                Debes repetir exactamente la misma contraseña.
            </div>
        </div>

        {{-- BOTÓN --}}
        <button type="submit" class="btn btn-primary btn-block w-100">
            Restablecer Contraseña
        </button>

    </div>
</form>
@stop

@section('auth_footer')
<div class="mt-3 text-center">
    <a href="{{ route('login') }}">Volver al Login</a>
</div>
@stop

@section('adminlte_js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* -------------------------------
       OJOS PARA MOSTRAR/OCULTAR
    --------------------------------*/
    function setupEye(inputSel, btnSel, iconSel) {
        const i = document.querySelector(inputSel),
              b = document.querySelector(btnSel),
              c = document.querySelector(iconSel);
        if (!i || !b || !c) return;

        b.addEventListener('click', () => {
            const isPass = i.type === 'password';
            i.type = isPass ? 'text' : 'password';
            c.classList.toggle('fa-eye-slash', isPass);
            c.classList.toggle('fa-eye', !isPass);
        });
    }

    setupEye('#password', '#togglePassword', '#eyeIcon');
    setupEye('#password-confirm', '#togglePasswordConfirm', '#eyeIconConfirm');

    /* -------------------------------
       VALIDACIÓN EXACTA 8 CARACTERES
    --------------------------------*/
    const form = document.getElementById('resetForm');
    const pass = document.getElementById('password');
    const pass2 = document.getElementById('password-confirm');

    const sanitize = el => {
        el.value = el.value.replace(/\s/g, '').slice(0, 8);
        el.setCustomValidity(/^\S{8}$/.test(el.value) ? '' : 'no-valid');
    };

    [pass, pass2].forEach(el => {
        el.addEventListener('input', () => sanitize(el));
        el.addEventListener('keydown', e => { if (e.key === ' ') e.preventDefault(); });
        el.addEventListener('paste', e => {
            const t = e.clipboardData.getData('text');
            if (/\s/.test(t) || t.length > 8) e.preventDefault();
        });
        sanitize(el);
    });

    form.addEventListener('submit', e => {
        sanitize(pass);
        sanitize(pass2);
        if (pass.value !== pass2.value) {
            pass2.setCustomValidity('no-match');
        }
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
});
</script>
@stop
