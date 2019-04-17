@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('distretti.create') }}" title="Nuovo distretto" class="btn btn-primary">Nuovo distretto</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($distretti as $distretto)
		    <tr>
		      <td>{{$distretto->nome}}</td>
		      <td> <a href="{{ route('distretti.edit',$distretto->id) }}" title="Modifica distretto" class="btn btn-primary btn-sm">modifica</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection