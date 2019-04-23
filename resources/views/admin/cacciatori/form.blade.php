@extends('layouts.app')

@section('header_css')
	<!-- Select2 -->
	<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
	
	@if ($cacciatore->exists)
		
		<form action="{{ route('cacciatori.destroy', $cacciatore->id) }}" method="POST" id="record_delete">
			{{ method_field('DELETE') }}
		  {!! csrf_field() !!}
		  <input type="hidden" name="id" value="{{$cacciatore->id}}">
		</form>
	
		<form role="form" action="{{ route('cacciatori.update', $cacciatore->id) }}" method="POST">
		{{ method_field('PUT') }}
	@else
		{{-- registro nuovo utente cacciatore --}}
		<form method="POST" action="{{ route('register') }}">
	@endif
		{!! csrf_field() !!}
	
		<div class="form-group" id="squadre_select">
			@include('admin.inc_squadre_select')
		</div>		

		<div class="form-group">
		  <label for="nome">Nome</label>
		  <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $cacciatore->nome}}" required="required">
		</div>
		<div class="form-group">
			<label for="note">Note</label>
			<textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $cacciatore->note}}</textarea>
		</div>
		<div class="box-footer">
		<button type="submit" class="btn btn-primary">
			@if ($cacciatore->exists)
				Modifica
			@else
				Crea
			@endif
		</button>
		<a href="{{ route('cacciatori.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
		</div>
		</form>

@endsection

@section('script_footer')
	<!-- Select2 -->
	<script src="{{ asset('js/select2.full.min.js') }}"></script>

	<script type="text/javascript">
				$(function () {
				    //Initialize Select2 Elements
				    $('.select2').select2();

				});
	</script>
@endsection