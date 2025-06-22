@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
<style>
    body {
        background-image: url('{{ asset('./images/tractor-campo-generado-ia_268835-11230.avif') }}');
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

    .form-animated-box {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.8s ease-out 0.2s forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@stop

@section('auth_header', 'Verificar Código OTP')

@section('auth_body')
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
@endsection
