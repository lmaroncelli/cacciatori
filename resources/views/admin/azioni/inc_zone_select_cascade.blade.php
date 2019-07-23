
<label for="zone">Zone:</label>
<select name="zona_id" id="zone" class="form-control" data-placeholder="@if (count($zone)) Seleziona la zona @else Nessuna zona disponibile @endif " style="width: 100%;">
<option value="0">Seleziona....</option>
  @foreach($zone as $id => $nome)
	<option value="{{$id}}" 
		@if ( 
			( isset($selected_id) && $selected_id == $id ) || old('zone') == $id  
			) selected="selected" @endif
		>{{$nome}}
	</option>
@endforeach
</select>