@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco zone</h3>
            <div class="box-tools">
              @not_role_and(['cacciatore','admin_ro'])
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('zone.create') }}" title="Nuova Zona" class="btn btn-success"><i class="fa fa-plus"></i> Nuova zona</a>
              </div>
              @endnot_role_and
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
            @if (!$zone->count())
            
            <div class="callout callout-warning" style="margin: 5px;">
              <h4>Attenzione</h4>
              <p>Nessuna zona/particella presente.</p>
            </div>
          
            @else
            
            <table class="table table-hover">
              <colgroup>
                    <col></col>
                    <col></col>
                    <col class="success"></col>
                    <col class="success"></col>
                    <col></col>
                    <col></col>
                    @not_role_and(['cacciatore','admin_ro'])
                    <col></col>
                    <col></col>
                    @endnot_role_and
                    <col></col>
              </colgroup>
              <thead>
                <tr>
                  <th @if ($order_by=='id_utg')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                    <a href="{{url()->current()}}?order_by=id_utg&order={{ $order_by=='id_utg' && $order=='asc' ? 'desc' : 'asc' }}">ID UG</a>
                  </th>
                  <th @if ($order_by=='utg')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                    <a href="{{url()->current()}}?order_by=utg&order={{ $order_by=='utg' && $order=='asc' ? 'desc' : 'asc' }}">Unità di gestione</a>
                  </th>
                  <th @if ($order_by=='id')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=id&order={{ $order_by=='id' && $order=='asc' ? 'desc' : 'asc' }}">ID</a>
                  </th>
                  <th @if ($order_by=='nome')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=nome&order={{ $order_by=='nome' && $order=='asc' ? 'desc' : 'asc' }}">Nome</a>
                  </th>
                  <th @if ($order_by=='tipo')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                    <a href="{{url()->current()}}?order_by=tipo&order={{ $order_by=='tipo' && $order=='asc' ? 'desc' : 'asc' }}">Tipo</a>
                  </th>
                  <th>Squadre</th>
                  @not_role_and(['cacciatore','admin_ro'])                  
                  <th>Referenti</th>
                  <th></th>
                  <th></th>
                  @endnot_role_and
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($zone as $zona)
                  <form action="{{ route('zone.destroy', $zona->id) }}" id="form_{{$zona->id}}" method="POST">
                    {!! csrf_field() !!}
                    @method('DELETE')
                  </form>
                  <tr>
                    <td>{{optional($zona->unita)->id}}</td>
                    <td>{{optional($zona->unita)->nome}}</td>
                    <td>{{$zona->id}}</td>
                    <td>{{$zona->nome}}</td>
                    <td>{{$zona->tipo}}</td>
                    <td>{{$zona->getSquadre()}}</td>
                    
                    @not_role_and(['cacciatore','admin_ro'])
                    <td>
                    {{ $zona->referenti->count() ? 'Sì' : 'No' }}
                    </td>
                    <td> <a href="{{ route('zone.edit',$zona->id) }}" title="Modifica zona" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
                    @endnot_role_and
                    
                    <td> <a href="{{ route('zone.show',$zona->id) }}" title="Visualizza zona" class="btn btn-warning btn-sm"><i class="fa fa-map"></i> visualizza</a> </td>
                    
                    @not_role_and(['cacciatore','admin_ro'])
                    <td>
                      <button type="button" class="btn btn-danger btn-flat delete btn-sm" data-id="{{$zona->id}}"><i class="fa fa-trash"></i> elimina</button>
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