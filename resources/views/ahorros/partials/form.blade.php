<div class="form-group">
    <label for="Id_Organizacion">Caja Rural</label>
    <select name="Id_Organizacion" id="Id_Organizacion" class="form-control" required>
        <option value="">-- Seleccione --</option>
        @foreach($cajas as $caja)
            <option value="{{ $caja->Id_Organizacion }}">{{ $caja->Nombre_Organizacion }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="Id_Beneficiario">Beneficiario</label>
    <select name="Id_Beneficiario" id="Id_Beneficiario" class="form-control" required>
        <option value="">-- Seleccione una Caja primero --</option>
    </select>
</div>

<div class="form-group">
    <label for="Monto">Monto</label>
    <input type="number" step="0.01" name="Monto" id="Monto" class="form-control" required>
</div>

<div class="form-group">
    <label for="Fecha">Fecha</label>
    <input type="date" name="Fecha" id="Fecha" class="form-control" required>
</div>

@section('js')
<script>
    document.getElementById('Id_Organizacion').addEventListener('change', function () {
        let id = this.value;
        if (!id) {
            document.getElementById('Id_Beneficiario').innerHTML = '<option value="">-- Seleccione una Caja primero --</option>';
            return;
        }

        fetch(`/api/ahorros/caja/${id}/socios`)
            .then(res => res.json())
            .then(data => {
                let select = document.getElementById('Id_Beneficiario');
                select.innerHTML = '<option value="">-- Seleccione un Beneficiario --</option>';

                // Agregar socios primero
                data.socios.forEach(socio => {
                    select.innerHTML += `<option value="${socio.Id_Beneficiario}">${socio.Nombre_Beneficiario} (Socio)</option>`;
                });

                // Agregar no socios
                data.no_socios.forEach(noSocio => {
                    select.innerHTML += `<option value="${noSocio.Id_Beneficiario}">${noSocio.Nombre_Beneficiario} (No Socio)</option>`;
                });
            })
            .catch(err => {
                console.error(err);
                alert('No se pudo cargar la lista de beneficiarios.');
            });
    });
</script>
@stop
