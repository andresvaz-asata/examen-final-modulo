<?php 
    $fichero = new DOMDocument();
	$fichero->load("ticketLocation.xml");
	$salida = array(); 
	$tickets = $fichero->getElementsByTagName("item");
    foreach($tickets as $entry) {
		$nuevo = array();
		$nuevo["title"] = $entry->getElementsByTagName("title")[0]->nodeValue;
		$nuevo["lat"] =  $entry->getElementsByTagName("lat")[0]->nodeValue;
		$nuevo["lng"] =  $entry->getElementsByTagName("long")[0]->nodeValue;		
		$salida[] = $nuevo;
    }
	echo json_encode($salida);
