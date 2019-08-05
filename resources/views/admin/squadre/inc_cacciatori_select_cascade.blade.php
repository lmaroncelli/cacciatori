
<label for="cacciatori">Cacciatori:</label>
<select multiple="multiple" name="cacciatori[]" id="cacciatori" class="form-control select2" data-placeholder="@if (count($cacciatori)) Seleziona i cacciatori @else Nessun cacciatore disponibile @endif " style="width: 100%;">
@foreach($cacciatori as $id => $nome)
	<option value="{{$id}}" 
		@if ( 
			( isset($cacciatori_squadra) && array_key_exists($id, $cacciatori_squadra) ) || collect(old('cacciatori'))->contains($id) 
			) selected="selected" @endif
		>{{$nome}}
	</option>
@endforeach
</select>