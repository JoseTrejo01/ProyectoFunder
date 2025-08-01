<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Botón lateral -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Espaciador que empuja el logout hacia la derecha -->
    <div class="flex-grow-1"></div>

    <!-- Botón de cerrar sesión a la derecha -->
    <div class="d-flex align-items-center me-3">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-power-off"></i> Cerrar Sesión
            </button>
        </form>
    </div>

</nav>
