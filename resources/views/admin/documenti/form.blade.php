@extends('layouts.app')



@section('header_css')
	<!-- Select2 -->
	<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection



@section('content')
	<div class="row">    
    <div class="col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Documenti</h3>
        </div>
        <!-- /.box-header -->
        @if ($doc->exists)
          
          <form action="{{ route('documenti.elimina', $doc->id) }}" method="POST" id="record_delete">
            {{ method_field('DELETE') }}
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{$doc->id}}">
          </form>
        
          <form role="form" action="{{ route('documenti.aggiorna', $doc->id) }}" method="POST">
        @else
          <form role="form" action="{{ route('documenti.upload') }}" method="POST" enctype="multipart/form-data">
        @endif
          {!! csrf_field() !!}
          <div class="box-body">
            
            @if (!$doc->exists)
              <div class="form-group">
                <label for="titolo">File</label>
                <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" required="required" aria-describedby="fileHelp">
                        <small id="fileHelp" class="form-text text-muted">Please upload a valid file. Size of file should not be more than 2MB.</small>
              </div>
            @endif
  
            <div class="form-group">
              <label for="squadre">Squadre:</label>
              <select multiple="multiple" name="squadre[]" id="squadre" class="form-control select2" data-placeholder="@if (count($squadre)) Seleziona le squadre @else Nessuna squadra disponibile @endif " style="width: 100%;">
              @foreach($squadre as $id => $nome)
                <option value="{{$id}}" 
                  @if ( 
                    (isset($squadre_associate) && array_key_exists($id, $squadre_associate)) || collect(old('squadre'))->contains($id) 
                    ) selected="selected" @endif
                  >{{$nome}}
                </option>
              @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="titolo">Titolo</label>
              <input type="titolo" class="form-control" name="titolo" id="titolo" placeholder="titolo" value="{{ old('titolo') != '' ?  old('titolo') : $doc->titolo}}" required="required">
            </div>

            <div class="form-group">
              <label for="argomento">Argomento</label>
              <input type="argomento" class="form-control" name="argomento" id="argomento" placeholder="argomento" value="{{ old('argomento') != '' ?  old('argomento') : $doc->argomento}}" required="required">
            </div>
            
            <div class="form-group">
              <label for="note">Note</label>
              <textarea class="form-control" rows="3" placeholder="note ..." name="note" id="note">@if(old('note') != ''){{ old('note') }}@else{{ $doc->note }}@endif</textarea>
            </div>
             
          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-success">
              @if ($doc->exists)
                Modifica
              @else
                Crea
              @endif
            </button>
            <a href="{{ route('documenti.index') }}" title="Annulla" class="btn btn-warning pull-right">Annulla</a>
          </div>
          </form>
        </div>
    </div>
  </div>
@endsection



@section('script_footer')
	<!-- Select2 -->
	<script src="{{ asset('js/select2.full.min.js') }}"></script>

	<script type="text/javascript">
				$(function () {
				    //Initialize Select2 Elements
				    $('.select2').select2();

				});
	</script>
@endsection