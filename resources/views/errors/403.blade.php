@extends('adminlte::page')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Acceso denegado',
            text: '{{ $mensaje ?? 'No tiene permisos para acceder a esta sección.' }}',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.history.back();
        });
    </script>
@endsection
