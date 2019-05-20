<label for="unita_gestione_id">Unità di gestione</label>
<select class="form-control" style="width: 100%;" name="unita_gestione_id" id="unita_gestione_id">
@foreach ($utg as $id => $nome)
	<option value="{{$id}}">{{$nome}}</option>
@endforeach
</select>
