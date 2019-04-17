@extends('layouts.app')

@section('content')
	
	@if ($squadra->exists)
		
		<form action="{{ route('squadre.destroy', $squadra->id) }}" method="POST" id="record_delete">
			{{ method_field('DELETE') }}
		  {!! csrf_field() !!}
		  <input type="hidden" name="id" value="{{$squadra->id}}">
		</form>
	
		<form role="form" action="{{ route('squadre.update', $squadra->id) }}" method="POST">
		{{ method_field('PUT') }}
	@else
		<form role="form" action="{{ route('squadre.store') }}" method="POST" enctype="multipart/form-data">
	@endif
		{!! csrf_field() !!}
		
	
		<div class="form-group">
		  <label for="distretto_id">Distretto</label>
		  <select class="form-control" style="width: 100%;" name="distretto_id" id="distretto_id">
		    @foreach ($distretti as $id => $nome)
		    	<option value="{{$id}}" @if ($squadra->distretto_id == $id || old('distretto_id') == $id) selected="selected" @endif>{{$nome}}</option>
		    @endforeach
		  </select>
		</div>

		<div class="form-group">
		  <label for="nome">Nome</label>
		  <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $squadra->nome}}" required="required">
		</div>
		<div class="form-group">
			<label for="note">Note</label>
			<textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $squadra->note}}</textarea>
		</div>
		<div class="box-footer">
		<button type="submit" class="btn btn-primary">
			@if ($squadra->exists)
				Modifica
			@else
				Crea
			@endif
		</button>
		<a href="{{ route('squadre.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
		</div>
		</form>

@endsection