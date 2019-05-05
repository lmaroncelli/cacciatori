@extends('layouts.app')


@section('header_css')
	<style type="text/css" media="screen">
		#map {
			width: 1000px;
			height: 800px;
		}
	</style>
@endsection


@section('content')
	
	<h3>Zona {{$zona->nome}}</h3>
	
	<div id="map"></div>

@endsection


@section('script_footer')

	<script type="text/javascript">
		var infoWindow;
		var map;


				// Initialize and add the map
				function initMap() {

				  // The location of center
				  var center = {lat: 44.060921, lng: 12.566300};

				  // Define the LatLng coordinates for the polygon's path.
				  // 
		  	 	// Define the LatLng coordinates for the polygon's path. Note that there's
		    	// no need to specify the final coordinates to complete the polygon, because
		    	// The Google Maps JavaScript API will automatically draw the closing side.
		     /*var distretto_coords = [
		       {lat: 44.066493, lng: 12.550754},
		       {lat:44.069207, lng: 12.592095},
		       {lat: 44.044657, lng: 12.597757},
		       {lat: 44.048605, lng: 12.535472}
		     ];*/

		    
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

				  // The map
				  map = new google.maps.Map(
				      document.getElementById('map'), {zoom: 13, center: center});

				  
				  // Construct the polygon.
		      var distretto = new google.maps.Polygon({
		        paths: distretto_coords,
		        strokeColor: '#FF0000',
		        strokeOpacity: 0.8,
		        strokeWeight: 2,
		        fillColor: '#FF0000',
		        fillOpacity: 0.35,
		        editable: true
		      });


		      //To add a layer to a map, you only need to call setMap(), passing it the map object on which to display the layer. 
		      distretto.setMap(map);

		      // Add a listener for the click event.
		      distretto.addListener('click', showArrays);

		     	infoWindow = new google.maps.InfoWindow;

				}

						/** @this {google.maps.Polygon} */
				    function showArrays(event) {

				    	
				      // Since this polygon has only one path, we can call getPath() to return the
				      // MVCArray of LatLngs.
				      var vertices = this.getPath();
				    	

				      var contentString = '<b>Rimini polygon</b><br>' +
				          'Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
				          '<br>';


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
				    }

	</script>
	
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrH8m8vUnPJQKt8zDTokE7Fg-kSGuL0mY&callback=initMap" type="text/javascript"></script>


@endsection