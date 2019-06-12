@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('province.create') }}" title="Nuova provincia" class="btn btn-success">Nuova provincia</a>
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
		      <td> <a href="{{ route('province.edit',$provincia->id) }}" title="Modifica provincia" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection