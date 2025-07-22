@extends('adminlte::page')

@section('title', 'Nuevo Ahorro')

@section('content_header')
    <h1>Registrar Nuevo Ahorro</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('ahorros.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="Id_Organizacion">Caja Rural:</label>
            <select name="Id_Organizacion" id="Id_Organizacion" class="form-control" required>
                <option value="">-- Seleccione una --</option>
                @foreach ($organizaciones as $org)
                    <option value="{{ $org->Id_Organizacion }}" {{ old('Id_Organizacion') == $org->Id_Organizacion ? 'selected' : '' }}>
                        {{ $org->Nombre_Organizacion }}
                    </option>
                @endforeach
            </select>
            @error('Id_Organizacion')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="beneficiario_id">Socio:</label>
            <select name="beneficiario_id" id="beneficiario_id" class="form-control" required>
                <option value="">-- Seleccione una caja primero --</option>
            </select>
            @error('beneficiario_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="monto">Monto (L.):</label>
            <input type="number" name="monto" id="monto" step="0.01" class="form-control" value="{{ old('monto') }}" required>
            @error('monto')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha') }}" required>
            @error('fecha')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Guardar Ahorro</button>
    </form>
@stop

@section('js')
<script>
    document.getElementById('Id_Organizacion').addEventListener('change', function() {
        const cajaId = this.value;
        const selectBeneficiario = document.getElementById('beneficiario_id');
        selectBeneficiario.innerHTML = '<option value="">Cargando...</option>';

        if (!cajaId) {
            selectBeneficiario.innerHTML = '<option value="">-- Seleccione una caja primero --</option>';
            return;
        }

        fetch(`/api/cajas/${cajaId}/socios`)
            .then(response => response.json())
            .then(data => {
                selectBeneficiario.innerHTML = '<option value="">-- Seleccione un socio --</option>';
                data.forEach(socio => {
                    selectBeneficiario.innerHTML += `<option value="${socio.Id_Beneficiario}">${socio.Nombre}</option>`;
                });
            })
            .catch(() => {
                selectBeneficiario.innerHTML = '<option value="">Error al cargar socios</option>';
            });
    });
</script>
@stop
