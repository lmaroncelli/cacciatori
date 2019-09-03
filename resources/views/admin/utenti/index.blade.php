@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco utenti</h3>
            <div class="box-tools">
              @not_role('admin_ro')
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('utenti.create') }}" title="Nuovo utente" class="btn btn-success"><i class="fa fa-plus"></i> Nuovo utente</a>
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
                  <th>Ruolo</th>
                  <th>Login</th>
                  @not_role('admin_ro')
                  <th></th>
                  @endnot_role
                </tr>
              </thead>
              <tbody>
                @foreach ($utenti as $utente)
                  <tr>
                    <td>{{$utente->name}}</td>
                    <td>{{$utente->email}}</td>
                    <td>{{ucfirst($utente->ruolo)}}</td>
                    <td>
                      @if ($utente->login_capabilities || $utente->hasRole('admin'))
                        <i class="fa fa-check text-green"></i>
                      @else
                        <i class="fa fa-times text-red"></i>
                      @endif
                    </td>
                    @not_role('admin_ro')
                    <td>
                      {{-- modifico solo se:
                        - non è un admin
                        - è un admin e sono IO
                        - oppure io sono lmaroncelli@gmail.com
                       --}}
                      @if(Auth::user()->email == 'lmaroncelli@gmail.com' || !$utente->hasRole('admin') || Auth::id() == $utente->id)
                        <a href="{{ route('utenti.edit',$utente->id) }}" title="Modifica utente" class="btn btn-success btn-sm">modifica</a>
                      @else
                       <a href="{{ route('utenti.edit',$utente->id) }}" title="Modifica utente" class="btn btn-success btn-sm disabled">modifica</a>
                      @endif

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