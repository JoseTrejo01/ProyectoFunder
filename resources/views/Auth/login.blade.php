@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <!-- Forza mayúsculas en el input -->
    <style>
        #Usuario { text-transform: uppercase; }
    </style>
@stop

@section('auth_header', __('Iniciar sesión'))

@section('auth_body')
    <form action="{{ route('login') }}" method="post">
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

        {{-- Campo Contraseña --}}
        <div class="input-group mb-3">
            <input type="password" 
                   name="Contraseña" 
                   id="Contraseña"
                   class="form-control @error('Contraseña') is-invalid @enderror"
                   placeholder="Contraseña">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('Contraseña')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Botón de ingreso --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Iniciar sesión') }}
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <div class="mt-3 text-center">
        <a href="{{ route('password.request') }}" class="text-primary">
            <i class="fas fa-key"></i> {{ __('¿Olvidaste tu contraseña?') }}
        </a>
    </div>
    
    {{-- Enlace para registrarse --}}
    <div class="mt-2 text-center">
        <span class="text-muted">{{ __('¿No tienes cuenta?') }}</span>
        <a href="{{ route('register') }}" class="text-primary">
            <i class="fas fa-user-plus"></i> {{ __('Regístrate aquí') }}
        </a>
    </div>
@stop
