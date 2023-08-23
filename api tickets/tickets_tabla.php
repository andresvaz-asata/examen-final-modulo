<?php 
    $fichero = new DOMDocument();
	$fichero->load( "ticketLocation.xml");
	$salida = array(); 
	$tickets = $fichero->getElementsByTagName("item");
    foreach($tickets as $entry) {
		$nuevo = array();
		$nuevo["title"] = $entry->getElementsByTagName("title")[0]->nodeValue;
		$nuevo["lat"] =  $entry->getElementsByTagName("lat")[0]->nodeValue;
		$nuevo["lng"] =  $entry->getElementsByTagName("long")[0]->nodeValue;		
		$salida[] = $nuevo;
    }
?>
<html>
	<head>
		<meta charset = "UTF-8">
		<title>¿Dónde hay tickets creados?</title>
		<style> table, td, th { border-style: solid} </style>
	</head>
	<body>
		<table>
			<tr><td>Título</td><td>Longitud</td><td>Latitud</td></tr>
			<?php
				foreach($salida as $elemento) {
					$titulo = $elemento["title"];
					$lat = $elemento["lat"];
					$lon = $elemento["lng"];
					echo "<tr><td>$titulo</td><td>$lon</td><td>$lat</td></tr>";
				}
			?>
		</table>
	</body>
</html>