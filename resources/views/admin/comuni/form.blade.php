@extends('layouts.app')


@section('content')
	
	@if ($comune->exists)
		
		<form action="{{ route('comuni.destroy', $comune->id) }}" method="POST" id="record_delete">
			{{ method_field('DELETE') }}
		  {!! csrf_field() !!}
		  <input type="hidden" name="id" value="{{$comune->id}}">
		</form>
	
		<form role="form" action="{{ route('comuni.update', $comune->id) }}" method="POST">
		{{ method_field('PUT') }}
	@else
		<form role="form" action="{{ route('comuni.store') }}" method="POST" enctype="multipart/form-data">
	@endif
		{!! csrf_field() !!}
		
		<div class="form-group">
		  <label for="nome">Nome</label>
		  <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $comune->nome}}" required="required">
		</div>
		
		<div class="form-group">
		  <label for="provincia_id">Provincia</label>
		  <select class="form-control" style="width: 100%;" name="provincia_id" id="provincia_id">
		    @foreach ($province as $id => $nome)
		    	<option value="{{$id}}" @if ($comune->provincia_id == $id || old('provincia_id') == $id) selected="selected" @endif>{{$nome}}</option>
		    @endforeach
		  </select>
		</div>
	
		<div class="box-footer">
		<button type="submit" class="btn btn-primary">
			@if ($comune->exists)
				Modifica
			@else
				Crea
			@endif
		</button>
		<a href="{{ route('comuni.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
		</div>
		</form>

@endsection
