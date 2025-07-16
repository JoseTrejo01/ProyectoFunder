<div class="form-group">
    <label>Etnia</label>
    <input type="text" name="etnia" class="form-control" value="{{ $genero->etnia ?? '' }}" required>
</div>
<div class="form-group">
    <label>Fecha de Nacimiento</label>
    <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $genero->fecha_nacimiento ?? '' }}" required>
</div>
<div class="form-group">
    <label>Edad</label>
    <input type="number" name="edad" class="form-control" value="{{ $genero->edad ?? '' }}" required>
</div>
