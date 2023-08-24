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


	
	//echo json_encode($salida);
	include_once '.././config/Database.php';

	
// Crear una instancia de la clase Database
$database = new Database();

// Obtener una conexión a la base de datos
$conn = $database->getConnection();

// Consulta SQL para obtener las ubicaciones desde la tabla "tickets"
$query = "SELECT location FROM tickets";

// Ejecutar la consulta
$result = $conn->query($query);

// Crear un array para almacenar las ubicaciones
$ubicaciones_db = array();

// Verificar si la consulta fue exitosa
if ($result) {
    // Recorrer los resultados y agregar las ubicaciones al array
    while ($row = $result->fetch_assoc()) {
        $ubicaciones_db[] = $row["location"];
    }
    // Liberar el resultado
    $result->free();
} else {
    echo "Error en la consulta: " . $conn->error;
}

	// Filtrar el array $salida manteniendo las coordenadas en las ubicaciones coincidentes
	$ubicaciones_filtradas = array();
	foreach ($salida as $ciudad) {
		if (in_array($ciudad["title"], $ubicaciones_db)) {
			$ubicaciones_filtradas[] = $ciudad;
		}
	}
	echo json_encode($ubicaciones_filtradas);
	
$conn->close();

// Mostrar el array de ubicaciones obtenidas de la base de datos
?>