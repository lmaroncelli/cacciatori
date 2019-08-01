<div class="box-header with-border" style="display: flex">

<button id="salva_coordinate" title="Salva coordinate" class="btn btn-sm btn-success align-self-end">Salva coordinate</button>

<form id="new_center" style="display: none;">
	<button title="Salva coordinate" class="btn btn-sm btn-success align-self-end">Ricentra la mappa</button>
	<input type="hidden" id="lat" name="lat" value="">
	<input type="hidden" id="long" name="long" value="">
	<input type="hidden" id="zoom" name="zoom" value="">
</form>




@if (isset($principale) && $principale != 'distretto')
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" name="distretto_check" id="distretto_check" class="form-check-input" checked>
      <a href="#" data-toggle="tooltip" title="Accendi o spegni il distretto disegnato sulla mappa" class="unita">Visibilità distretto</a>
    </label>
  </div>
@endif

@if (isset($principale) && $principale != 'utg')
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" name="utg_check" id="utg_check" class="form-check-input" checked>
      <a href="#" data-toggle="tooltip" title="Accendi o spegni le unità gestione disegnate sulla mappa" class="unita">Visibilità unità</a>
    </label>
  </div>
@endif

@if (isset($principale) && $principale != 'zone')
<div class="form-check">
  <label class="form-check-label">
    <input type="checkbox" name="zone_check" id="zone_check" class="form-check-input" checked>
    <a href="#" data-toggle="tooltip" title="Accendi o spegni le zone disegnate sulla mappa" class="zona">Visibilità zone</a>
  </label>
</div>
@endif






@if (isset($principale))
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" name="main_editable" id="main_editable" class="{{$principale}} form-check-input">
        <a href="#" data-toggle="tooltip" title="Il poligno disegnato sulla mappa diventa editabile e si può modificare trascinando i vertici e creando nuovi punti sul perimetro. Ricorda sempre di cliccare il bottone 'Salva coordinate' !">Editabile</a>
    </label>
  </div>
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" name="main_draggable" id="main_draggable" class="{{$principale}} form-check-input">
        <a href="#" data-toggle="tooltip" title="Il poligno disegnato sulla mappa diventa trascinabile tenendo premuto il pulsante sinistro del mouse. Ricorda sempre di cliccare il bottone 'Salva coordinate' !">Trascinabile</a>
    </label>
  </div>
@endif




</div>	
<div id="map"></div>