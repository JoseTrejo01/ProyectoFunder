<div class="form-group">
    <label>Organización</label>
    <select name="id_organizacion" class="form-control" required>
        @foreach($organizaciones as $org)
            <option value="{{ $org->Id_Organizacion }}" {{ (isset($evaluacion) && $evaluacion->id_organizacion == $org->Id_Organizacion) ? 'selected' : '' }}>
                {{ $org->Nombre_Organizacion }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Criterio</label>
    <select name="criterio_id" class="form-control" required>
        <option value="1" {{ old('criterio_id', $evaluacion->criterio_id ?? '') == '1' ? 'selected' : '' }}>Excelente</option>
        <option value="2" {{ old('criterio_id', $evaluacion->criterio_id ?? '') == '2' ? 'selected' : '' }}>Bueno</option>
        <option value="3" {{ old('criterio_id', $evaluacion->criterio_id ?? '') == '3' ? 'selected' : '' }}>Malo</option>
    </select>
</div>

<div class="form-group">
    <label>Puntuación Inicial</label>
    <input type="number" name="puntuacion_inicial" class="form-control" value="{{ old('puntuacion_inicial', $evaluacion->puntuacion_inicial ?? '') }}">
</div>

<div class="form-group">
    <label>Puntuación Actualizada</label>
    <input type="number" name="puntuacion_actualizada" class="form-control" value="{{ old('puntuacion_actualizada', $evaluacion->puntuacion_actualizada ?? '') }}">
</div>

<div class="form-group">
    <label>Peso</label>
    <input type="number" step="0.01" name="peso" class="form-control" value="{{ old('peso', $evaluacion->peso ?? '') }}">
</div>

<div class="form-group">
    <label>Ponderación Inicial</label>
    <input type="number" step="0.01" name="ponderacion_inicial" class="form-control" value="{{ old('ponderacion_inicial', $evaluacion->ponderacion_inicial ?? '') }}">
</div>

<div class="form-group">
    <label>Ponderación Actual</label>
    <input type="number" step="0.01" name="ponderacion_actual" class="form-control" value="{{ old('ponderacion_actual', $evaluacion->ponderacion_actual ?? '') }}">
</div>

<button type="submit" class="btn btn-primary">Guardar</button>
