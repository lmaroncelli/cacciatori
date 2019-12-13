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
          <h3 class="box-title">Cacciatore</h3>
        </div>
        <!-- /.box-header -->
        @if ($cacciatore->exists)
          <form role="form" action="{{ route('utenti.modifica',$cacciatore->utente->id) }}" method="POST">
          <input type="hidden" name="utente_id" value="{{$cacciatore->utente->id}}">
        @else
          {{-- registro nuovo utente cacciatore --}}
          <form method="POST" action="{{ route('register') }}">
        @endif
        <input type="hidden" name="ruolo" value="cacciatore">
          {!! csrf_field() !!}
          <div class="box-body">

            @if ($cacciatore->exists)
						<div class="form-group has-feedback">        
							<label class="checkbox-inline">
							  <input type="checkbox" name="login_capabilities" value="1" @if ( old('login_capabilities', $cacciatore->utente->hasLoginCapabilites()) ) checked @endif data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-width="150" data-height="25" data-off="Login Disabilitato" data-on="Login Abilitato"> <b>LOGIN</b>
							</label>
						</div>
					  @endif

            <div class="form-group" id="squadre_select">
              @include('admin.inc_squadre_select')
            </div>		

            <div class="form-group">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $cacciatore->nome}}" required="required">
            </div>

            <div class="form-group">
              <label for="cognome">Cognome</label>
              <input type="text" class="form-control" name="cognome" id="cognome" placeholder="cognome" value="{{ old('cognome') != '' ?  old('cognome') : $cacciatore->cognome}}" required="required">
            </div>


            @include('auth._subform_register_cacciatore')

            <div class="form-group">
              <label for="registro">Registro</label>
              <input type="text" class="form-control" name="registro" id="registro" placeholder="registro" value="{{ old('registro') != '' ?  old('registro') : $cacciatore->registro}}" required="required">
            </div>

            <div class="form-group">
              <label for="telefono">Telefono</label>
              <input type="text" class="form-control" name="telefono" id="telefono" placeholder="telefono" value="{{ old('telefono') != '' ?  old('telefono') : $cacciatore->telefono}}">
            </div>

            <div class="form-group">
              <label for="data_nascita">Data di nascita</label>
              <div class="input-group date">
                {{-- <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div> --}}
                <input type="text" class="form-control pull-right" id="datepicker" name="data_nascita" id="data_nascita" @if ($cacciatore->exists) value="{{ old('data_nascita') != '' ? old('data_nascita') : $cacciatore->data_nascita }}" @else value="{{ old('data_nascita')}}" @endif>
              </div>
              <!-- /.input group -->
            </div>

            <div class="form-group">
              <label for="note">Note</label>
              <textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $cacciatore->note}}</textarea>
            </div>
          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-success">
              @if ($cacciatore->exists)
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