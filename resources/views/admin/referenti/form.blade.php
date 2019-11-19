@extends('layouts.app')

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
