@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco attività</h3>

            <div class="box-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('azioni.create') }}" title="Nuovo distretto" class="btn btn-success"><i class="fa fa-plus"></i> Nuova attività</a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Data</th>
                  <th>Squadra</th>
                  <th>Distretto</th>
                  <th>UTG</th>
                  <th>Zona</th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($azioni as $azione)
                  <form action="{{ route('azioni.destroy', $azione->id) }}" id="form_{{$azione->id}}" method="POST" id="record_delete">
                    {!! csrf_field() !!}
                    @method('DELETE')
                  </form>
                  <tr>
                    <td>{{$azione->getDalleAlle()}}</td>
                    <td>{{$azione->squadra->nome}}</td>
                    <td>{{$azione->distretto->nome}}</td>
                    <td>{{$azione->unita->nome}}</td>
                    <td>{{$azione->zona->nome}}</td>
                    <td> <a href="{{ route('azioni.edit',$azione->id) }}" title="Modifica azione" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>modifica</a> </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-flat delete pull-right btn-sm" data-id="{{$azione->id}}"><i class="fa fa-trash"></i> elimina</button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
@endsection