@extends('layouts.app')

@section('content')
	
	@if ($distretto->exists)
		
		<form action="{{ route('distretti.destroy', $distretto->id) }}" method="POST" id="record_delete">
			{{ method_field('DELETE') }}
		  {!! csrf_field() !!}
		  <input type="hidden" name="id" value="{{$distretto->id}}">
		</form>
	
		<form role="form" action="{{ route('distretti.update', $distretto->id) }}" method="POST">
		{{ method_field('PUT') }}
	@else
		<form role="form" action="{{ route('distretti.store') }}" method="POST" enctype="multipart/form-data">
	@endif
		{!! csrf_field() !!}
	
		{{-- <div class="form-group" id="squadre_select">
			@include('admin.distretti.inc_squadre_select')
		</div>		 --}}

		<div class="form-group">
		  <label for="nome">Nome</label>
		  <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $distretto->nome}}" required="required">
		</div>
		<div class="form-group">
			<label for="note">Note</label>
			<textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $distretto->note}}</textarea>
		</div>
		<div class="box-footer">
		<button type="submit" class="btn btn-primary">
			@if ($distretto->exists)
				Modifica
			@else
				Crea
			@endif
		</button>
		<a href="{{ url('admin/distretti') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
		</div>
		</form>

@endsection