
<label for="zone">Zone:</label>
<select multiple="multiple" name="zone[]" id="zone" class="form-control select2" data-placeholder="@if (count($zone)) Seleziona le zone @else Nessuna zona disponibile @endif " style="width: 100%;">
@foreach($zone as $id => $nome)
	<option value="{{$id}}" 
		@if ( 
			( isset($zone_associate) && array_key_exists($id, $zone_associate) ) || collect(old('zone'))->contains($id) 
			) selected="selected" @endif
		>{{$nome}}
	</option>
@endforeach
</select>