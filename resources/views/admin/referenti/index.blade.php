@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco referenti</h3>
            <div class="box-tools">
              @not_role('admin_ro')
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('referenti.create') }}" title="Nuovo referente" class="btn btn-success"><i class="fa fa-plus"></i> Nuovo referente</a>
              </div>
              @endnot_role
            </div>
          </div>
          <div class="box-body table-responsive no-padding">	
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Nome</th>
                  <th>Numero</th>
                  <th>Dipartimento</th>
                  <th>Zone di assegnazione</th>
                  @not_role('admin_ro')
                  <th></th>
                  <th></th>
                  @endnot_role
                </tr>
              </thead>
              <tbody>
                @foreach ($ref as $referente)
                  <form action="{{ route('referenti.destroy', $referente->id) }}" id="form_{{$referente->id}}" method="POST">
                    {!! csrf_field() !!}
                    @method('DELETE')
                  </form>
                  <tr>
                    <td>{{$referente->nome}}</td>
                    <td>{{$referente->telefono}}</td>
                    <td>{{$referente->dipartimento}}</td>
                    <td>{{$referente->getZone()}}</td>
                    @not_role('admin_ro')
                    <td> <a href="{{ route('referenti.edit',$referente->id) }}" title="Modifica referente" class="btn btn-success btn-sm">modifica</a> </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-flat delete btn-sm" data-id="{{$referente->id}}"><i class="fa fa-trash"></i> elimina</button>
                    </td>
                    @endnot_role
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
@endsection