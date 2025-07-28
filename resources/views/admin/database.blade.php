@extends('adminlte::page')

@section('title', 'Gestión de Base de Datos')

@section('content_header')
    <h1>Configuración de Base de Datos</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card border-info shadow-lg h-100">
            <div class="card-header bg-info text-white">
                <i class="fas fa-download"></i> Generar Backup
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
                <img src="{{ asset('images/database-management.png') }}" alt="Backup"  style="width:120px;">
                <form method="POST" action="{{ route('admin.database.backup') }}" class="mt-3 w-100 d-flex flex-column align-items-center">
                    @csrf
                    <button type="submit" class="btn btn-info btn-lg w-75">
                        <i class="fas fa-download"></i> Generar Backup
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-danger shadow-lg h-100">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-upload"></i> Restaurar Backup
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
                <img src="{{ asset('images/database (1).png') }}" alt="Restore" style="width:120px;">
                <form method="POST" action="{{ route('admin.database.restore') }}" enctype="multipart/form-data" class="mt-3 w-100 d-flex flex-column align-items-center">
                    @csrf
                    <div class="form-group w-75">
                        <label for="backup_file" class="mb-2">Arrastra tu archivo .sql aquí o haz clic para seleccionarlo</label>
                        <input type="file" name="backup_file" id="backup_file" class="form-control-file" accept=".sql" required style="margin:auto;">
                    </div>
                    <button type="submit" class="btn btn-danger btn-lg mt-2 w-75">
                        <i class="fas fa-upload"></i> Restaurar Base de Datos
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
        });
    @endif
</script>
@endsection