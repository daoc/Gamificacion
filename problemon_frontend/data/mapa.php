
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,
maximum-scale=1.0, minimun-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<title>Búsqueda del tesoro</title>
	<script>

function initMap() {
	var myLatLng = {lat: -0.2371699, lng: -78.5318944, };
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 19,
		center: myLatLng,
	});
	
	var infoWindow = new google.maps.InfoWindow({map: map});
	
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Location found.');
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
			  
          });
			
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      
	
	var geocoder = new google.maps.Geocoder();
	
	document.getElementById('address').addEventListener('blur', function() {
			geocodeAddress(geocoder, map);
    	});
var iconoMarc = './Recursos/Imagenes/iconoubicacion.png';
	function geocodeAddress(geocoder, resultsMap) {
		
        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
			  
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
				animation: google.maps.Animation.BOUNCE,
				icon: iconoMarc,
              position: results[0].geometry.location,
				draggable: true,
            });
		var infowindow = new google.maps.InfoWindow({
          content: '<p>Posición del Marcador:' + marker.getPosition() + '</p>'
        });
			  document.getElementById('coord').value=marker.getPosition();
		google.maps.event.addListener(marker, 'mouseover', function() {
			document.getElementById('coord').value=marker.getPosition();	
        });
			  google.maps.event.addListener(marker, 'mouseout', function() {
			document.getElementById('coord').value=marker.getPosition();	
        });
			  google.maps.event.addListener(marker, 'mouseclick', function() {
			document.getElementById('coord').value=marker.getPosition();	
        });
			  
          } else {
            //alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
	
}	
	
	function doNothing() {}
	
	function clearMarkers() {
        setMapOnAll(null);
      }
</script>
	<style>
	#map {
        height: 100%;
		width: 100%;
	}
      /* Optional: Makes the sample page fill the window. */
	html, body {
        height: 100%;
        margin: 0;
		padding: 0;
    }
	a[href^="http://maps.google.com/maps"]{display:none !important}
	a[href^="https://maps.google.com/maps"]{display:none !important}

	.gmnoprint a, .gmnoprint span, .gm-style-cc {
		display:none;
	}
	.gmnoprint div {
		background:none !important;
	}
    </style>
	<script>
	function disableEnterKey(e){
		var key;
		if(window.event){
			key = window.event.keyCode; //IE
		}else{
			key = e.which; //firefox
		}
		if(key==13){
			return false;
		}else{
			return true;
		}
	}
	
	</script>
</head>
<body>
	<?php
	if ( $_POST ){
		session_start();
		$variable = $_POST['coord'];
		$_SESSION['coordenadas']=$variable;
		print "<script> alert('Coordenadas obtenidas exitósamente'); window.close();</script>";
	}
		
	?>
	<center>
		<form action="#" method="post" onKeyPress="return disableEnterKey(event)">
			<table>
				<tr>
					<td>Ingrese la dirección</td>
					<td><input type="text" onBlur="" class="cajastexto" id="address" placeholder="Dirección del Generador" name="direccion" /></td>
					<td><input type="text" id="coord" class="cajastexto" name="coord" style="display: none"></td>
				</tr>
					
			
			</table>
			
			<input type="submit" value="obtener coordenadas del punto" class="botones">
		</form>
	</center>
		
	<div id="map" style="align='center'">
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAX3z1OKQnbLIDquups7Nt9pJJI3zGbpVI&callback=initMap"></script>
	</div>
</body>
</html>
