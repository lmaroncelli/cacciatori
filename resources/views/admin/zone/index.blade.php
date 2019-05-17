@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('zone.create') }}" title="Nuova Zona" class="btn btn-primary">Nuova zona</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th>Numero</th>
	      <th>Unità di gestione</th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($zone as $zona)
		    <tr>
		      <td>{{$zona->nome}}</td>
		      <td>{{$zona->numero}}</td>
		      <td>{{$zona->unita->nome}}</td>
		      <td> <a href="{{ route('zone.edit',$zona->id) }}" title="Modifica zona" class="btn btn-primary btn-sm">modifica</a> </td>
		      <td> <a href="{{ route('zone.show',$zona->id) }}" title="Visualizza zona" class="btn btn-warning btn-sm">visualizza</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection