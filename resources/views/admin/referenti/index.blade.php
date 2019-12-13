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
                  <th scope="col" @if ($order_by=='nome')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=nome&order={{ $order_by=='nome' && $order=='asc' ? 'desc' : 'asc' }}">Nome</a>
                  </th>
                  <th>Numero</th>
                  <th @if ($order_by=='email')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=email&order={{ $order_by=='email' && $order=='asc' ? 'desc' : 'asc' }}">Email</a>
                  </th>
                  <th @if ($order_by=='dipartimento')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=dipartimento&order={{ $order_by=='dipartimento' && $order=='asc' ? 'desc' : 'asc' }}">Dipartimento</a>
                  </th>
                  <th>Zone di assegnazione</th>
                  <th @if ($order_by=='notice')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=notice&order={{ $order_by=='notice' && $order=='asc' ? 'desc' : 'asc' }}">Notifiche</a>
                  </th>
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
                    <td>{{$referente->email}}</td>
                    <td>{{$referente->dipartimento}}</td>
                    <td>{{$referente->getZoneForTable()}}</td>
                    <td>
                      @if ($referente->notice)
                        <i class="fa fa-check text-green"></i>
                      @else
                        <i class="fa fa-times text-red"></i>
                      @endif
                    </td>
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