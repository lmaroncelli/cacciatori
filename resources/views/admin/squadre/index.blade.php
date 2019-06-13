@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('squadre.create') }}" title="Nuova squadra" class="btn btn-success">Nuova Squadra</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th scope="col">Nome</th>
	      <th>Distretto</th>
	      <th>Zone</th>
	      <th></th>
	    </tr>
	  </thead>
	  <tbody>
	  	@foreach ($squadre as $squadra)
		    <tr>
		      <td>{{$squadra->nome}}</td>
          <td>{{optional($squadra->distretto)->nome}}</td>
          <td>{{$squadra->getZone()}}</td>          
		      <td> <a href="{{ route('squadre.edit',$squadra->id) }}" title="Modifica squadra" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection