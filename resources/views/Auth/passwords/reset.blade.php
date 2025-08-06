@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
<style>
    body {
        background-image: url('{{ asset('./images/funder2.png') }}');
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

@section('auth_header', __('Restablecer Contraseña'))

@section('auth_body')
    <form method="POST" action="{{ route('otp.reset.password') }}">
        @csrf

        <div class="form-animated-box">

            <input type="hidden" name="Correo_Electronico" value="{{ $email ?? old('Correo_Electronico') }}">

            <div class="input-group mb-3">
                <input type="email" class="form-control @error('Correo_Electronico') is-invalid @enderror" 
                       name="Correo_Electronico" value="{{ $email ?? old('Correo_Electronico') }}" required autocomplete="email" readonly>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('Correo_Electronico')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="new-password" minlength="8" placeholder="Nueva Contraseña">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input id="password-confirm" type="password" class="form-control" 
                       name="password_confirmation" required autocomplete="new-password" minlength="8" placeholder="Confirmar Contraseña">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
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
        <a href="{{ route('login') }}">
            {{ __('Volver al Login') }}
        </a>
    </div>
@endsection
