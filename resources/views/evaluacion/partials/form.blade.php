{{-- Organización --}}
<div class="form-group mb-3">
    <label for="id_organizacion" class="fw-semibold" aria-label="Seleccionar organización">
        Organización
    </label>

    <select name="id_organizacion"
            id="id_organizacion"
            class="form-control"
            required
            aria-required="true">

        @foreach($organizaciones as $org)
            <option value="{{ $org->Id_Organizacion }}"
                {{ (isset($evaluacion) && $evaluacion->id_organizacion == $org->Id_Organizacion) ? 'selected' : '' }}>
                {{ $org->Nombre_Organizacion }}
            </option>
        @endforeach
    </select>
</div>

{{-- Criterio --}}
<div class="form-group mb-3">
    <label for="criterio_id" class="fw-semibold">Criterio</label>

    <select name="criterio_id"
            id="criterio_id"
            class="form-control"
            required
            aria-required="true">

        <option value="1" {{ old('criterio_id', $evaluacion->criterio_id ?? '') == '1' ? 'selected' : '' }}>
            Excelente
        </option>

        <option value="2" {{ old('criterio_id', $evaluacion->criterio_id ?? '') == '2' ? 'selected' : '' }}>
            Bueno
        </option>

        <option value="3" {{ old('criterio_id', $evaluacion->criterio_id ?? '') == '3' ? 'selected' : '' }}>
            Malo
        </option>
    </select>
</div>

{{-- Puntuación Inicial --}}
<div class="form-group mb-3">
    <label for="puntuacion_inicial" class="fw-semibold">Puntuación Inicial</label>

    <input id="puntuacion_inicial"
           type="number"
           name="puntuacion_inicial"
           class="form-control"
           value="{{ old('puntuacion_inicial', $evaluacion->puntuacion_inicial ?? '') }}"
           aria-label="Puntuación inicial">
</div>

{{-- Puntuación Actualizada --}}
<div class="form-group mb-3">
    <label for="puntuacion_actualizada" class="fw-semibold">Puntuación Actualizada</label>

    <input id="puntuacion_actualizada"
           type="number"
           name="puntuacion_actualizada"
           class="form-control"
           value="{{ old('puntuacion_actualizada', $evaluacion->puntuacion_actualizada ?? '') }}"
           aria-label="Puntuación actualizada">
</div>

{{-- Peso --}}
<div class="form-group mb-3">
    <label for="peso" class="fw-semibold">Peso</label>

    <input id="peso"
           type="number"
           step="0.01"
           name="peso"
           class="form-control"
           value="{{ old('peso', $evaluacion->peso ?? '') }}"
           aria-label="Peso del criterio">
</div>

{{-- Ponderación Inicial --}}
<div class="form-group mb-3">
    <label for="ponderacion_inicial" class="fw-semibold">Ponderación Inicial</label>

    <input id="ponderacion_inicial"
           type="number"
           step="0.01"
           name="ponderacion_inicial"
           class="form-control"
           value="{{ old('ponderacion_inicial', $evaluacion->ponderacion_inicial ?? '') }}"
           aria-label="Ponderación inicial">
</div>

{{-- Ponderación Actual --}}
<div class="form-group mb-3">
    <label for="ponderacion_actual" class="fw-semibold">Ponderación Actual</label>

    <input id="ponderacion_actual"
           type="number"
           step="0.01"
           name="ponderacion_actual"
           class="form-control"
           value="{{ old('ponderacion_actual', $evaluacion->ponderacion_actual ?? '') }}"
           aria-label="Ponderación actual">
</div>

{{-- BOTÓN ESTILO FUNDER --}}
<button type="submit"
        class="btn fw-semibold mt-2"
        style="background-color:#1B5E20; color:white;"
        aria-label="Guardar evaluación">
    Guardar
</button>
