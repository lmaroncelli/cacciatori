@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco province</h3>
            <div class="box-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('province.create') }}" title="Nuova provincia" class="btn btn-success"><i class="fa fa-plus"></i> Nuova provincia</a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">	
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Nome</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($province as $provincia)
                  <tr>
                    <td>{{$provincia->nome}}</td>
                    <td> <a href="{{ route('province.edit',$provincia->id) }}" title="Modifica provincia" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
@endsection