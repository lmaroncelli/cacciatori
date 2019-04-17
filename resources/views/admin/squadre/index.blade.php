@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('squadre.create') }}" title="Nuova squadra" class="btn btn-primary">Nuova Squadra</a>
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
	  	@foreach ($squadre as $squadra)
		    <tr>
		      <td>{{$squadra->nome}}</td>
		      <td>{{$squadra->distretto->nome}}</td>
		      <td> <a href="{{ route('squadre.edit',$squadra->id) }}" title="Modifica squadra" class="btn btn-primary btn-sm">modifica</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection