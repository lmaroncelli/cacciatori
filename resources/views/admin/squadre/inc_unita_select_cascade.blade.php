<label for="unita_gestione_id">Unità di gestione</label>
<select class="form-control" style="width: 100%;" name="unita_gestione_id" id="unita_gestione_id">
@if (isset($utg))
	@foreach ($utg as $id => $nome)
		<option value="0">Seleziona...</option>
		<option value="{{$id}}">{{$nome}}</option>
	@endforeach
@else
		<option value="0">Attendi...</option>
@endif
</select>
