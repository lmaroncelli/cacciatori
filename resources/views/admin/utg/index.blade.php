@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('utg.create') }}" title="Nuova Unità gestione" class="btn btn-success">Nuova Unità gestione</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th>Distretto</th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($utg as $unita)
	  		<form action="{{ route('utg.destroy', $unita->id) }}" id="form_{{$unita->id}}" method="POST">
	  		  {!! csrf_field() !!}
	  		  @method('DELETE')
	  		</form>
		    <tr>
		      <td>{{$unita->nome}}</td>
		      <td>{{$unita->distretto->nome}}</td>
		      <td> <a href="{{ route('utg.edit',$unita->id) }}" title="Modifica unita" class="btn btn-success btn-sm">modifica</a> </td>
		      <td>
		      	<button type="button" class="btn btn-danger btn-flat delete pull-right btn-sm" data-id="{{$unita->id}}">elimina</button>
		      </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection