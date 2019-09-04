@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco squadre</h3>
            <div class="box-tools">
              @not_role_and(['cacciatore','admin_ro'])
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('squadre.create') }}" title="Nuova squadra" class="btn btn-success"><i class="fa fa-plus"></i> Nuova Squadra</a>
              </div>
              @endnot_role_and
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <colgroup>
                <col></col>
                <col class="success"></col>
                <col></col>
                <col></col>
                @not_role_and(['cacciatore','admin_ro'])
                <col></col>
                <col></col>
                @endnot_role_and
            </colgroup>
            <thead>
              <tr>
                <th @if ($order_by=='distretto')
                  class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                  @endif>
                    <a href="{{url()->current()}}?order_by=distretto&order={{ $order_by=='distretto' && $order=='asc' ? 'desc' : 'asc' }}">Distretto</a>
                </th>
                <th scope="col" @if ($order_by=='nome')
                  class="{{$order=='asc' ? 'sort_asc' : 'sort_desc' }}"
                  @endif>
                    <a href="{{url()->current()}}?order_by=nome&order={{ $order_by=='nome' && $order=='asc' ? 'desc' : 'asc' }}">Nome</a>
                </th>
                <th>Zone</th>
                <th>Cacciatori</th>
                @role('admin')<th>Caposquadra</th>@endrole
                @not_role_and(['cacciatore','admin_ro'])
                <th></th>
                <th></th>
                @endnot_role_and
              </tr>
            </thead>
            <tbody>
              @foreach ($squadre as $squadra)
                <form action="{{ route('squadre.destroy', $squadra->id) }}" id="form_{{$squadra->id}}" method="POST">
                    {!! csrf_field() !!}
                    @method('DELETE')
                  </form>
                <tr>
                  <td>{{optional($squadra->distretto)->nome}}</td>
                  <td>{{$squadra->nome}}</td>
                  <td>{{$squadra->getZone()}}</td>          
                  <td>{{$squadra->getCacciatori()}}</td>

                  @role('admin')
                  <td>
                    <select class="form-control capoSquadra" name="capoSquadra" data-id_squadra="{{$squadra->id}}">
                      @foreach ([0 => 'Seleziona caposquadra...'] + $squadra->getCacciatoriSelect() as $id => $nome)
                        <option 
                        value="{{$id}}"
                        @if (optional($squadra->getCapoSquadra())->id == $id)
                            selected="selected"
                        @endif
                        >{{$nome}}</option>
                      @endforeach
                    </select>
                  </div>  
                  </td>
                  @endrole
                  
                  @not_role_and(['cacciatore','admin_ro'])
                  <td> <a href="{{ route('squadre.edit',$squadra->id) }}" title="Modifica squadra" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
                  <td>
                    <button type="button" class="btn btn-danger btn-flat delete btn-sm" data-id="{{$squadra->id}}"><i class="fa fa-trash"></i> elimina</button>
                  </td>
                  @endnot_role_and
                </tr>
              @endforeach
            </tbody>
          </table>
          </div>
      </div>
    </div>
  </div>
@endsection


@section('script_footer')
  	<script type="text/javascript">

      function assegnaCapoSquadra(val, id_squadra) {
					var cacciatore_id = val;
					var squadra_id = id_squadra;
					jQuery.ajax({
					        url: '{{ route('assegna_capo_squadra') }}',
					        type: "post",
					        async: false,
					        data : { 
					               'cacciatore_id': cacciatore_id, 
                         'squadra_id' :squadra_id,
					               '_token': jQuery('input[name=_token]').val()
					               },
					       	success: function(data) {
                      $('#msg_jquery').html(data).fadeIn('slow');
                      $('#msg_jquery').delay(3000).fadeOut('slow');
					       }
					 });

				}


      $(function () {
				    $('.capoSquadra').change(function(){
              id_squadra = $(this).attr("data-id_squadra");
				    	assegnaCapoSquadra(this.value, id_squadra);
				    });
				});
    </script>  
@endsection