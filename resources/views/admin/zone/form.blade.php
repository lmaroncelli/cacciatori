@extends('layouts.app')


@section('titolo')
Zona
@endsection

@section('titolo_small')
nuova
@endsection


@section('back')
<a href="{{ route('zone.index') }}"><i class="fa fa-step-backward"></i> back </a>
@endsection

@section('header_css')
	<!-- Select2 -->
	<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection


@section('content')
	<div class="row">    
    <div class="col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Zona</h3> 
          @if ($zona->exists) 
            <div class="text" style="margin:10px 0">Referenti: <span id="elenco_referenti">{{$zona->getReferenti()}}</span></div>
          @endif
          <div>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#referentiModal" id="triggerModal">
              Assegna Referenti
            </button>
          </div>
        </div>
        <!-- /.box-header -->
        @if ($zona->exists)
          
          <form action="{{ route('zone.destroy', $zona->id) }}" method="POST" id="record_delete">
            {{ method_field('DELETE') }}
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{$zona->id}}">
          </form>
        
        <form role="form" action="{{ route('zone.update', $zona->id) }}" method="POST">
            @method('PUT')
        @else
          <form role="form" action="{{ route('zone.store') }}" method="POST" enctype="multipart/form-data">
        @endif
          {!! csrf_field() !!}
          <div class="box-body">
            
            <div class="form-group">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $zona->nome}}" required="required">
            </div>


            <div class="form-group">
              <label for="tipo">Tipo</label>
              <select class="form-control" name="tipo" id="tipo">
                @foreach (App\Utility::getTipoZona() as $key => $nome)
                  <option value="{{$key}}" @if ($zona->tipo == $key || old('zona') == $key) selected="selected" @endif>{{$nome}}</option>
                @endforeach
              </select>
            </div>

              <div class="form-group" id="unita_select">
                @include('admin.inc_unita_select')
              </div>

              <div class="form-group" style="display: none;" id="distretto_wrapper">
                <label for="distretto">Distretto</label>
                <input type="text" class="form-control" name="distretto" id="distretto" value="" readonly="">
              </div>

              <div class="form-group" id="squadre_select">
                @include('admin.inc_squadre_select')
              </div>	

              <div class="form-group">
                <label for="numero">Numero</label>
                <input type="text" class="form-control" name="numero" id="numero" placeholder="numero" value="{{ old('numero') != '' ?  old('numero') : $zona->numero}}" required="required">
              </div>

              <div class="form-group">
                <label for="superficie">Superficie</label>
                <input type="text" class="form-control" name="superficie" id="superficie" placeholder="superficie" value="{{ old('superficie') != '' ?  old('superficie') : $zona->superficie}}">
              </div>

              
              <div class="form-group">
                <label for="note">Note</label>
                <textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $zona->note}}</textarea>
              </div>
          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-success">
              @if ($zona->exists)
                Modifica
              @else
                Crea
              @endif
            </button>
            <a href="{{ route('zone.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
          </div>
          </form>
       </div>
    </div>
  </div>


@if ($zona->exists)
  <!-- Modal -->
  <form action="" method="POST"  id="assegnaReferentiForm">
  <input type="hidden" name="zona_id" value="{{$zona->id}}">
  <div class="modal fade" id="referentiModal" tabindex="-1" role="dialog" aria-labelledby="referentiModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Assegna i referenti di zona</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
                <label for="referente">Referenti</label>
                <span id="msg"></span>
                <select multiple="multiple"  class="form-control select2" data-placeholder="Seleziona i referenti" name="referente" id="referente" style="width: 100%;">
                  @foreach (App\Referente::getAllSelect() as $key => $nome)
                    <option value="{{$key}}" @if ( in_array($key, $zona->getReferentiIds()) || old('referente') == $key ) selected="selected" @endif>{{$nome}}</option>
                  @endforeach
                </select>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
          <button type="button" class="btn btn-primary" id="assegnaReferenti">Salva</button>
        </div>
      </div>
    </div>
  </div>
  </form>
@endif

@endsection


@section('script_footer')
	<!-- Select2 -->
	<script src="{{ asset('js/select2.full.min.js') }}"></script>

	<script type="text/javascript">
				
				function caricaSquadre(distretto_id, zona_id) {
					var distretto_id = distretto_id;
					var zona_id = zona_id;
					
					jQuery.ajax({
					        url: '{{ route('get_squadre') }}',
					        type: "post",
					        async: false,
					        data : { 
					               'distretto_id': distretto_id, 
					               'zona_id': zona_id, 
					               '_token': jQuery('input[name=_token]').val()
					               },
					       	success: function(data) {
					         jQuery("#squadre_select").html(data);
					          $('.select2').select2();
					       }
					 });

				}


				function showDistretto(val) {
					var unita_gestione_id = val;
					
					jQuery.ajax({
					        url: '{{ route('show_distretto') }}',
					        type: "post",
					        dataType: 'json',
					        async: false,
					        data : { 
					               'unita_gestione_id': unita_gestione_id, 
					               '_token': jQuery('input[name=_token]').val()
					               },
					       	success: function(data) {
					         jQuery("#distretto").val(data.nome);
					         jQuery("#distretto_wrapper").show();
					         var distretto_id = data.id;
					        
					         if(distretto_id != 0) {
					         	var zona_id = '{{$zona->id}}';
					         	caricaSquadre(distretto_id, zona_id);
					         }

					       } // success
					 }); // ajax

				} // showDistretto

				$(function () {
				    //Initialize Select2 Elements
				    $('.select2').select2();

				    var unita_gestione_id = $("#unita_gestione_id").val();
            
            //console.log('unita_gestione_id = '+unita_gestione_id);

				    showDistretto(unita_gestione_id);

				    $('#unita_gestione_id').change(function(){
				    	showDistretto(this.value);
				    });

        });
        
        @if ($zona->exists)
        
          $(function() {

            
              $("#triggerModal").click(function(){
                $("#msg").html('');
              });


              $("#assegnaReferenti").click(function(){
                var data = $('#assegnaReferentiForm').serialize();    
                jQuery.ajax({
  					        url: '{{ route('assegna_referenti_zona') }}',
  					        type: "post",
  					        async: false,
  					        data : { 
  					                data: data,
  					               '_token': jQuery('input[name=_token]').val()
  					               },
  					       	success: function(data) {
                        if(data == 'ok') {
                          $("#msg").html('<div class="alert alert-success alert-dismissible">Referenti asseganti correttamente</div>');
                          
                          ///////////////////////////////////////
                          jQuery.ajax({
                              url: '{{ route('aggiorna_referenti_zona') }}',
                              type: "post",
                              async: false,
                              data : { 
                                      zona_id: '{{$zona->id}}',
                                    '_token': jQuery('input[name=_token]').val()
                                    },
                              success: function(data) {
                                  $("#elenco_referenti").text(data);
                              } // success
                          }); // ajax

                          ///////////////////////////////////////
                          
                        }
                        else {
                          $("#msg").html('<div class="alert alert-danger alert-dismissible">Si è verificato un errore imprevisto</div>');
                        }
  					        } // success
  					    }); // ajax
              }); // end click

         }); // function

        @endif
	
  </script>
@endsection