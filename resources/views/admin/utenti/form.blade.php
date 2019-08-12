@extends('layouts.app')

@section('header_css')
	<!-- bootstrap datepicker -->
	<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">

	<!-- Select2 -->
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
  
  	{{-- bootstrap toogle button --}}
	<link href="{{ asset('css/bootstrap-toggle.min.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="row">    
    <div class="col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Utente</h3>
        </div>
        <!-- /.box-header -->
        @if ($utente->exists)
          <form role="form" action="{{ route('utenti.modifica',$utente->id) }}" method="POST">
        @else
          {{-- registro nuovo utente cacciatore --}}
          <form method="POST" action="{{ route('register') }}">
        @endif
          {!! csrf_field() !!}
          <div class="box-body">

            @if ($utente->exists)
						<div class="form-group has-feedback">        
							<label class="checkbox-inline">
							  <input type="checkbox" name="login_capabilities" value="1" @if ($utente->hasLoginCapabilites()) checked @endif data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-width="150" data-height="25" data-off="Login Disabilitato" data-on="Login Abilitato"> <b>LOGIN</b>
							</label>
            </div>
            @else
              <div class="form-group">
                <label for="ruolo">Ruolo</label>
                <select class="form-control" name="ruolo" id="ruolo">
                  <option value="consultatore" 
                    @if (old('ruolo') == 'consultatore') selected="selected" @endif
                    >Consultatore</option>
                  <option value="cartografo"
                    @if (old('ruolo') == 'cartografo') selected="selected" @endif                  
                    >Cartografo</option>
                </select>
              </div>
            @endif
            
            <div class="form-group">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $utente->nome}}" required="required">
            </div>

            <div class="form-group">
              <label for="cognome">Cognome</label>
              <input type="text" class="form-control" name="cognome" id="cognome" placeholder="cognome" value="{{ old('cognome') != '' ?  old('cognome') : $utente->cognome}}" required="required">
            </div>


            @include('auth._subform_register_utente')


          <div class="box-footer">
            <button type="submit" class="btn btn-success">
              @if ($utente->exists)
                Modifica
              @else
                Crea
              @endif
            </button>
            <a href="{{ route('cacciatori.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
          </div>
          </form>
        </div>
    </div>
  </div>
@endsection

@section('script_footer')
	<!-- Select2 -->
	<script src="{{ asset('js/select2.full.min.js') }}"></script>

	<!-- bootstrap datepicker -->
	<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-datepicker.it.js') }}"></script>
  
  {{-- bootstrap toogle button --}}
  <script src="{{ asset('js/bootstrap-toggle.min.js') }}"></script>

	<script type="text/javascript">
				$(function () {
				    //Initialize Select2 Elements
				    $('.select2').select2();

				});

				//Date picker
				$('#datepicker').datepicker({
					format: 'dd/mm/yyyy',
				  	autoclose: true,
				});
	</script>
@endsection