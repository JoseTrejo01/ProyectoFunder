<div class="form-group">
    <label>Nombre y Apellidos</label>
    <input type="text" name="nombre_apellidos" class="form-control" value="{{ $genero->nombre_apellidos ?? '' }}" required>
</div>
<div class="form-group">
    <label>No. de Identidad</label>
    <input type="text" name="identidad" class="form-control" value="{{ $genero->identidad ?? '' }}" required>
</div>
<div class="form-group">
    <label>Cargo</label>
    <input type="text" name="cargo" class="form-control" value="{{ $genero->cargo ?? '' }}" required>
</div>
