@extends('layouts.app')

@section('content')
	
	@if ($utg->exists)
		
		<form action="{{ route('utg.destroy', $utg->id) }}" method="POST" id="record_delete">
			{{ method_field('DELETE') }}
		  {!! csrf_field() !!}
		  <input type="hidden" name="id" value="{{$utg->id}}">
		</form>
	
		<form role="form" action="{{ route('utg.update', $utg->id) }}" method="POST">
		{{ method_field('PUT') }}
	@else
		<form role="form" action="{{ route('utg.store') }}" method="POST" enctype="multipart/form-data">
	@endif
		{!! csrf_field() !!}
		
	
		<div class="form-group">
		  <label for="distretto_id">Distretto</label>
		  <select class="form-control" style="width: 100%;" name="distretto_id" id="distretto_id">
		    @foreach ($distretti as $id => $nome)
		    	<option value="{{$id}}" @if ($utg->distretto_id == $id || old('distretto_id') == $id) selected="selected" @endif>{{$nome}}</option>
		    @endforeach
		  </select>
		</div>

		<div class="form-group">
		  <label for="nome">Nome</label>
		  <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $utg->nome}}" required="required">
		</div>
		<div class="form-group">
			<label for="note">Note</label>
			<textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $utg->note}}</textarea>
		</div>
		<div class="box-footer">
		<button type="submit" class="btn btn-success">
			@if ($utg->exists)
				Modifica
			@else
				Crea
			@endif
		</button>
		<a href="{{ route('utg.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
		</div>
		</form>

@endsection