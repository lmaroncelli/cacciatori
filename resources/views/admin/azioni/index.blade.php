@extends('layouts.app')


@section('header_css')
   

    {{-- bootstrap toogle button --}}
	  <link href="{{ asset('css/bootstrap-toggle.min.css') }}" rel="stylesheet">
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
                  <label for="azioni">Squadra:</label>
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
                  <label for="zone">Quadrante:</label>
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
                  <label class="checkbox-inline">
							      <input type="checkbox" id="trashed" name="trashed" value="1" @if ($trashed) checked @endif data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-width="150" data-height="25" data-off="NON Eliminate" data-on="ANCHE ELIMINATE"> <b>AZIONI</b>
							    </label>
                </div>

                <div class="form-group align-dx">
                  <button type="submit" class="btn btn-success">Filtra</button>
                  <a href="{{ route('reset') }}" class="btn btn-warning">Reset</a>
                  <a href="{{$pdf_export_url}}" title="Esporta" target="_blank" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></a>
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
             @not_role_and(['admin_ro','consultatore'])
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('azioni.create') }}" title="Nuovo distretto" class="btn btn-success"><i class="fa fa-plus"></i> Nuova attività</a>
              </div>
              @endnot_role_and
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
            {{-- ATTENZIONE: se lo metto dentro la tabella il dataTables mi toglie i form  --}}
            @foreach ($azioni as $azione)
              <form action="{{ route('azioni.destroy', $azione->id) }}" id="form_{{$azione->id}}" method="POST">
                {!! csrf_field() !!}
                @method('DELETE')
              </form>
            @endforeach
            <table class="table table-hover" id="tbl_azioni">
              <thead>
                <tr>
                  @foreach ($columns as $field => $name)

                    @if (empty($field))
                        <th>{{$name}}</th>
                    @else
                        {{-- se sono il campo per cui è ordinato il listing --}}
                        @if (app('request')->has('order_by') && app('request')->get('order_by') == $field)
                            @php
                                if(app('request')->get('order') == 'desc')
                                  {
                                  $new_order = 'asc';
                                  $class = "sort_desc";
                                  }
                                else
                                  {
                                  $new_order = 'desc';
                                  $class = "sort_asc";
                                  }

                                $link = "<a href='".url()->current()."?order_by=".$field."&order=".$new_order."'>".$name."</a>";
                            @endphp
                        @else
                            {{-- altrimenti ordinamento asc --}}
                            @php
                                $new_order = 'asc';
                                $link = "<a href='".url()->current()."?order_by=".$field."&order=$new_order'>".$name."</a>";
                                $class="";
                            @endphp
                        @endif
                        <th class="{{$class}}">
                          {!!$link!!}
                        </th>
                    @endif
                  @endforeach
                  <th></th>
                  @not_role_and(['admin_ro','consultatore'])
                  <th></th>
                  <th></th>
                  @endnot_role_and
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($azioni as $azione)
                  <tr @if ($azione->trashed()) class="trashed"@endif>
                    <td>{{$azione->getDalleAlle()}}</td>
                    <td>{{optional($azione->squadra)->nome}}</td>
                    <td>{{optional($azione->distretto)->nome}}</td>
                    <td>{{$azione->getZone()}}</td>
                    <td> <a href="{{ route('azioni.show',$azione->id) }}" title="Visualizza azione" class="btn btn-warning btn-sm"><i class="fa fa-map"></i> visualizza</a> </td>
                    @not_role_and(['admin_ro','consultatore'])
                    <td> <a href="{{ route('azioni.edit',$azione->id) }}" title="Modifica azione" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>modifica</a> </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-flat delete pull-right btn-sm" data-id="{{$azione->id}}"><i class="fa fa-trash"></i> elimina</button>
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
</div>
@endsection


@section('script_footer')


    {{-- bootstrap toogle button --}}
    <script src="{{ asset('js/bootstrap-toggle.min.js') }}"></script>

    {{-- Date range picker --}}
    <script src="{{ asset('js/moment.min.js') }}">
    </script>
    <script src="{{ asset('js/daterangepicker.js') }}">
    </script>
    <script type="text/javascript">
        $(function () {

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

          $('#trashed').change(function() {
            var checked = $(this).prop('checked');
            //console.log('checked = '+checked)
            if(!checked)
              {
              // chiamata per rimuovere tutti i filtri
              window.location.href = "{{ route('reset') }}";
              }
          })


    	});
    </script>
    @endsection