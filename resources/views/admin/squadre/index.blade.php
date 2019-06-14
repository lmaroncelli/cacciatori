@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco squadre</h3>

            <div class="box-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('squadre.create') }}" title="Nuova squadra" class="btn btn-success"><i class="fa fa-plus"></i> Nuova Squadra</a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <colgroup>
                <col></col>
                <col class="success"></col>
                <col></col>
                <col></col>
            </colgroup>
            <thead>
              <tr>
                <th>Distretto</th>
                <th scope="col">Nome</th>
                <th>Zone</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($squadre as $squadra)
                <tr>
                  <td>{{optional($squadra->distretto)->nome}}</td>
                  <td>{{$squadra->nome}}</td>
                  <td>{{$squadra->getZone()}}</td>          
                  <td> <a href="{{ route('squadre.edit',$squadra->id) }}" title="Modifica squadra" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          </div>
      </div>
    </div>
  </div>
@endsection