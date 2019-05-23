@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('comuni.create') }}" title="Nuovo comune" class="btn btn-success">Nuovo comune</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($comuni as $comune)
		    <tr>
		      <td>{{$comune->nome}}</td>
		      <td> <a href="{{ route('comuni.edit',$comune->id) }}" title="Modifica comune" class="btn btn-success btn-sm">modifica</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection