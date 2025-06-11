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
            background-color: #f8f9fa;
        }
        .welcome-container {
            margin-top: 3%;
        }
        .logo-funder {
            max-width: 180px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container welcome-container text-center">

        <!-- Logo de FUNDER -->
        <img src="{{ asset('images/FUNDER4.png') }}" alt="Logo FUNDER" class="img-fluid w-25 mb-4">


        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body text-center">
                        
                        <h3 class="text-success mb-3">¡Bienvenido al Sistema!</h3>
                        <p class="lead mb-4">Este sistema está diseñado para gestionar eficientemente las operaciones de las cajas rurales apoyadas por FUNDER.</p>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary m-2">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary m-2">Registrarse</a>
                        
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>