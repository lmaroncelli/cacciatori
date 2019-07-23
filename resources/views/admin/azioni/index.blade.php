@extends('layouts.app')


@section('header_css')
    <!-- DataTables -->
    <link href="{{ asset('css/dataTables.bootstrap.min.css') }}" rel="stylesheet">

@endsection

@section('content')
{{-- FILTRI DI RICERCA --}}
<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Filtri di ricerca</h3>
          </div>
          <form action="{{ route('azioni_search') }}" method="post" id="filtra_azioni">
            @csrf
            <div class="box-body">
                <div class="form-group">
                 <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                  <input type="text" name="datefilter" value="{{$init_value}}" class="form-control pull-right">
                  </div>
                  <!-- /.input group -->
                </div>

                <div class="form-group">
                  <label for="squadre">Squadra:</label>
                  <select name="squadra" id="squadra" class="form-control">
                    @foreach(['0' => 'Selziona...'] + $squadre as $id => $nome)
                      <option value="{{$id}}" 
                      @if ($id == $squadra_selected)
                          selected="selected"
                      @endif
                      >{{$nome}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="zone">Zona:</label>
                  <select name="zona" id="zona" class="form-control">
                    @foreach(['0' => 'Selziona...'] + $zone as $id => $nome)
                      <option value="{{$id}}" 
                      @if ($id == $zona_selected)
                          selected="selected"
                      @endif
                      >{{$nome}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-success">Filtra</button>
                  <a href="{{ route('reset') }}" class="btn btn-warning">Reset</a>
                </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco attività</h3>

            <div class="box-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('azioni.create') }}" title="Nuovo distretto" class="btn btn-success"><i class="fa fa-plus"></i> Nuova attività</a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover" id="tbl_azioni">
              <thead>
                <tr>
                  @foreach ($columns as $field => $name)
                  
                    {{-- se sono il campo per cui è ordinato il listing --}}
                    @if (app('request')->has('order_by') && app('request')->get('order_by') == $field)
                        @php
                            if(app('request')->get('order') == 'desc')
                              {
                              $new_order = 'asc';
                              $class = "sorting_desc";
                              }
                            else
                              {
                              $new_order = 'desc';
                              $class = "sorting_asc";
                              }

                            $link = "<a href='".url()->current()."?order_by=".$field."&order=".$new_order."'>".$name."</a>";
                        @endphp
                    @else
                        {{-- altrimenti ordinamento asc --}}
                        @php
                            $new_order = 'asc';
                            $link = "<a href='".url()->current()."?order_by=".$field."&order=$new_order'>".$name."</a>";
                            $class="sorting";
                        @endphp
                    @endif
                    <th class="{{$class}}">
                      {!!$link!!}
                    </th>
                  @endforeach

                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($azioni as $azione)
                  <form action="{{ route('azioni.destroy', $azione->id) }}" id="form_{{$azione->id}}" method="POST" id="record_delete">
                    {!! csrf_field() !!}
                    @method('DELETE')
                  </form>
                  <tr>
                    <td>{{$azione->getDalleAlle()}}</td>
                    <td>{{$azione->squadra->nome}}</td>
                    <td>{{$azione->distretto->nome}}</td>
                    <td>{{$azione->unita->nome}}</td>
                    <td>{{$azione->zona->nome}}</td>
                    <td> <a href="{{ route('azioni.edit',$azione->id) }}" title="Modifica azione" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>modifica</a> </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-flat delete pull-right btn-sm" data-id="{{$azione->id}}"><i class="fa fa-trash"></i> elimina</button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection


@section('script_footer')
    <!-- DataTables -->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}">
    </script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}">
    </script>

    {{-- Date range picker --}}
    <script src="{{ asset('js/moment.min.js') }}">
    </script>
    <script src="{{ asset('js/daterangepicker.js') }}">
    </script>
    <script type="text/javascript">
        $(function () {

    	    $('#tbl_azioni').DataTable({
              'paging'      : false,
              'lengthChange': false,
              'searching'   : false,
              'ordering'    : false,
              'info'        : false,
              'autoWidth'   : true
            });


          //Date range picker
          $('input[name="datefilter"]').daterangepicker({
              autoUpdateInput: false,
              format: 'DD/MM/YYYY',
              startOfWeek: 'monday',
              language:'it',
              opens:'right',
              drops:'down',
              locale: {
                  cancelLabel: 'Clear',
                  "format": "DD/MM/YYYY",
              }
          });

          $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
              var dal = picker.startDate.format('DD/MM/YYYY');
              var al = picker.endDate.format('DD/MM/YYYY');
              $(this).val(dal + ' - ' + al);
          });

          $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
              $(this).val('');
          });


    	});
    </script>
    @endsection