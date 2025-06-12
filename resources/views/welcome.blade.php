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
    background: url('{{ asset('./images/AdobeStock_282660820-min2-.jpeg') }}') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', sans-serif;
}


        .overlay {
            
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .logo-funder {
            width: 150px;
            margin-bottom: 20px;
        }

        .card {
            border: none;
            border-radius: 20px;
            padding: 40px 30px;
            background-color:rgba(255, 255, 255, 0.93);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: fadeInUp 1s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        h3 {
            color: #2d6a4f;
            font-weight: 600;
        }

        p.lead {
            color: #495057;
        }

        .btn-primary {
            background-color: #40916c;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2d6a4f;
        }

        .btn-outline-primary {
            color: #40916c;
            border-color: #40916c;
        }

        .btn-outline-primary:hover {
            background-color: #40916c;
            color: #fff;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="overlay text-center">
        <img src="{{ asset('./images/cropped-cropped-logo-funder-1.webp') }}" alt="Logo FUNDER" class="logo-funder">

        <div class="row justify-content-center w-100 px-3">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">¡Bienvenido al Sistema!</h3>
                        <p class="lead mb-4">
                            Este sistema está diseñado para gestionar eficientemente las operaciones de las cajas rurales apoyadas por <strong>FUNDER</strong>.
                        </p>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary m-2 px-4">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary m-2 px-4">Registrarse</a>
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
