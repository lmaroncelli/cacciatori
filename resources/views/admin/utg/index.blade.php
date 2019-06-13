@extends('layouts.app')

@section('content')
	<p>
		<a href="{{ route('utg.create') }}" title="Nuova Unità gestione" class="btn btn-success">Nuova Unità gestione</a>
	</p>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th>Distretto</th>
        <th scope="col">Nome</th>
        <th>Zone</th>
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
		      <td>{{optional($unita->distretto)->nome}}</td>
          <td>{{$unita->nome}}</td>
          <td>{{$unita->getZone()}}</td>
		      <td> <a href="{{ route('utg.edit',$unita->id) }}" title="Modifica unita" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
		      <td>
		      	<button type="button" class="btn btn-danger btn-flat delete pull-right btn-sm" data-id="{{$unita->id}}"><i class="fa fa-trash"></i> elimina</button>
		      </td>
		    </tr>
	  	@endforeach
	  </tbody>
	</table>
@endsection