@extends('layouts.app')


@section('header_css')
	<!-- Select2 -->
	<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection


@section('content')
	
	@if ($zona->exists)
		
		<form action="{{ route('zone.destroy', $zona->id) }}" method="POST" id="record_delete">
			{{ method_field('DELETE') }}
		  {!! csrf_field() !!}
		  <input type="hidden" name="id" value="{{$zona->id}}">
		</form>
	
		<form role="form" action="{{ route('zone.update', $zona->id) }}" method="POST">
		{{ method_field('PUT') }}
	@else
		<form role="form" action="{{ route('zone.store') }}" method="POST" enctype="multipart/form-data">
	@endif
		{!! csrf_field() !!}
		
	
		<div class="form-group">
		  <label for="unita_gestione_id">UTG</label>
		  <select class="form-control" style="width: 100%;" name="unita_gestione_id" id="unita_gestione_id">
		    @foreach ($utg as $id => $nome)
		    	<option value="{{$id}}" @if ($zona->unita_gestione_id == $id || old('unita_gestione_id') == $id) selected="selected" @endif>{{$nome}}</option>
		    @endforeach
		  </select>
		</div>

		<div class="form-group" id="squadre_select">
			@include('admin.inc_squadre_select')
		</div>	

		<div class="form-group">
		  <label for="numero">Numero</label>
		  <input type="text" class="form-control" name="numero" id="numero" placeholder="numero" value="{{ old('numero') != '' ?  old('numero') : $zona->numero}}" required="required">
		</div>

		<div class="form-group">
		  <label for="nome">Nome</label>
		  <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $zona->nome}}" required="required">
		</div>

		<div class="form-group">
		  <label for="superficie">Superficie</label>
		  <input type="text" class="form-control" name="superficie" id="superficie" placeholder="superficie" value="{{ old('superficie') != '' ?  old('superficie') : $zona->superficie}}">
		</div>

		
		<div class="form-group">
			<label for="note">Note</label>
			<textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $zona->note}}</textarea>
		</div>
		<div class="box-footer">
		<button type="submit" class="btn btn-primary">
			@if ($zona->exists)
				Modifica
			@else
				Crea
			@endif
		</button>
		<a href="{{ route('zone.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
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