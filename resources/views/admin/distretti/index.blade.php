@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco distretti</h3>
            <div class="box-tools">
              @not_role_and(['cacciatore','admin_ro'])
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('distretti.create') }}" title="Nuovo distretto" class="btn btn-success"><i class="fa fa-plus"></i> Nuovo distretto</a>
              </div>
              @endnot_role_and
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
          
          @if (!$distretti->count())
            <div class="callout callout-warning" style="margin: 5px;">
                <h4>Attenzione</h4>
                <p>Nessun distretto presente.</p>
            </div>
          @else
              <div>
              {{$order_by}} {{$order}}
            </div>
            <table class="table table-hover">
              <colgroup>
                  <col></col>
                  <col class="success"></col>
                  <col></col>
                  @not_role('cacciatore')
                  <col></col>
                  <col></col>
                  @endnot_role
              </colgroup>
              <thead>
                <tr>
                  <th>A.T.C.</th>
                  <th scope="col" @if ($order_by=='nome')
                    class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                    @endif>
                      <a href="{{url()->current()}}?order_by=nome&order={{ $order_by=='nome' && $order=='asc' ? 'desc' : 'asc' }}">Distretto</a>
                  </th>
                  <th>Unità di gestione</th>
                  @not_role('cacciatore')
                  <th></th>
                  <th></th>
                  @endnot_role
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($distretti as $distretto)
                  <form action="{{ route('distretti.destroy', $distretto->id) }}" id="form_{{$distretto->id}}" method="POST">
                    {!! csrf_field() !!}
                    @method('DELETE')
                  </form>
                  <tr>
                    <td>ATC RN1</td>
                    <td>{{$distretto->nome}}</td>
                    <td>{{$distretto->getUnita()}}</td>
                    
                    @not_role_and(['cacciatore','admin_ro'])
                    <td> <a href="{{ route('distretti.edit',$distretto->id) }}" title="Modifica distretto" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
                    @endnot_role_and
                    
                    <td> <a href="{{ route('distretti.show',$distretto->id) }}" title="Visualizza distretto" class="btn btn-warning btn-sm"><i class="fa fa-map"></i> visualizza</a> </td>
                    
                    @not_role_and(['cacciatore','admin_ro'])
                    <td>
                      <button type="button" class="btn btn-danger btn-flat delete btn-sm" data-id="{{$distretto->id}}"><i class="fa fa-trash"></i> elimina</button>
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