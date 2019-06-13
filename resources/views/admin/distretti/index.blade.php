@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('distretti.create') }}" title="Nuovo distretto" class="btn btn-success">Nuovo distretto</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th>A.T.C.</th>
	      <th scope="col">Nome</th>
	      <th>Unità di gestione</th>
	      <th></th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($distretti as $distretto)
		    <form action="{{ route('distretti.destroy', $distretto->id) }}" id="form_{{$distretto->id}}" method="POST">
		      {!! csrf_field() !!}
		      @method('DELETE')
		    </form>
		    <tr>
		      <td>ATC RN1</td>
		      <td>{{$distretto->nome}}</td>
		      <td>{{$distretto->getUnita()}}</td>
		      <td> <a href="{{ route('distretti.edit',$distretto->id) }}" title="Modifica distretto" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
		      <td> <a href="{{ route('distretti.show',$distretto->id) }}" title="Visualizza distretto" class="btn btn-warning btn-sm"><i class="fa fa-map"></i> visualizza</a> </td>
		      <td>
		      	<button type="button" class="btn btn-danger btn-flat delete btn-sm" data-id="{{$distretto->id}}"><i class="fa fa-trash"></i> elimina</button>
		      </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection