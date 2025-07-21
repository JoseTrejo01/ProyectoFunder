@csrf

<div class="form-group">
    <label for="organizacion_id">Caja Rural</label>
    <select name="organizacion_id" id="organizacion_id" class="form-control">
        <option value="">Seleccione una caja rural</option>
        @foreach($cajas as $caja)
            <option value="{{ $caja->Id_Organizacion }}">{{ $caja->Nombre_Organizacion }}</option>
        @endforeach
    </select>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs" id="ahorroTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="socios-tab" data-toggle="tab" href="#socios" role="tab">Socios</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="no-socios-tab" data-toggle="tab" href="#no-socios" role="tab">No Socios</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="totales-tab" data-toggle="tab" href="#totales" role="tab">Totales</a>
    </li>
</ul>

<div class="tab-content mt-3" id="ahorroTabsContent">
    {{-- Tab: Socios --}}
    <div class="tab-pane fade show active" id="socios" role="tabpanel">
        <div class="form-group">
            <label for="socios_no">No. de Socios</label>
            <input type="number" name="socios_no" id="socios_no" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="socios_ahorros">Ahorros</label>
            <input type="number" name="socios_ahorros" class="form-control" step="0.01">
        </div>
        <div class="form-group">
            <label for="socios_promedio">Promedio</label>
            <input type="number" name="socios_promedio" class="form-control" step="0.01">
        </div>
    </div>

    {{-- Tab: No Socios --}}
    <div class="tab-pane fade" id="no-socios" role="tabpanel">
        {{-- ... aquí va tu código para no socios ... --}}
    </div>

    {{-- Tab: Totales --}}
    <div class="tab-pane fade" id="totales" role="tabpanel">
        {{-- ... aquí va tu código para totales ... --}}
    </div>
</div>

{{-- Botón Guardar --}}
<div class="form-group mt-3">
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>

{{-- Script para cargar automáticamente el número de socios --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const select = document.getElementById("organizacion_id");
        const sociosNoInput = document.getElementById("socios_no");

        select.addEventListener("change", function () {
            const organizacionId = this.value;
            if (organizacionId) {
                fetch(`/organizacion/${organizacionId}/socios`)
                    .then(response => response.json())
                    .then(data => {
                        sociosNoInput.value = data.total_socios ?? 0;
                    })
                    .catch(error => {
                        console.error('Error al cargar socios:', error);
                        sociosNoInput.value = '';
                    });
            } else {
                sociosNoInput.value = '';
            }
        });
    });
</script>

