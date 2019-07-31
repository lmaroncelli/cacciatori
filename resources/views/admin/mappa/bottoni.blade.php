<div class="box-header with-border" style="display: flex">

<button id="salva_coordinate" title="Salva coordinate" class="btn btn-sm btn-success align-self-end">Salva coordinate</button>

<form id="new_center" style="display: none;">
	<button title="Salva coordinate" class="btn btn-sm btn-success align-self-end">Ricentra la mappa</button>
	<input type="hidden" id="lat" name="lat" value="">
	<input type="hidden" id="long" name="long" value="">
	<input type="hidden" id="zoom" name="zoom" value="">
</form>



@if (isset($spegni) && $spegni == 'utg' )
  <button id="spegni_utg" title="Spegni utg" class="btn btn-sm bg-gray color-palette align-self-end">Spegni utg</button>
  <button id="accendi_utg" title="Accendi utg" class="btn btn-sm btn-warning align-self-end">Accendi utg</button>
@endif

@if (isset($spegni) && ($spegni == 'zone' || $spegni == 'utg'))
  <button id="spegni_zone" title="Spegni zone" class="btn btn-sm bg-gray color-palette align-self-end">Spegni zone</button>
  <button id="accendi_zone" title="Accendi zone" class="btn btn-sm btn-warning align-self-end">Accendi zone</button>
@endif


@if (isset($spegni) && $spegni == 'distretto')
  <button id="spegni_distretto" title="Spegni distretto" class="btn btn-sm bg-gray color-palette align-self-end">Spegni distretto</button>
  <button id="accendi_distretto" title="Accendi distretto" class="btn btn-sm btn-warning align-self-end">Accendi distretto</button>
@endif




</div>	
<div id="map"></div>