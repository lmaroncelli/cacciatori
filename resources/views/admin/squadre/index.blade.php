@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco squadre</h3>

            <div class="box-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('squadre.create') }}" title="Nuova squadra" class="btn btn-success"><i class="fa fa-plus"></i> Nuova Squadra</a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <colgroup>
                <col></col>
                <col class="success"></col>
                <col></col>
                <col></col>
            </colgroup>
            <thead>
              <tr>
                <th>Distretto</th>
                <th scope="col">Nome</th>
                <th>Zone</th>
                <th>Cacciatori</th>
                <th></th>
                <th></th>
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
                  <td> <a href="{{ route('squadre.edit',$squadra->id) }}" title="Modifica squadra" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> modifica</a> </td>
                  <td>
                    <button type="button" class="btn btn-danger btn-flat delete btn-sm" data-id="{{$squadra->id}}"><i class="fa fa-trash"></i> elimina</button>
                  </td>
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