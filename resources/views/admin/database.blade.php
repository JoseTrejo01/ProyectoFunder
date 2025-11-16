@extends('adminlte::page')

@section('title', 'Gestión de Base de Datos')

@section('content_header')
    <h1 class="font-weight-bold">Configuración de Base de Datos</h1>
@endsection

@section('content')

<div class="row">

    {{-- ================= BACKUP ================= --}}
    <div class="col-md-6">
        <div class="card border-info shadow-lg h-100">

            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="fas fa-download mr-2"></i> Generar Backup
            </div>

            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 320px;">
                
                <img src="{{ asset('images/database-management.png') }}" alt="Backup" class="mb-3" style="width:120px;">

                <form method="POST" action="{{ route('admin.database.backup') }}" class="w-75 text-center">
                    @csrf
                    <button type="submit" class="btn btn-info btn-lg w-100">
                        <i class="fas fa-download"></i> Generar Backup
                    </button>
                </form>

            </div>

        </div>
    </div>


    {{-- ================= RESTAURACIÓN ================= --}}
    <div class="col-md-6">
        <div class="card border-danger shadow-lg h-100">

            <div class="card-header bg-danger text-white d-flex align-items-center">
                <i class="fas fa-upload mr-2"></i> Restaurar Backup
            </div>

            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 320px;">
                
                <img src="{{ asset('images/database (1).png') }}" alt="Restore" class="mb-3" style="width:120px;">

                <form method="POST" action="{{ route('admin.database.restore') }}"
                      enctype="multipart/form-data"
                      class="w-75 text-center">

                    @csrf

                    <div class="form-group text-left">
                        <label for="backup_file" class="font-weight-bold mb-1">Archivo .sql</label>
                        <div class="custom-file">
                            <input type="file" name="backup_file" id="backup_file" class="custom-file-input" accept=".sql" required>
                            <label class="custom-file-label" for="backup_file">Seleccionar archivo...</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 mt-3">
                        <i class="fas fa-upload"></i> Restaurar Base de Datos
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>

@endsection


@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Mostrar nombre del archivo seleccionado
    document.getElementById('backup_file').addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'Seleccionar archivo...';
        this.nextElementSibling.innerText = fileName;
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#28a745',
            timer: 2500
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>
@endsection
