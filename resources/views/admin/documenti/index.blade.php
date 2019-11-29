@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Elenco documenti</h3>
            <div class="box-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                  <a href="{{ route('documenti.form-upload') }}" title="Nuovo documento" class="btn btn-success"><i class="fa fa-plus"></i> Nuovo Documento</a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <thead>
                <tr>
                    @foreach ($columns as $field => $name)

                      @if (empty($field))

                        <th>
                          {!!$field!!}
                        </th>

                      @else

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
                            {{-- Se sono il id e non ho ordinamento , il default è per id desc, quindi metto ordinamento inverso --}}
                            {{-- altrimenti anche il id ha ordinamento asc --}}
                            @php
                                if ($field == 'id' && !app('request')->has('order_by'))
                                  {
                                  $new_order = 'asc';
                                  }
                                else
                                  {
                                  $new_order = 'desc';
                                  }
                                $link = "<a href='".url()->current()."?order_by=".$field."&order=$new_order'>".$name."</a>";
                                $class="sorting";
                            @endphp
                        @endif
                        <th class="{{$class}}">
                          {!!$link!!}
                        </th>

                      @endif

                    @endforeach
                    @isAdmin
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    @endisAdmin
                </tr>
            </thead>
            <tbody>   
              @foreach ($documenti as $documento)
              <form action="{{ route('documenti.elimina', $documento->id) }}" id="form_{{$documento->id}}" method="POST">
                {!! csrf_field() !!}
                @method('DELETE')
              </form>
              <tr>
                <td>
                    <a class="documento" href="{{asset('storage').'/'.$documento->file}}" title="Scarica documento" target="_blank">
                    {!!$documento->titolo!!}
                    </a>
                </td>
                <td>
                    {{$documento->argomento}}
                </td>
                <td>
                    {{$documento->tipo}}
                </td>
                @php
                  Carbon\Carbon::setLocale('it'); /* in un middleware every request!!*/
                @endphp
                <td>
                    {{ $documento->created_at->diffForHumans() }} 
                </td>
                @isAdmin
                <td>
                    <a class="documento" href="{{ route('documenti.modifica', $documento->id) }}" title="Modifica documento">
                      <button type="button" class="btn btn-primary btn-flat pull-right"><i class="fa fa-edit"></i></button>
                    </a>
                </td>
                <td>
                  <button type="button" class="btn btn-danger btn-flat delete_doc pull-right" data-doc-id="{{$documento->id}}"><i class="fa fa-trash"></i></button>
                </td>
                @endisAdmin
              </tr>
              @endforeach
            </tbody>
          </table>
          </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-5">
        <div aria-live="polite" class="dataTables_info" id="example2_info" role="status">
          Pagina {{$documenti->currentPage()}} di {{$documenti->lastPage()}}
        </div>
    </div>
    <div class="col-sm-7">
        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
        @if ($ordering)
                {{ $documenti->appends(['order_by' => $order_by, 'order' => $order])->links() }}
            @else
                {{ $documenti->links() }}
            @endif
        </div>
    </div>
  </div>
@endsection


@section('script_footer')
  	
@endsection