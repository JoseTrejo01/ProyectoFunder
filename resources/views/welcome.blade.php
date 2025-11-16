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

        /* Capa accesible */
        .overlay {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 80px;
            text-align: left;
            color: #0D3B66; /* Azul oscuro legible */
            background: rgba(255, 255, 255, 0.70);
            backdrop-filter: blur(3px);
        }

        .logo-funder {
            width: 220px;
            margin-bottom: 25px;
        }

        h3 {
            font-weight: 700;
            font-size: 2.4rem;
            margin-bottom: 12px;
            color: #0D47A1; /* Azul FUNDER */
        }

        p.lead {
            font-size: 1.15rem;
            max-width: 520px;
            margin-bottom: 28px;
            color: #333;
        }

        /* Botón principal FUNDER */
        .btn-funder-primary {
            background-color: #0D47A1;
            border: none;
            padding: 12px 26px;
            font-weight: 600;
            color: white;
        }

        .btn-funder-primary:hover {
            background-color: #09316e;
        }

        /* Botón secundario */
        .btn-funder-outline {
            color: #0D47A1;
            border: 2px solid #0D47A1;
            padding: 12px 26px;
            font-weight: 600;
            background: white;
        }

        .btn-funder-outline:hover {
            background-color: #0D47A1;
            color: #fff;
        }

        .button-group {
            display: flex;
            gap: 20px;
        }

        /* Accesibilidad */
        .button-group a:focus {
            outline: 3px solid #F9A825;
            outline-offset: 3px;
        }

        @media(max-width: 768px) {
            .overlay {
                padding-left: 20px;
                padding-right: 20px;
                text-align: center;
            }
            .button-group {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <div class="overlay" role="main" aria-label="Pantalla de bienvenida del sistema FUNDER">
        
        <img src="{{ asset('images/cropped-cropped-logo-funder-1.webp') }}"
             alt="Logo institucional de FUNDER"
             class="logo-funder">

        <h3 id="bienvenida-titulo">¡Bienvenido al Sistema!</h3>

        <p class="lead" aria-labelledby="bienvenida-titulo">
            Este sistema está diseñado para gestionar eficientemente las operaciones de las cajas rurales apoyadas por 
            <strong>FUNDER</strong>.
        </p>

        <div class="button-group" role="group" aria-label="Acciones principales de acceso">
            @guest
                <a href="{{ route('login') }}" class="btn btn-funder-primary" aria-label="Iniciar sesión en el sistema">
                    Iniciar Sesión
                </a>

                <a href="{{ route('register') }}" class="btn btn-funder-outline" aria-label="Registrarse como usuario nuevo">
                    Registrarse
                </a>
            @endguest
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
