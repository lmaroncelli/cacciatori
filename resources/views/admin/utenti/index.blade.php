@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco utenti</h3>
            <div class="box-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('utenti.create') }}" title="Nuovo utente" class="btn btn-success"><i class="fa fa-plus"></i> Nuovo utente</a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">	
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Nome</th>
                  <th>Ruolo</th>
                  <th>Login</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($utenti as $utente)
                  <tr>
                    <td>{{$utente->name}}</td>
                    <td>{{$utente->ruolo}}</td>
                    <td>
                      @if ($utente->login_capabilities)
                        <i class="fa fa-check text-green"></i>
                      @else
                        <i class="fa fa-times text-red"></i>
                      @endif
                    </td>
                    <td> <a href="{{ route('utenti.edit',$utente->id) }}" title="Modifica utente" class="btn btn-success btn-sm">modifica</a> </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
@endsection