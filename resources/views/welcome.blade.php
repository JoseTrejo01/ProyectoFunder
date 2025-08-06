<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Sistema de Cajas Rurales FUNDER</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('{{ asset('images/imagen1.png') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .overlay {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 80px;
            text-align: left;
            color: #1b4332;
        }

        .logo-funder {
            width: 200px;
            margin-bottom: 20px;
        }

        h3 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        p.lead {
            font-size: 1.2rem;
            max-width: 500px;
            margin-bottom: 25px;
        }

        .btn-primary {
            background-color: #40916c;
            border: none;
            padding: 10px 25px;
        }

        .btn-primary:hover {
            background-color: #2d6a4f;
        }

        .btn-outline-primary {
            color: #40916c;
            border-color: #40916c;
            padding: 10px 25px;
        }

        .btn-outline-primary:hover {
            background-color: #40916c;
            color: #fff;
        }

        .button-group {
            display: flex;
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="overlay">
        <img src="{{ asset('images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER" class="logo-funder">

        <h3>¡Bienvenido al Sistema!</h3>
        <p class="lead">
            Este sistema está diseñado para gestionar eficientemente las operaciones de las cajas rurales apoyadas por <strong>FUNDER</strong>.
        </p>

        <div class="button-group">
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">Registrarse</a>
            @endguest
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
