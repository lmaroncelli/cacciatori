
<label for="zone">Quadranti:</label>
<select multiple="multiple" name="zone[]" id="zone" class="form-control select2" data-placeholder="@if (count($zone)) Seleziona i quadranti @else Nessun quadrante disponibile @endif " style="width: 100%;">
@foreach($zone as $id => $nome)
	<option value="{{$id}}" @if ( (isset($zone_associate) &&  in_array($id, $zone_associate)) || collect(old('zone'))->contains($id) ) selected="selected" @endif>{{$nome}}</option>
@endforeach
</select>
