
<label for="utg">Unità di gestione:</label>
<select name="utg" id="utg" class="form-control select2" data-placeholder="@if (count($utg)) Seleziona le unità di gestione @else Nessuna unità di gestione disponibile @endif " style="width: 100%;">
<option value="0">Seleziona....</option>
  @foreach($utg as $id => $nome)
	<option value="{{$id}}" 
		@if ( 
			( isset($utg_associate) && array_key_exists($id, $utg_associate) ) || collect(old('utg'))->contains($id) 
			) selected="selected" @endif
		>{{$nome}}
	</option>
@endforeach
</select>