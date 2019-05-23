@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('distretti.create') }}" title="Nuovo distretto" class="btn btn-success">Nuovo distretto</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th></th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($distretti as $distretto)
		    <form action="{{ route('distretti.destroy', $distretto->id) }}" id="form_{{$distretto->id}}" method="POST" id="record_delete">
		      {!! csrf_field() !!}
		      @method('DELETE')
		    </form>
		    <tr>
		      <td>{{$distretto->nome}}</td>
		      <td> <a href="{{ route('distretti.edit',$distretto->id) }}" title="Modifica distretto" class="btn btn-success btn-sm">modifica</a> </td>
		      <td> <a href="{{ route('distretti.show',$distretto->id) }}" title="Visualizza distretto" class="btn btn-warning btn-sm">visualizza</a> </td>
		      <td>
		      	<button type="button" class="btn btn-danger btn-flat delete pull-right btn-sm" data-id="{{$distretto->id}}">elimina</button>
		      </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection