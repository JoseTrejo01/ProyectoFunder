<div class="form-group">
    <label>Nombre de Caja Rural</label>
    <input type="text" name="nombre_caja_rural" class="form-control" value="{{ $genero->nombre_caja_rural ?? '' }}" required>
</div>
<div class="form-group">
    <label>Departamento</label>
    <input type="text" name="departamento" class="form-control" value="{{ $genero->departamento ?? '' }}" required>
</div>
<div class="form-group">
    <label>Municipio</label>
    <input type="text" name="municipio" class="form-control" value="{{ $genero->municipio ?? '' }}" required>
</div>
<div class="form-group">
    <label>Comunidad</label>
    <input type="text" name="comunidad" class="form-control" value="{{ $genero->comunidad ?? '' }}" required>
</div>
