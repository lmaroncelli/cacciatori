@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('cacciatori.create') }}" title="Nuovo cacciatore" class="btn btn-success">Nuovo cacciatore</a>
	</p>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($cacciatori as $cacciatore)
		    <tr>
		      <td>{{$cacciatore->nome}}</td>
		      <td> <a href="{{ route('cacciatori.edit',$cacciatore->id) }}" title="Modifica cacciatore" class="btn btn-success btn-sm">modifica</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection