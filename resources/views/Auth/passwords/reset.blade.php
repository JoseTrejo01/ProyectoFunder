{{-- resources/views/auth/passwords/reset.blade.php --}}

@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Restablecer Contraseña'))

@section('auth_body')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

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
                   name="password" required autocomplete="new-password" minlength="8" placeholder="{{ __('Nueva Contraseña') }}">
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
                   name="password_confirmation" required autocomplete="new-password" minlength="8" placeholder="{{ __('Confirmar Contraseña') }}">
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
                <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    {{ __('Restablecer Contraseña') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('login') }}">
            {{ __('Volver al Login') }}
        </a>
    </p>
@endsection
