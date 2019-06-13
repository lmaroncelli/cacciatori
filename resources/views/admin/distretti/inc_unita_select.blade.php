<label for="utg">Unità di gestione:</label>
<select multiple="multiple" name="utg[]" id="utg" class="form-control select2" data-placeholder="@if (count($utg)) Seleziona le unità @else Nessuna unità disponibile @endif " style="width: 100%;">
@foreach($utg as $id => $nome)
	<option value="{{$id}}" 
		@if ( 
			( isset($unita_associate) && in_array($id, $unita_associate) ) || collect(old('utg'))->contains($id) 
			) selected="selected" @endif
		>{{$nome}}
	</option>
@endforeach
</select>