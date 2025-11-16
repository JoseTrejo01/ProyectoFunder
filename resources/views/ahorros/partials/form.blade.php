<div class="form-group mb-3">
    <label for="Id_Organizacion" class="form-label">Caja Rural</label>
    <select name="Id_Organizacion" id="Id_Organizacion" class="form-select" required>
        <option value="">-- Seleccione --</option>
        @foreach($cajas as $caja)
            <option value="{{ $caja->Id_Organizacion }}">{{ $caja->Nombre_Organizacion }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label for="Id_Beneficiario" class="form-label">Beneficiario</label>
    <select name="Id_Beneficiario" id="Id_Beneficiario" class="form-select" required>
        <option value="">Seleccione una Caja primero</option>
    </select>
</div>

<div class="form-group mb-3">
    <label for="Monto" class="form-label">Monto</label>
    <input type="number" step="0.01" name="Monto" id="Monto" class="form-control" required>
</div>

<div class="form-group mb-3">
    <label for="Fecha" class="form-label">Fecha</label>
    <input type="date" name="Fecha" id="Fecha" class="form-control" required>
</div>

@section('js')
<script>

document.getElementById('Id_Organizacion').addEventListener('change', async function () {

    const id = this.value;
    const beneficiariosSelect = document.getElementById('Id_Beneficiario');

    if (!id) {
        beneficiariosSelect.innerHTML = `<option value="">Seleccione una Caja primero</option>`;
        return;
    }

    // Mostrar estado de carga
    beneficiariosSelect.innerHTML = `<option value="">Cargando beneficiarios...</option>`;

    try {
        const response = await fetch(`/api/ahorros/caja/${id}/socios`);
        const data = await response.json();

        beneficiariosSelect.innerHTML = `<option value="">-- Seleccione un Beneficiario --</option>`;

        // SOCIOS
        if (data.socios?.length) {
            data.socios.forEach(s => {
                const option = document.createElement('option');
                option.value = s.Id_Beneficiario;
                option.textContent = `${s.Nombre_Beneficiario} (Socio)`;
                beneficiariosSelect.appendChild(option);
            });
        }

        // NO SOCIOS
        if (data.no_socios?.length) {
            data.no_socios.forEach(n => {
                const option = document.createElement('option');
                option.value = n.Id_Beneficiario;
                option.textContent = `${n.Nombre_Beneficiario} (No Socio)`;
                beneficiariosSelect.appendChild(option);
            });
        }

        // Si no hay beneficiarios
        if (data.socios.length === 0 && data.no_socios.length === 0) {
            beneficiariosSelect.innerHTML = `<option value="">No hay beneficiarios disponibles</option>`;
        }

    } catch (error) {
        console.error(error);

        beneficiariosSelect.innerHTML = `<option value="">Error al cargar</option>`;

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cargar la lista de beneficiarios.',
            confirmButtonColor: '#d33'
        });
    }
});
</script>
@stop
