<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema en Mantenimiento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .maintenance-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 40px 30px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .icon-warning {
            font-size: 70px;
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="maintenance-box text-center">
        <div class="icon-warning mb-3">
            ⚠️
        </div>

        <h1 class="text-danger fw-bold">Sistema en Mantenimiento</h1>

        <p class="lead mt-3 mb-2">
            {{ $message }}
        </p>

        <p class="text-muted mb-4">
            Estamos trabajando para mejorar su experiencia.  
            Por favor, inténtelo más tarde.
        </p>

        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
            ⬅ Volver atrás
        </a>
    </div>
</body>
</html>
