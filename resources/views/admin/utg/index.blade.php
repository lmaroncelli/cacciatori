@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('utg.create') }}" title="Nuova Unità gestione" class="btn btn-primary">Nuova Unità gestione</a>
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
		    <tr>
		      <td>{{$unita->nome}}</td>
		      <td>{{$unita->distretto->nome}}</td>
		      <td> <a href="{{ route('utg.edit',$unita->id) }}" title="Modifica unita" class="btn btn-primary btn-sm">modifica</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection