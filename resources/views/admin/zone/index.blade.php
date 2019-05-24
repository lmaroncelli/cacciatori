@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('zone.create') }}" title="Nuova Zona" class="btn btn-success">Nuova zona</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th>Numero</th>
	      <th>Unità di gestione</th>
	      <th></th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($zone as $zona)
	  		<form action="{{ route('zone.destroy', $zona->id) }}" id="form_{{$zona->id}}" method="POST">
	  		  {!! csrf_field() !!}
	  		  @method('DELETE')
	  		</form>
		    <tr>
		      <td>{{$zona->nome}}</td>
		      <td>{{$zona->numero}}</td>
		      <td>{{$zona->unita->nome}}</td>
		      <td> <a href="{{ route('zone.edit',$zona->id) }}" title="Modifica zona" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
		      <td> <a href="{{ route('zone.show',$zona->id) }}" title="Visualizza zona" class="btn btn-warning btn-sm"><i class="fa fa-map"></i> visualizza</a> </td>
		      <td>
		      	<button type="button" class="btn btn-danger btn-flat delete btn-sm" data-id="{{$zona->id}}"><i class="fa fa-trash"></i> elimina</button>
		      </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection