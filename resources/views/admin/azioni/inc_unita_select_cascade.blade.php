
<label for="utg">Unità di gestione:</label>
<select name="unita_gestione_id" id="utg" class="form-control" data-placeholder="@if (count($utg)) Seleziona le unità di gestione @else Nessuna unità di gestione disponibile @endif " style="width: 100%;">
<option value="0">Seleziona....</option>
  @foreach($utg as $id => $nome)
	<option value="{{$id}}" 
		@if ( 
			( isset($selected_id) && $selected_id == $id ) || old('utg') == $id  
			) selected="selected" @endif
		>{{$nome}}
	</option>
@endforeach
</select>