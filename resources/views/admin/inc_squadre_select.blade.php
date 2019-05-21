
<label for="squadre">Squadre:</label>
<select multiple="multiple" name="squadre[]" id="squadre" class="form-control select2" data-placeholder="@if (count($squadre)) Seleziona le squadre @else Nessuna squadra disponibile @endif " style="width: 100%;">
@foreach($squadre as $id => $nome)
	<option value="{{$id}}" 
		@if ( 
			(isset($squadre_associate) && in_array($id, $squadre_associate)) || collect(old('squadre'))->contains($id) 
			) selected="selected" @endif
		>{{$nome}}
	</option>
@endforeach
</select>
