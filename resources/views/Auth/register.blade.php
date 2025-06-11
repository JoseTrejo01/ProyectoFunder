@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('adminlte_css_pre')
    <!-- Forza mayúsculas en el input -->
    <style>
        #Usuario, #Nombre_Usuario { text-transform: uppercase; }
    </style>
@stop

@section('auth_header', __('Registro'))

@section('auth_body')
    <form action="{{ route('register') }}" method="post">
        @csrf
        
        {{-- Campo Usuario --}}
        <div class="input-group mb-3">
            <input type="text" 
                   name="Usuario" 
                   id="Usuario"
                   class="form-control @error('Usuario') is-invalid @enderror"
                   value="{{ old('Usuario') }}"
                   placeholder="Usuario"
                   required autofocus>

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

        {{-- Campo Nombre --}}
        <div class="input-group mb-3">
            <input type="text" 
                   name="Nombre_Usuario" 
                   id="Nombre_Usuario"
                   class="form-control @error('Nombre_Usuario') is-invalid @enderror"
                   value="{{ old('Nombre_Usuario') }}"
                   placeholder="Nombre"
                   required>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user-tag"></span>
                </div>
            </div>

            @error('Nombre_Usuario')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Campo Correo Electrónico --}}
        <div class="input-group mb-3">
            <input type="email" 
                   name="Correo_Electronico" 
                   class="form-control @error('Correo_Electronico') is-invalid @enderror"
                   value="{{ old('Correo_Electronico') }}"
                   placeholder="Correo Electrónico"
                   required>

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

        {{-- Campo Contraseña --}}
        <div class="input-group mb-3">
            <input type="password" 
                   name="Contraseña" 
                   id="Contraseña"
                   class="form-control @error('Contraseña') is-invalid @enderror"
                   placeholder="Contraseña"
                   required>

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

        {{-- Campo Confirmar Contraseña --}}
        <div class="input-group mb-3">
            <input type="password" 
                   name="Contraseña_confirmation" 
                   class="form-control @error('Contraseña_confirmation') is-invalid @enderror"
                   placeholder="Confirmar Contraseña"
                   required>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('Contraseña_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Botón de registro --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Registrarse') }}
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <div class="mt-3 text-center">
        <a href="{{ route('login') }}" class="text-primary">
            <i class="fas fa-arrow-left"></i> {{ __('Ya tengo una cuenta') }}
        </a>
    </div>
@stop

