@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <style>
        #Usuario { text-transform: uppercase; }
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

        {{-- Campo Usuario --}}
        <div class="input-group mb-3">
            <input type="text" 
                   name="Usuario" 
                   id="Usuario"
                   class="form-control @error('Usuario') is-invalid @enderror"
                   value="{{ old('Usuario') }}"
                   placeholder="Usuario"
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

        {{-- Botón de envío --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Enviar enlace de recuperación') }}
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <div class="mt-3 text-center">
        <a href="{{ route('login') }}" class="text-center">
            {{ __('Volver al login') }}
        </a>
    </div>
@stop
