@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco unità di gestione</h3>
            <div class="box-tools">
              @not_role_and(['cacciatore','admin_ro'])
                <div class="input-group input-group-sm" style="width: 150px;">
                    <a href="{{ route('utg.create') }}" title="Nuova Unità gestione" class="btn btn-success"><i class="fa fa-plus"></i> Nuova Unità gestione</a>
                </div>
              @endnot_role_and
            </div>
          </div>
          <div class="box-body table-responsive no-padding">	
            @if (!$utg->count())
              <div class="callout callout-warning" style="margin: 5px;">
                <h4>Attenzione</h4>
                <p>Nessuna unità gestione presente.</p>
              </div>
            @else
            <table class="table table-hover">
              <colgroup>
                  <col></col>
                  <col class="success"></col>
                  <col class="success"></col>
                  <col></col>
                  @not_role_and(['cacciatore','admin_ro'])
                  <col></col>
                  <col></col>
                  @endnot_role_and
                  <col></col>
              </colgroup>
              <thead>
                <tr>
                  <th @if ($order_by=='distretto')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=distretto&order={{ $order_by=='distretto' && $order=='asc' ? 'desc' : 'asc' }}">Distretto</a>
                  </th>
                  <th @if ($order_by=='id')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=id&order={{ $order_by=='id' && $order=='asc' ? 'desc' : 'asc' }}">ID</a>
                  </th>
                  <th scope="col" @if ($order_by=='nome')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=nome&order={{ $order_by=='nome' && $order=='asc' ? 'desc' : 'asc' }}">Nome</a>
                  </th>
                  <th>Quadranti</th>
                  @not_role_and(['cacciatore','admin_ro'])
                  <th></th>
                  <th></th>
                  @endnot_role_and
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($utg as $unita)
                  <form action="{{ route('utg.destroy', $unita->id) }}" id="form_{{$unita->id}}" method="POST">
                    {!! csrf_field() !!}
                    @method('DELETE')
                  </form>
                  <tr>
                    <td>{{optional($unita->distretto)->nome}}</td>
                    <td>{{$unita->id}}</td>
                    <td>{{$unita->nome}}</td>
                    <td>{{$unita->getZone()}}</td>

                    @not_role_and(['cacciatore','admin_ro'])
                    <td> <a href="{{ route('utg.edit',$unita->id) }}" title="Modifica unita" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
                    @endnot_role_and

                    <td> <a href="{{ route('utg.show',$unita->id) }}" title="Visualizza unita" class="btn btn-warning btn-sm"><i class="fa fa-map"></i> visualizza</a> </td>

                    @not_role_and(['cacciatore','admin_ro'])
                    <td>
                      <button type="button" class="btn btn-danger btn-flat delete pull-right btn-sm" data-id="{{$unita->id}}"><i class="fa fa-trash"></i> elimina</button>
                    </td>
                    @endnot_role_and
                  </tr>
                @endforeach
              </tbody>
            </table>
            @endif

          </div>
      </div>
    </div>
  </div>
@endsection