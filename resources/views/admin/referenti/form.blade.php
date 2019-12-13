@extends('layouts.app')


@section('header_css')
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
          <h3 class="box-title">Referente</h3>
        </div>
        <!-- /.box-header -->
        @if ($ref->exists)
          
          <form action="{{ route('referenti.destroy', $ref->id) }}" method="POST" id="record_delete">
            {{ method_field('DELETE') }}
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{$ref->id}}">
          </form>
        
          <form role="form" action="{{ route('referenti.update', $ref->id) }}" method="POST">
          {{ method_field('PUT') }}
        @else
          <form role="form" action="{{ route('referenti.store') }}" method="POST" enctype="multipart/form-data">
        @endif
          {!! csrf_field() !!}
          <div class="box-body">
            


            @if ($ref->exists)
            <input type="hidden" name="notice" value="0">
						<div class="form-group has-feedback">        
							<label class="checkbox-inline">
							  <input type="checkbox" name="notice" value="1" @if (old('notice',$ref->notice)) checked @endif data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-width="150" data-height="25" data-off="NON Notificabile" data-on="Notificabile"> <b>NOTIFICHE</b>
							</label>
						</div>
					  @endif


            <div class="form-group">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $ref->nome}}" required="required">
            </div>
            
            
            <div class="form-group">
              <label for="dipartimento">Dipartimento</label>
              <select class="form-control" name="dipartimento" id="dipartimento">
                @foreach (App\Utility::getDipartimentoReferente() as $key => $nome)
                  <option value="{{$key}}" @if ($ref->dipartimento == $key || old('dipartimento') == $key) selected="selected" @endif>{{$nome}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="telefono">Telefono</label>
              <input type="text" class="form-control" name="telefono" id="telefono" placeholder="telefono" value="{{ old('telefono') != '' ?  old('telefono') : $ref->telefono}}">
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" name="email" id="email" placeholder="email" value="{{ old('email') != '' ?  old('email') : $ref->email}}">
            </div>

            <div class="form-group" id="zone_select">
              <label for="zone">Quadranti:</label>
              <select multiple="multiple" name="zone[]" id="zone" class="form-control select2" data-placeholder="@if (count($zone)) Seleziona i quadranti @else Nessun quadrante disponibile @endif " style="width: 100%;">
              @foreach($zone as $id => $nome)
                <option value="{{$id}}" 
                  @if ( 
                    ( isset($zone_associate) && array_key_exists($id, $zone_associate) ) || collect(old('zone'))->contains($id) 
                    ) selected="selected" @endif
                  >{{$nome}}
                </option>
              @endforeach
              </select>
            </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-success">
              @if ($ref->exists)
                Modifica
              @else
                Crea
              @endif
            </button>
            <a href="{{ route('referenti.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
          </div>

          </form>
        </div>
    </div>
  </div>
@endsection


@section('script_footer')
	<!-- Select2 -->
  <script src="{{ asset('js/select2.full.min.js') }}"></script>
  
  {{-- bootstrap toogle button --}}
  <script src="{{ asset('js/bootstrap-toggle.min.js') }}"></script>

	<script type="text/javascript">
				$(function () {
				    //Initialize Select2 Elements
				    $('.select2').select2();

				});
	</script>
@endsection