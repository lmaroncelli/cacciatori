@extends('layouts.app')

@section('titolo')
{{$distretto->nome}}
@endsection

@section('titolo_small')
Distretto
@endsection


@section('back')
<a href="{{ route('distretti.index') }}"><i class="fa fa-step-backward"></i> back </a>
@endsection

@section('content')
	<div id="content">
		<div class="row">    
      <div class="col-xs-12">
        <div class="box box-success">
          @csrf

          @include('admin.mappa.bottoni', ['principale' => 'distretto'])
        </div>
      </div>
		</div>
	</div>	
	
	@php
		$item = $distretto
	@endphp
@endsection


@section('script_footer')
  
<script src="{{ asset('js/icheck.js') }}"></script>

	<script type="text/javascript">
		var infoWindow;
		var map;
		var contentString;

    var distretto;

    var zone_ids = new Array();
    var utg_ids = new Array();


    @foreach ($coordinate_zona as $id_zona => $coordinata_zona)
      var zona_{{$id_zona}};
      zone_ids.push({{$id_zona}});
    @endforeach


    @foreach ($coordinate_utg as $id_utg => $coordinata_utg)
      var utg_{{$id_utg}};
      utg_ids.push({{$id_utg}});
    @endforeach


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

        
        // creo tutte le zone delle quali ho le coordinate nell'array coordinate_zona
        @foreach ($coordinate_zona as $id_zona => $coordinata_zona)
          
          var zona_coords = new Array();

          @foreach ($coordinata_zona as $lat => $long)


        
            var jsonData = {};
            jsonData['lat'] = {{$lat}};
            jsonData['lng'] = {{$long}};
            
            //console.log('jsonData = '+JSON.stringify(jsonData));

            zona_coords.push(jsonData);
          
          @endforeach

          // Construct the polygon.
          zona_{{$id_zona}} = new google.maps.Polygon({
            paths: zona_coords,
            strokeColor: '{{$colors["zona"]}}',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '{{$colors["zona"]}}',
            fillOpacity: 0.35,
            editable: false,
            draggable: false
          });


          //To add a layer to a map, you only need to call setMap(), passing it the map object on which to display the layer. 
          zona_{{$id_zona}}.setMap(map);


          google.maps.event.addListener(zona_{{$id_zona}}, 'click', function(event){
            showInfo(event,'zona',"{{$nomi_zona[$id_zona]}}");
          });


        @endforeach
        ////////////////////////////////////////////////////////////////
        
        // creo tutte le utg delle quali ho le coordinate nell'array coordinate_utg
        @foreach ($coordinate_utg as $id_utg => $coordinata_utg)
          
          var utg_coords = new Array();

          @foreach ($coordinata_utg as $lat => $long)


        
            var jsonData = {};
            jsonData['lat'] = {{$lat}};
            jsonData['lng'] = {{$long}};
            
            //console.log('jsonData = '+JSON.stringify(jsonData));

            utg_coords.push(jsonData);
          
          @endforeach

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
            showInfo(event,'unità',"{{$nomi_utg[$id_utg]}}");
          });


        @endforeach




      
        ///////////////////////////////////////////////////////////////
		    
		    var distretto_coords = new Array();

		    
		   	@foreach ($coordinate as $lat => $long)
		   		
		    	var jsonData = {};
		   		jsonData['lat'] = {{$lat}};
		   		jsonData['lng'] = {{$long}};
		   		
		   		//console.log('jsonData = '+JSON.stringify(jsonData));

		   		distretto_coords.push(jsonData);

		   	@endforeach

		   		//console.log(distretto_coords);
		    	//console.log(distretto_coords);

				  

				  
				  // Construct the polygon.
		      distretto = new google.maps.Polygon({
		        paths: distretto_coords,
		        strokeColor: '{{$colors["distretto"]}}',
		        strokeOpacity: 0.8,
		        strokeWeight: 2,
		        fillColor: '{{$colors["distretto"]}}',
		        fillOpacity: 0.35,
		        editable: false,
		        draggable: false
		      });


		      //To add a layer to a map, you only need to call setMap(), passing it the map object on which to display the layer. 
		      distretto.setMap(map);

		      // Add a listener for the click event.
		      distretto.addListener('click', showArrays);

		     	infoWindow = new google.maps.InfoWindow;



     	    $('#salva_coordinate').click(function(){

     	    	var vertices = distretto.getPath();
     				console.log(vertices);

     				var distretto_coords = new Array();

     				// Iterate over the vertices.
     				for (var i =0; i < vertices.getLength(); i++) {
     				  var xy = vertices.getAt(i);

     				   	var jsonData = {};
	 				  		jsonData['lat'] = xy.lat();
	 				  		jsonData['long'] = xy.lng();

	 				  		distretto_coords.push(jsonData);

     				}

     				console.log(distretto_coords);

     	    	jQuery.ajax({
     	    	        url: '{{ route("aggiorna_coordinate") }}',
     	    	        type: "post",
     	    	        async: false,
     	    	        data : { 
     	    	               'coords': distretto_coords, 
     	    	               'distretto_id': '{{$item->id}}',
     	    	               '_token': jQuery('input[name=_token]').val()
     	    	               },
     	    	       	success: function(data) {
     	    	       
     	    	       }

     	    	 }); // ajax //

     	    }); // clcik //

				} // initMap



        function spegni_distretto() {
            distretto.setMap(null);  
        }


        function accendi_distretto() {
          distretto.setMap(map);
        }


        function spegni_utg() {
          utg_ids.forEach(function(id){
             eval('utg_'.concat(id)).setMap(null);  
          })
        }

        function accendi_utg() {
          utg_ids.forEach(function(id){
             eval('utg_'.concat(id)).setMap(map);  
          })
        }

        function spegni_zone() {
          zone_ids.forEach(function(id){
             eval('zona_'.concat(id)).setMap(null);  
          })
        }


        function accendi_zone() {
          zone_ids.forEach(function(id){
             eval('zona_'.concat(id)).setMap(map);  
          })
        }


        

        


        function toggleVisibility(id, nome) {
           if ($('#'+id).is(':checked')) {
            eval('accendi_'+ nome + '()');
           }
           else {
            eval('spegni_'+ nome + '()');
           }
        }




        function showInfo(event, type, nome)
          {
            // Replace the info window's content and position.
          infoWindow.setContent(type + ' <b>'+ nome + '</b>');
		      infoWindow.setPosition(event.latLng);
		      infoWindow.open(map);
          }


				/** @this {google.maps.Polygon} */
		    function showArrays(event) {


		    	$("#new_center").show();

		    	
		      // Since this polygon has only one path, we can call getPath() to return the
		      // MVCArray of LatLngs.
		      var vertices = this.getPath();

		      var nome = '{{$item->nome}}';

		      var c_lat = event.latLng.lat();
		      var c_long = event.latLng.lng();
		      var c_zoom = map.getZoom();



		      $("#lat").val(c_lat);
		      $("#long").val(c_long);
		      $("#zoom").val(c_zoom);
		    	

		     contentString = '<b>'+nome+'</b><br><br>' +
		     		      'Coordinate del centro correnti: <br>' + c_lat + ',' + c_long +
		     		      '<br><br>'+
		     		      'Zoom corrente: '+ c_zoom + '<br><br>';


          if (false) {
             // Iterate over the vertices.
            for (var i =0; i < vertices.getLength(); i++) {
		          var xy = vertices.getAt(i);
		          contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
		            xy.lng();
		        }
          }
		     
          // Replace the info window's content and position.
          infoWindow.setContent(contentString);
		      infoWindow.setPosition(event.latLng);
		      infoWindow.open(map);


		      google.maps.event.addListener(infoWindow, 'closeclick', function() {  
		          $("#new_center").hide();
		      });  


		    } // showArrays


		    $('#new_center').click(function(e){
		    		e.preventDefault();

		    		var c_lat = $("#lat").val();
		    		var c_long = $("#long").val();
		    		var c_zoom = $("#zoom").val();

		    		jQuery.ajax({
		    		        url: '{{ route("aggiorna_centro") }}',
		    		        type: "post",
		    		        async: false,
		    		        data : { 
		    		               'lat': c_lat, 
		    		               'long': c_long, 
		    		               'zoom': c_zoom, 
		    		               'distretto_id': '{{$item->id}}',
		    		               '_token': jQuery('input[name=_token]').val()
		    		               },
		    		       	success: function(data) {
		    		       		location.reload();
		    		       	}

		    		 }) // ajax //

		    }); // click new_center


         $("#distretto_check").click(function(e){
            toggleVisibility('distretto_check', 'distretto');
        });

        $("#utg_check").click(function(e){
            toggleVisibility('utg_check', 'utg');
        });

        $("#zone_check").click(function(e){
            toggleVisibility('zone_check', 'zone');
        });


        $("#main_editable").click(function(e){
              distretto.setEditable($(this).is(':checked'));
        });

        $("#main_draggable").click(function(e){
              distretto.setDraggable($(this).is(':checked'));
        });
        

	</script>
	
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrH8m8vUnPJQKt8zDTokE7Fg-kSGuL0mY&callback=initMap" type="text/javascript"></script>
	


	<script type="text/javascript">
		$(function () {
		    
		
		}); // onload
		

	</script>

@endsection