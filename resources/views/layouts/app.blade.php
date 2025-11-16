<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Funder')</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Estilos internos del proyecto --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    {{-- Estilos adicionales --}}
    @stack('styles')
</head>

<body>

    {{-- ================= NAVBAR ================= --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm" role="navigation">
        <div class="container">

            {{-- LOGO --}}
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo-funder.png') }}"
                     alt="Logo Funder" height="30" class="me-2">
                <span class="fw-bold">Funder</span>
            </a>

            {{-- Botón responsive --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent" aria-controls="navbarContent"
                    aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Menú --}}
            <div class="collapse navbar-collapse" id="navbarContent">

                {{-- Menú izquierda --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
                           href="{{ route('dashboard') }}">
                           <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('socios.*') ? 'active fw-bold' : '' }}"
                           href="{{ route('socios.index') }}">
                           <i class="fas fa-users me-1"></i> Socios
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ahorros.*') ? 'active fw-bold' : '' }}"
                           href="{{ route('ahorros.index') }}">
                           <i class="fas fa-piggy-bank me-1"></i> Ahorros
                        </a>
                    </li>
                </ul>

                {{-- Menú derecha --}}
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item d-flex align-items-center">

                            {{-- BOTÓN LOGOUT --}}
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" class="btn btn-outline-light btn-sm"
                                        onclick="confirmLogout()">
                                    <i class="fas fa-power-off"></i> Cerrar Sesión
                                </button>
                            </form>

                        </li>
                    @endauth
                </ul>

            </div>
        </div>
    </nav>


    {{-- ================= CONTENIDO PRINCIPAL ================= --}}
    <main class="container py-4">
        @yield('content')
    </main>


    {{-- ================= SCRIPTS ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script general --}}
    <script src="{{ asset('js/app.js') }}" defer></script>

    {{-- Confirmación logout --}}
    <script>
        function confirmLogout() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: 'Tu sesión se cerrará.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    @stack('scripts')

</body>
</html>
