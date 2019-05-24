<label for="zona_id">Zone</label>
<select class="form-control" style="width: 100%;" name="zona_id" id="zona_id">
	@if (isset($zone))
		@foreach ($zone as $id => $nome)
			<option value="{{$id}}">{{$nome}}</option>
		@endforeach
	@else
			<option value="0">Attendi...</option>
	@endif
</select>
