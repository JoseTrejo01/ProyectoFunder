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
        /* ---------------- FONDO + BLUR ---------------- */
        body {
            position: relative;
            min-height: 100vh;
            margin: 0;
            background: url('{{ asset('images/funder2.png') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 0;
        }

        /* ---------------- TARJETA ---------------- */
        .verify-card {
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.92);
            border-radius: 1.4rem;
            padding: 2rem 2.2rem;
            max-width: 460px;
            width: 100%;
            box-shadow: 0 10px 35px rgba(0,0,0,0.25);
            animation: fadeInUp .8s ease forwards;
            opacity: 0;
            transform: translateY(25px);
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            font-weight: 700;
        }

        .btn-success {
            background-color: #5B8E3E !important;
            border-color: #5B8E3E !important;
            border-radius: .7rem;
            font-weight: bold;
        }

        .btn-success:hover {
            background-color: #2ed473 !important;
        }

        /* ---------------- RESPONSIVE ---------------- */
        @media (max-width: 480px) {
            .verify-card {
                padding: 1.5rem;
                border-radius: 1rem;
            }
            h2 { font-size: 1.4rem; }
        }

        /* ---------------- Modo Oscuro Automático ---------------- */
        @media (prefers-color-scheme: dark) {
            .verify-card {
                background: rgba(20,20,20,0.85);
                color: #fff;
            }
            h2 { color: #9dfa9d; }
            p { color: #ddd; }
            .alert-success {
                background-color: #204f20 !important;
                color: #a8ffb1 !important;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; position: relative; z-index: 2;">
        
        <div class="verify-card text-center">

            <h2 class="text-success mb-3">Verifica tu correo electrónico</h2>

            <p class="mb-2">Hemos enviado un enlace de verificación a tu correo.</p>
            <p class="mb-3">Por favor, revisa tu bandeja de entrada para continuar.</p>

            {{-- Mensaje de verificación reenviada --}}
            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success py-2">
                    ¡Se ha enviado un nuevo enlace de verificación!
                </div>
            @endif

            {{-- Botón de reenvío --}}
            <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
                @csrf
                <label for="resendBtn" class="d-block mb-2 fw-semibold">¿No recibiste el correo?</label>
                <button id="resendBtn" type="submit" class="btn btn-success w-100">
                    Reenviar correo de verificación
                </button>
            </form>

        </div>

    </div>

</body>
</html>
@endsection
