@extends('layouts.app')


@section('header_css')
	<!-- Select2 -->
	<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection


@section('content')
	<div class="row">    
    <div class="col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Distretto</h3>
        </div>
        <!-- /.box-header -->
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
          <div class="box-body">
        
            <div class="form-group">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $utg->nome}}" required="required">
            </div>
            
            <div class="form-group">
              <label for="distretto_id">Distretto</label>
              <select class="form-control" style="width: 100%;" name="distretto_id" id="distretto_id">
                @foreach ($distretti as $id => $nome)
                  <option value="{{$id}}" @if ($utg->distretto_id == $id || old('distretto_id') == $id) selected="selected" @endif>{{$nome}}</option>
                @endforeach
              </select>
            </div>
            

            <div class="form-group" id="squadre_select">
              @include('admin.inc_zone_select')
            </div>	

            
            <div class="form-group">
              <label for="note">Note</label>
              <textarea name="note" id="note" class="form-control">{{old('note') != '' ?  old('note') : $utg->note}}</textarea>
            </div>
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
        </div>
    </div>
  </div>
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