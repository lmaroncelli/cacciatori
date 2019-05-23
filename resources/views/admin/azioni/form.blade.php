@extends('layouts.app')

@section('header_css')

	<!-- bootstrap datepicker -->
	<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
	
	<!-- Bootstrap time Picker -->
	<link href="{{ asset('css/bootstrap-timepicker.min.css') }}" rel="stylesheet">

	<!-- Select2 -->
	<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">

@endsection

@section('content')
	
	@if ($azione->exists)
		
		<form action="{{ route('azioni.destroy', $azione->id) }}" method="POST" id="record_delete">
			{{ method_field('DELETE') }}
		  {!! csrf_field() !!}
		  <input type="hidden" name="id" value="{{$azione->id}}">
		</form>
	
		<form role="form" action="{{ route('azioni.update', $azione->id) }}" method="POST">
		{{ method_field('PUT') }}
	@else
		<form role="form" action="{{ route('azioni.store') }}" method="POST" enctype="multipart/form-data">
	@endif
		{!! csrf_field() !!}
		
		
		<div class="form-group">
		  <label for="nome">A.T.C. RIMINI 1</label>
		</div>
		
		{{-- UNICA RIGA --}}
		<div class="row form-group">
			
			<div class="col-md-4">
				<label>Date:</label>

				<div class="input-group date">
				  <div class="input-group-addon">
				    <i class="fa fa-calendar"></i>
				  </div>
				  <input type="text" name="data" @if ($azione->exists) value="{{ old('data') != '' ? old('data') : $azione->dalle->format('d/m/Y') }}" @else value="{{ old('data')}}" @endif class="form-control pull-right" id="datepicker">
				</div>
			</div>
			
			<div class="col-md-4 bootstrap-timepicker">
				<label>Dalle:</label>
				<div class="input-group">
				  <input type="text" name="dal" @if ($azione->exists) value="{{ old('dal') != '' ? old('dal') : $azione->dalle->format('H:i')}}" @endif class="form-control timepicker">

				  <div class="input-group-addon">
				    <i class="fa fa-clock-o"></i>
				  </div>
				</div>
			</div>
			
			<div class="col-md-4 bootstrap-timepicker">
				<label>Alle:</label>
				<div class="input-group">
				  <input type="text" name="al" @if ($azione->exists) value="{{old('al') != ''  ? old('al') : $azione->alle->format('H:i')}}" @endif class="form-control timepicker">

				  <div class="input-group-addon">
				    <i class="fa fa-clock-o"></i>
				  </div>
				</div>
			</div>

		</div>

		<div class="form-group">
		  <label for="squadra_id">Squadra</label>
		  <select class="form-control" style="width: 100%;" name="squadra_id" id="squadra_id">
		    @foreach ([0 => 'Seleziona'] + $squadre as $id => $nome)
		    	<option value="{{$id}}" @if ($azione->squadra_id == $id || old('squadra_id') == $id) selected="selected" @endif>{{$nome}}</option>
		    @endforeach
		  </select>
		</div>	

		
		<div class="form-group" id="distretto_wrapper" style="display: none;">
		  <label for="distretto">Distretto</label>
		  <input type="text" class="form-control" id="distretto" value="">
		</div>

	
		<div class="form-group">
			<label for="note">Note</label>
			<textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $azione->note}}</textarea>
		</div>
		<div class="box-footer">
		<button type="submit" class="btn btn-success">
			@if ($azione->exists)
				Modifica
			@else
				Crea
			@endif
		</button>
		<a href="{{ route('azioni.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
		</div>
		</form>

@endsection

@section('script_footer')
	<!-- Select2 -->
	<script src="{{ asset('js/select2.full.min.js') }}"></script>

	<!-- bootstrap datepicker -->
	<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-datepicker.it.js') }}"></script>

	<!-- bootstrap time picker -->
	<script src="{{ asset('js/bootstrap-timepicker.min.js') }}"></script>

	<script type="text/javascript">
			$(function () {
			    //Initialize Select2 Elements
			    $('.select2').select2();

			    $("#squadra_id").change(function(){
			    	var squadra_id = $(this).val();

			    	jQuery.ajax({
			    	        url: '{{ route('get_distretto') }}',
			    	        type: "post",
			    	        async: false,
			    	        data : { 
			    	               'squadra_id': squadra_id, 
			    	               '_token': jQuery('input[name=_token]').val()
			    	               },
			    	       	success: function(data) {
			    	         jQuery("#distretto").val(data);
			    	         $("#distretto_wrapper").show();
			    	       }
			    	 });

			    });

			});

			var _jsonObjDate = {language: "it", format: 'dd/mm/yyyy', autoclose: true};

		
			$("#datepicker").datepicker(_jsonObjDate);

			//Timepicker
			$('.timepicker').timepicker({
			  language: "it",
			  showInputs: false,
			  showMeridian: false,
			  minuteStep: 1
			})
	</script>
@endsection