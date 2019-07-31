<div class="box-header with-border" style="display: flex">

<button id="salva_coordinate" title="Salva coordinate" class="btn btn-sm btn-success align-self-end">Salva coordinate</button>

<form id="new_center" style="display: none;">
	<button title="Salva coordinate" class="btn btn-sm btn-success align-self-end">Ricentra la mappa</button>
	<input type="hidden" id="lat" name="lat" value="">
	<input type="hidden" id="long" name="long" value="">
	<input type="hidden" id="zoom" name="zoom" value="">
</form>



@if (isset($spegni) && $spegni == 'utg' )
  
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" name="utg_check" id="utg_check" class="unita form-check-input" checked>
      Visibilità unità
    </label>
  </div>
      
@endif

@if (isset($spegni) && ($spegni == 'zone' || $spegni == 'utg'))
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" name="zone_check" id="zone_check" class="zona form-check-input" checked>
      Visibilità zone
    </label>
  </div>
  @endif


@if (isset($spegni) && $spegni == 'distretto')
  <button id="spegni_distretto" title="Spegni distretto" class="btn btn-sm bg-gray color-palette align-self-end">Spegni distretto</button>
  <button id="accendi_distretto" title="Accendi distretto" class="btn btn-sm btn-warning align-self-end">Accendi distretto</button>
@endif



@if (isset($principale) && $principale == 'distretto')
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" name="main_editable" id="main_editable" class="distretto form-check-input">
        Editabile
    </label>
  </div>
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" name="main_draggable" id="main_draggable" class="distretto form-check-input">
        Trascinabile
    </label>
  </div>
@endif




</div>	
<div id="map"></div>