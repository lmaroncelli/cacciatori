@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco cacciatori</h3>
            <div class="box-tools">
              @not_role('admin_ro')
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('cacciatori.create') }}" title="Nuovo cacciatore" class="btn btn-success"><i class="fa fa-plus"></i> Nuovo cacciatore</a>
              </div>
              @endnot_role
            </div>
          </div>
          <div class="box-body table-responsive no-padding">	
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Nome</th>
                  <th>Email</th>
                  <th>Squadre</th>
                  <th>CapoSquadra</th>
                  <th>Login</th>
                  @not_role('admin_ro')
                  <th></th>
                  @endnot_role
                </tr>
              </thead>
              <tbody>
                @foreach ($cacciatori as $cacciatore)
                  <tr>
                    <td>{{$cacciatore->nome}} {{$cacciatore->cognome}}</td>
                    <td>{{$cacciatore->utente->email}}</td>
                    <td>{{$cacciatore->getSquadre()}}</td>
                    <td>{{$cacciatore->getSquadreACapo()}}</td>
                    <td>
                      @if ($cacciatore->utente->login_capabilities)
                        <i class="fa fa-check text-green"></i>
                      @else
                        <i class="fa fa-times text-red"></i>
                      @endif
                    </td>
                    @not_role('admin_ro')
                    <td> <a href="{{ route('cacciatori.edit',$cacciatore->id) }}" title="Modifica cacciatore" class="btn btn-success btn-sm">modifica</a> </td>
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