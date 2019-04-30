
<label for="comuni">Comuni:</label>
<select multiple="multiple" name="comuni[]" id="comuni" class="form-control select2" data-placeholder="@if (count($comuni)) Seleziona i comuni @else Nessun comune disponibile @endif " style="width: 100%;">
@foreach($comuni as $id => $nome)
	<option value="{{$id}}" @if ( array_key_exists($id, $comuni_associati) || collect(old('comuni'))->contains($id) ) selected="selected" @endif>{{$nome}}</option>
@endforeach
</select>
