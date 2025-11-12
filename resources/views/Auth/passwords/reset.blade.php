@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('plugins.FontAwesome', true)

@section('adminlte_css_pre')
<style>
    body {
        background-image: url('{{ asset('images/funder2.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .btn-primary {
        background-color: #5B8E3E !important;
        border-color: #5B8E3E !important;
        color: white !important;
        border-radius: 0.75rem;
        font-weight: bold;
    }
    .form-animated-box { opacity: 0; transform: translateY(20px); animation: fadeInUp .8s ease-out .2s forwards; }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    .input-group-text { background-color: #fff; cursor: pointer; border: 1px solid #ced4da; border-left: 0; }
</style>
@stop

@section('auth_header', __('Restablecer Contraseña'))

@section('auth_body')
<form method="POST" action="{{ route('otp.reset.password') }}" id="resetForm" novalidate>
    @csrf

    <div class="form-animated-box">

        {{-- Email (solo lectura; este es el ÚNICO campo con ese name) --}}
        <div class="input-group mb-3">
            <input type="email"
                   class="form-control @error('Correo_Electronico') is-invalid @enderror"
                   name="Correo_Electronico"
                   value="{{ $email ?? old('Correo_Electronico') }}"
                   required autocomplete="email" readonly>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
            @error('Correo_Electronico')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Nueva contraseña (exactamente 8, sin espacios) --}}
        <div class="input-group mb-3">
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   placeholder="Nueva Contraseña"
                   required minlength="8" maxlength="8" pattern="\S{8}"
                   autocomplete="new-password">
            <div class="input-group-append">
                <button type="button" class="input-group-text" id="togglePassword" aria-label="Mostrar u ocultar contraseña">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
            <div class="invalid-feedback">La contraseña debe tener exactamente 8 caracteres y sin espacios.</div>
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- Confirmación (exactamente 8, sin espacios) --}}
        <div class="input-group mb-3">
            <input id="password-confirm" type="password"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   name="password_confirmation"
                   placeholder="Confirmar Contraseña"
                   required minlength="8" maxlength="8" pattern="\S{8}"
                   autocomplete="new-password">
            <div class="input-group-append">
                <button type="button" class="input-group-text" id="togglePasswordConfirm" aria-label="Mostrar u ocultar confirmación">
                    <i class="fas fa-eye" id="eyeIconConfirm"></i>
                </button>
            </div>
            <div class="invalid-feedback">Debes repetir la misma contraseña (8 caracteres, sin espacios).</div>
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Restablecer Contraseña') }}
                </button>
            </div>
        </div>

    </div>
</form>
@endsection

@section('auth_footer')
<div class="mt-3 text-center">
    <a href="{{ route('login') }}">{{ __('Volver al Login') }}</a>
</div>
@endsection

@section('adminlte_js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ojos
    function setupEye(inputSel, btnSel, iconSel) {
        const input = document.querySelector(inputSel);
        const btn   = document.querySelector(btnSel);
        const icon  = document.querySelector(iconSel);
        if (!input || !btn || !icon) return;
        btn.addEventListener('click', () => {
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isPass);
            icon.classList.toggle('fa-eye-slash', isPass);
        });
    }
    setupEye('#password', '#togglePassword', '#eyeIcon');
    setupEye('#password-confirm', '#togglePasswordConfirm', '#eyeIconConfirm');

    // Validación: 8 exactos sin espacios
    const form = document.getElementById('resetForm');
    const pass = document.getElementById('password');
    const pass2= document.getElementById('password-confirm');

    const enforce = el => {
        el.value = el.value.replace(/\s+/g, '').slice(0,8);
        el.setCustomValidity(/^\S{8}$/.test(el.value) ? '' : 'exact8');
    };
    const blockSpace = e => { if (e.key === ' ') e.preventDefault(); };
    const onPaste = e => {
        const t = (e.clipboardData || window.clipboardData).getData('text');
        if (/\s/.test(t) || t.length > 8) e.preventDefault();
    };

    [pass, pass2].forEach(el => {
        el.addEventListener('input', () => enforce(el));
        el.addEventListener('keydown', blockSpace);
        el.addEventListener('paste', onPaste);
        enforce(el);
    });

    form.addEventListener('submit', e => {
        enforce(pass); enforce(pass2);
        if (pass.value !== pass2.value) pass2.setCustomValidity('no-match'); else pass2.setCustomValidity('');
        if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
    });
});
</script>
@stop
