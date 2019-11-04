{{--  {{dd($utg, $unita_associate)}}  --}}
<label for="squadre">unita:</label>
<select multiple="multiple" name="unita_gestione_id[]" id="unita_gestione_id" class="form-control select2" data-placeholder="@if (count($utg)) Seleziona le unita @else Nessuna unita disponibile @endif " style="width: 100%;">
@foreach($utg as $id => $nome)
	<option value="{{$id}}" 
		@if ( 
			(isset($unita_associate) && array_key_exists($id, $unita_associate)) || collect(old('unita_gestione_id'))->contains($id) 
			) selected="selected" @endif
		>{{$nome}}
	</option>
@endforeach
</select>
