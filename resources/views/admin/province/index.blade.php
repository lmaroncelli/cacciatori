@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('province.create') }}" title="Nuova provincia" class="btn btn-primary">Nuova provincia</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($province as $provincia)
		    <tr>
		      <td>{{$provincia->nome}}</td>
		      <td> <a href="{{ route('province.edit',$provincia->id) }}" title="Modifica provincia" class="btn btn-primary btn-sm">modifica</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection