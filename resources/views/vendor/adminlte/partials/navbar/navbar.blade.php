<nav class="main-header navbar navbar-expand navbar-white navbar-light"
     role="navigation"
     aria-label="Barra de navegación principal">

    <!-- Botón lateral -->
    <ul class="navbar-nav" role="menubar">
        <li class="nav-item" role="none">
            <a class="nav-link"
               data-widget="pushmenu"
               href="#"
               role="menuitem"
               aria-label="Abrir o cerrar menú lateral">
                <i class="fas fa-bars" style="color:#0D47A1;"></i>
            </a>
        </li>
    </ul>

    <!-- Espaciador -->
    <div class="flex-grow-1"></div>

    <!-- Cerrar sesión -->
    <div class="d-flex align-items-center me-3">
        <form action="{{ route('logout') }}" method="POST" class="d-inline" aria-label="Formulario de cierre de sesión">
            @csrf
            <button type="submit"
                    class="btn btn-sm fw-semibold"
                    style="border:1px solid #B71C1C; color:#B71C1C;"
                    aria-label="Cerrar sesión">
                <i class="fas fa-power-off"></i> Cerrar Sesión
            </button>
        </form>
    </div>

</nav>
