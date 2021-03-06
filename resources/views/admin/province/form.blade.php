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
        @if ($provincia->exists)
          
          <form action="{{ route('province.destroy', $provincia->id) }}" method="POST" id="record_delete">
            {{ method_field('DELETE') }}
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{$provincia->id}}">
          </form>
        
          <form role="form" action="{{ route('province.update', $provincia->id) }}" method="POST">
          {{ method_field('PUT') }}
        @else
          <form role="form" action="{{ route('province.store') }}" method="POST" enctype="multipart/form-data">
        @endif
          {!! csrf_field() !!}
          <div class="box-body">
            <div class="form-group" id="squadre_select">
              @include('admin.inc_comuni_select')
            </div>		

            <div class="form-group">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" value="{{ old('nome') != '' ?  old('nome') : $provincia->nome}}" required="required">
            </div>
          </div>
          
          <div class="box-footer">
            <button type="submit" class="btn btn-success">
              @if ($provincia->exists)
                Modifica
              @else
                Crea
              @endif
            </button>
            <a href="{{ route('province.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
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