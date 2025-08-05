<!-- resources/views/auth/verify-email.blade.php -->
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verifica tu correo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset('./images/funder2.png') }}');
             background-size: cover;
             background-position: center;
             background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-success:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .center-container {
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center center-container">
        <div class="card p-4 w-100" style="max-width: 500px;">
            <div class="card-body text-center">
                <h2 class="mb-3 text-success">Verifica tu correo electrónico</h2>
                <p class="mb-4">Hemos enviado un enlace de verificación a tu correo.</p>
                <p class="mb-4">Por favor, revisa tu bandeja de entrada para continuar.</p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success" role="alert">
                        ¡Se ha enviado un nuevo enlace de verificación!
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">Reenviar correo de verificación</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>