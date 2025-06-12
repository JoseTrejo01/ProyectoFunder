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
        /* verde principal */
        border-color: #5B8E3E !important;
        color: white !important;
        border-radius: 0.75rem;
        font-weight: bold;
        font-size: 1.05rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }


    #Usuario {
        text-transform: uppercase;
    }

    .login-box .card-header {
        color: #2e7d32;
        /* verde tipo Funder */
        font-weight: bold;
        text-align: center;
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




@section('adminlte_css_pre')
<style>
    #Usuario {
        text-transform: uppercase;
    }
</style>
@stop

@section('auth_header', __('Recuperar Contraseña'))

@section('auth_body')
@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<form action="{{ route('password.email') }}" method="post">
    @csrf

    <!-- Caja animada -->
    <div class="form-animated-box">

        {{-- Campo Usuario --}}
        <div class="input-group mb-3">
            <input type="text" name="Usuario" id="Usuario" class="form-control @error('Usuario') is-invalid @enderror"
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

        {{-- Botón --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Enviar enlace de recuperación') }}
                </button>
            </div>
        </div>

    </div> <!-- fin caja animada -->
</form>
@stop


@section('auth_footer')
<div class="mt-3 text-center">
    <a href="{{ route('login') }}" class="text-center">
        {{ __('Volver al login') }}
    </a>
</div>
@stop