@extends('layouts.app_pdf')

@section('content')
  
@if (!$azioni->count())
    <div class="callout callout-info">
        <h4>
            Nessuna azione presente!
        </h4>
    </div>
    @else
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
    @foreach ($azioni->chunk($chunked_element) as $key => $chunk)
    <div id="pdf_filter_container">
       <div id="pdf_filter">
       {!! implode('<br />', $filtro_pdf) !!}
       </div>
       <div id="pdf_logo">
          <img src="{{ base_path('public/images/provincia-rimini.jpg') }}" alt="Provincia di Rimini">
       </div>
    </div>
    <div class="clear border"></div>
    <div class="row">
        <p class="page_number">Pagina {{$key+1}} di {{ count($azioni->chunk($chunked_element)) }}</p>
        <div class="col-xs-12">
            <div class="box-body">
                <table cellpadding="10" cellspacing="0" id="tbl_azioni">
                    <thead>
                        <tr>
                            @foreach ($columns as $field => $name)
                                <th>
                                  {!!$name!!}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($chunk as $azione)
                        <tr>
                          <td>{{$azione->getDalleAlle()}}</td>
                          <td>{{$azione->squadra->nome}}</td>
                          <td>{{$azione->distretto->nome}}</td>
                          <td>{{$azione->unita->nome}}</td>
                          <td>{{$azione->zona->nome}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
        		</div>
        </div>
    </div>
    @if ($key+1 < count($azioni->chunk($chunked_element)))
     <div class="row">
          <div class="col-xs-12">
            <div class="page-break"></div>
          </div>
      </div>
    @endif
    @endforeach
    </div>
    @endif
    
@endsection
