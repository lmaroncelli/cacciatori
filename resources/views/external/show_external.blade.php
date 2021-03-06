@extends('layouts.app_external')

@section('header_css')

	<!-- bootstrap datepicker -->
	<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
	
@endsection


@section('content')
	<div id="content">
		<div class="row">    
      <div class="col-xs-12">
        <div class="box box-success">
          @csrf
          @include('external.calendar_azioni')
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

     var azioni_di_zona = JSON.parse('{!! json_encode($azioni_di_zona) !!}');
     var nomi_di_zona = JSON.parse('{!! json_encode($nomi_di_zona) !!}');
     var nomi_ug_di_zona = JSON.parse('{!! json_encode($nomi_ug_di_zona) !!}');
    

				// Initialize and add the map
				function initMap() {

		 			var center_lat = {{$item->center_lat}};
		 			var center_long = {{$item->center_long}};
           var zoom = {{$item->zoom}};
           

           var distretto_coords = new Array();
          var utg_coords = new Array();
          var zona_coords = new Array();

		 		  // The location of center
		 		  var center = {lat: center_lat, lng: center_long};

          // The map
				  map = new google.maps.Map(
				      document.getElementById('map'), {zoom: zoom, center: center});


          
          // Create the bounds object
          var bounds = new google.maps.LatLngBounds();
          
        
        // per evitare sovrapposizione nei poligono disegno PRIMA i DISTRETTI
        @if (true )
          

          @foreach ($distretto_coo as $id_distretto => $coordinate_distretto)
            
          
                var distretto_coords = new Array();


                @foreach ($coordinate_distretto as $lat => $long)
                  
                  @if(!empty($lat) && !empty($long))

                      var jsonData = {};
                      jsonData['lat'] = {{$lat}};
                      jsonData['lng'] = {{$long}};
                      
                      distretto_coords.push(jsonData);

                  @endif

                @endforeach
                
                

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
                        showInfo(event,'distretto',"{{$nomi_distretto[$id_distretto]}}");
                      });

                  }


          @endforeach // end $distretto_coo

        @endif  
            
   
   


        // Per evitare sovrapposizione dei poligoni
        // disegno cmq PRIMA le UG

         @if (true)
          
          // per ogni zona costruisco le UG
          @foreach ($unita_coo as $id_utg => $coordinata_unita)

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
              utg_{{$id_utg}} = new google.maps.Polygon({
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
              utg_{{$id_utg}}.setMap(map);


              google.maps.event.addListener(utg_{{$id_utg}}, 'click', function(event){
                showInfo(event,'unità',"{{$nomi_unita[$id_utg]}}");
              });

          @endforeach // end $unita_coo

        @endif
      

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


          // Add a listener for the click event.
          zona.addListener('click', function(event) {
            showAzioni(event, {{$zona_id}});
          });

          infoWindow = new google.maps.InfoWindow;
          

        @endforeach //end $coordinate_zona
				
        if(distretto_coords.length !== 0) 
          {
          map.fitBounds(bounds);
          }

       

				} // initMap


        function showAzioni(event, zona_id)
          {
          //console.log(event);
          //console.log('zona_id = '+zona_id);
          
          var contentString = "Elenco azioni quadrante <b>" + nomi_di_zona[zona_id] + "(unità: "+ nomi_ug_di_zona[zona_id] +")</b>:<br/><br/>"
          for (let index = 0; index < azioni_di_zona[zona_id].length; index++) {
            const azione = azioni_di_zona[zona_id][index];
            //console.log('azione = '+azione.dalle);
            contentString += azione.dalle_alle + ': <ul>'
            + '<li>Squadra: ' + azione.nomesquadra + '</li>'
            + '</ul>'
          }
         

          // Replace the info window's content and position.
		      infoWindow.setContent(contentString);
		      infoWindow.setPosition(event.latLng);

		      infoWindow.open(map);

          }


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
          
          var url = '{{ route('pagina_esterna') }}'+'?data='+data

          document.location.href=url;
        
        }//if 
      
      });

	</script>

@endsection