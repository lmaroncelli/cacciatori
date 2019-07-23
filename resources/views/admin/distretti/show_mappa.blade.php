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

          @include('admin.mappa.bottoni')
        </div>
      </div>
		</div>
	</div>	
	
	@php
		$item = $distretto
	@endphp
@endsection


@section('script_footer')

	<script type="text/javascript">
		var infoWindow;
		var map;

		var contentString;


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

        @foreach ($coordinate_zona as $coordinata_zona)
          
          var zona_coords = new Array();

          @foreach ($coordinata_zona as $lat => $long)


        
            var jsonData = {};
            jsonData['lat'] = {{$lat}};
            jsonData['lng'] = {{$long}};
            
            //console.log('jsonData = '+JSON.stringify(jsonData));

            zona_coords.push(jsonData);
          
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


        @endforeach
				

		    
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
		      var distretto = new google.maps.Polygon({
		        paths: distretto_coords,
		        strokeColor: 'yellow',
		        strokeOpacity: 0.8,
		        strokeWeight: 2,
		        fillColor: 'yellow',
		        fillOpacity: 0.35,
		        editable: true,
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
		    	

		     contentString = '<b>'+nome+'</b><br>' +
		     		      'Coordinate correnti: <br>' + c_lat + ',' + c_long +
		     		      '<br>'+
		     		      'Zoom corrente: '+ c_zoom + '<br><br>';


		      // Iterate over the vertices.
		      for (var i =0; i < vertices.getLength(); i++) {
		        var xy = vertices.getAt(i);
		        contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
		            xy.lng();
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

	</script>
	
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrH8m8vUnPJQKt8zDTokE7Fg-kSGuL0mY&callback=initMap" type="text/javascript"></script>
	


	<script type="text/javascript">
		$(function () {
		    
		
		}); // onload
		

	</script>

@endsection