@extends('layouts.app')

@section('header_css')

	<!-- bootstrap datepicker -->
	<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
	
@endsection

@section('titolo')
@if ($zone_count == 1)
  Azione sul quadrante: {{$azione->getZone()}}    
@else  
  Azione su {{$zone_count}} quadranti: {{$azione->getZone()}}
@endif
@endsection

@section('info-azione')
  Il <b>{{$azione->getDalleAlle()}}</b> ad opera della Squadra <b>{{$azione->squadra->nome}}</b>
  <ul>
    @if (!is_null($azione->note))
      <li>Nota: {{$azione->note}}</li>
    @endif
    <li>Caposquadra: {{$azione->squadra->getNomeCapoSquadra()}}</li>
    <li>Tel: {{optional($azione->squadra->getCapoSquadra())->telefono}}</li>
  </ul>
     
@endsection


@section('content')
	<div id="content">
		<div class="row">    
      <div class="col-xs-12">
        <div class="box box-success">
          @csrf
          <div id="map"></div>
        </div>
      </div>
		</div>
	</div>	
	
@endsection


@section('script_footer')

  <!-- bootstrap datepicker -->
	<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-datepicker.it.js') }}"></script>

	<script type="text/javascript">

		var map;
		var contentString;
    var infoWindow;

				// Initialize and add the map
				function initMap() {

		 			var center_lat = {{$item->center_lat}};
		 			var center_long = {{$item->center_long}};
		 			var zoom = {{$item->zoom}};

		 		  // The location of center
		 		  var center = {lat: center_lat, lng: center_long};

          // The map
				  map = new google.maps.Map(
				      document.getElementById('map'), {zoom: zoom, center: center});

          // Create the bounds object
          var bounds = new google.maps.LatLngBounds();
        
        // per evitare sovrapposizione nei poligono disegno PRIMA i DISTRETTI
        @foreach ($coordinate_zona as $zona_id => $coordinata_zona)
          
          @if(isset($coordinate_unita[$zona_id]))
            
            @foreach ($coordinate_unita[$zona_id] as $id_utg => $coordinata_unita)
              
            
                  var distretto_coords = new Array();

                  @if (!is_null($coordinate_distretto[$id_utg]))

                    @foreach ($coordinate_distretto[$id_utg] as $lat => $long)
                      
                      @if(!empty($lat) && !empty($long))

                          var jsonData = {};
                          jsonData['lat'] = {{$lat}};
                          jsonData['lng'] = {{$long}};
                          
                          //console.log('jsonData = '+JSON.stringify(jsonData));

                          distretto_coords.push(jsonData);

                      @endif

                    @endforeach
                  
                  @endif

                  if(distretto_coords.length !== 0) 
                    {

                        var distretto = new google.maps.Polygon({
                          paths: distretto_coords,
                          strokeColor: '{{$colors["distretto"]}}',
                          strokeOpacity: 0.8,
                          strokeWeight: 2,
                          fillColor: '{{$colors["distretto"]}}',
                          fillOpacity: 0.35,
                          editable: false,
                          draggable: false
                        });


                        distretto.setMap(map);

                        distretto.getPath().forEach(function (path, index) {
                            bounds.extend(path);
                        });

                        google.maps.event.addListener(distretto, 'click', function(event){
                          showInfo(event,'distretto',"{{$nomi_distretto[$id_utg]}}");
                        });

                    }


            @endforeach // end $coordinate_unita[$zona_id]
          
          @endif
      
        @endforeach //end $coordinate_zona



        // Per evitare sovrapposizione dei poligoni
        // disegno cmq PRIMA le UG

        // per ogni zona costruisco le UG (se esiste)
        @foreach ($coordinate_zona as $zona_id => $coordinata_zona)
          
          @if(isset($coordinate_unita[$zona_id]))
            
            // per ogni zona costruisco le UG
            @foreach ($coordinate_unita[$zona_id] as $id_utg => $coordinata_unita)

                var utg_coords = new Array();

                @foreach ($coordinata_unita as $lat => $long)


                  @if(!empty($lat) && !empty($long))

                      var jsonData = {};
                      jsonData['lat'] = {{$lat}};
                      jsonData['lng'] = {{$long}};
                      
                      //console.log('jsonData = '+JSON.stringify(jsonData));

                      utg_coords.push(jsonData);

                  @endif
                
                @endforeach // end coordinata_unita

                // Construct the polygon.
                utg_{{$zona_id}} = new google.maps.Polygon({
                  paths: utg_coords,
                  strokeColor: '{{$colors["utg"]}}',
                  strokeOpacity: 0.8,
                  strokeWeight: 2,
                  fillColor: '{{$colors["utg"]}}',
                  fillOpacity: 0.35,
                  editable: false,
                  draggable: false
                });


                //To add a layer to a map, you only need to call setMap(), passing it the map object on which to display the layer. 
                utg_{{$zona_id}}.setMap(map);


                google.maps.event.addListener(utg_{{$zona_id}}, 'click', function(event){
                  showInfo(event,'unità',"{{$nomi_unita[$zona_id][$id_utg]}}");
                });

            @endforeach // end $coordinate_unita[$zona_id]
          @endif
        
        @endforeach //end $coordinate_zona


        // creo tutte le zone delle quali ho le coordinate nell'array coordinate_zona
        @foreach ($coordinate_zona as $zona_id => $coordinata_zona)
        
          var zona_coords = new Array();

          @foreach ($coordinata_zona as $lat => $long)


            @if(!empty($lat) && !empty($long))

                var jsonData = {};
                jsonData['lat'] = {{$lat}};
                jsonData['lng'] = {{$long}};
                
                //console.log('jsonData = '+JSON.stringify(jsonData));

                zona_coords.push(jsonData);
            
            @endif
          
          @endforeach

          // Construct the polygon.
          var zona = new google.maps.Polygon({
            paths: zona_coords,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            editable: false,
            draggable: false
          });


          //To add a layer to a map, you only need to call setMap(), passing it the map object on which to display the layer. 
          zona.setMap(map);

          google.maps.event.addListener(zona, 'click', function(event){
            showInfo(event,'zona',"{{$nomi_zona[$zona_id]}}");
          });

          infoWindow = new google.maps.InfoWindow;
          

        @endforeach //end $coordinate_zona
				
        if(distretto_coords.length !== 0)
          {
            map.fitBounds(bounds);
          }

				} // initMap

        function showInfo(event, type, nome)
          {
            // Replace the info window's content and position.
          infoWindow.setContent(type + ' <b>'+ nome + '</b>');
          infoWindow.setPosition(event.latLng);
          infoWindow.open(map);
          }
	</script>
	
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrH8m8vUnPJQKt8zDTokE7Fg-kSGuL0mY&callback=initMap" type="text/javascript"></script>
	


	<script type="text/javascript">
		
    var _jsonObjDate = {language: "it", format: 'dd/mm/yyyy', autoclose: true, todayBtn:true, todayHighlight: true};
		$("#datepicker")
    .datepicker(_jsonObjDate)
    .on('hide', function(ev) {
        if(ev != "undefined") {
          //var data =  ev.date.toLocaleDateString("it-IT");
          var data =  ev.date.getFullYear() + "-" + (ev.date.getMonth() + 1) + "-" + ev.date.getDate()
          console.log(data);
          
          var url = '{{ route('home') }}'+'?data='+data

          document.location.href=url;
        
        }//if 
      
      });

	</script>

@endsection