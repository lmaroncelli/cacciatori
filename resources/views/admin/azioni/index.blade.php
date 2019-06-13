@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('azioni.create') }}" title="Nuovo distretto" class="btn btn-success">Nuova attività</a>
	</p>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th scope="col">Data</th>
	      <th>Squadra</th>
        <th>Zona</th>
        <th></th>
        <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($azioni as $azione)
		    <form action="{{ route('azioni.destroy', $azione->id) }}" id="form_{{$azione->id}}" method="POST" id="record_delete">
		      {!! csrf_field() !!}
		      @method('DELETE')
		    </form>
		    <tr>
		      <td>{{$azione->getDalleAlle()}}</td>
		      <td>{{$azione->squadra->nome}}</td>
		      <td>{{$azione->zona->nome}}</td>
		      <td> <a href="{{ route('azioni.edit',$azione->id) }}" title="Modifica azione" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>modifica</a> </td>
		      <td>
		      	<button type="button" class="btn btn-danger btn-flat delete pull-right btn-sm" data-id="{{$azione->id}}"><i class="fa fa-trash"></i> elimina</button>
		      </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection