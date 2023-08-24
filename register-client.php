<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $role = $_POST["role"];
    $status = $_POST["status"];
   

    // Validar que los campos no estén vacíos
    if (empty($name) || empty($role) || empty($email) || empty($status) || empty($password)) {
        echo "Todos los campos son obligatorios. Por favor, completa el formulario.";
    } else {
        // Conexión a la base de datos

        include_once 'config/Database.php';

        include_once 'class/User.php';
        include_once 'class/Ticket.php';
         /* Prueba */
        $database = new Database();
        $db = $database->getConnection();
        
        $user = new User($db);






        // Generar el hash de la contraseña
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Preparar la consulta SQL para insertar el nuevo autor
        $consulta = "INSERT INTO ticket_user (email, password, name, role, status) VALUES (?, ?, ?, ?, ?)";


        // Preparar y ejecutar la consulta
        $statement = $db->prepare($consulta);
        $statement->bind_param("sssss", $email, $hash_password, $name, $role, $status);
        

        if ($statement->execute()) {
            // Mostrar mensaje de registro exitoso y enlace a la página principal
            echo "Registro de autor exitoso. ¡Bienvenido, $name $role! Gracias por registrarte.<br>";
            echo '<a href="index.php">Volver a la página principal</a>';
        } else {
            // Mostrar mensaje de error y enlace al formulario de registro
            echo "Error al registrar el autor. Inténtalo de nuevo.<br>";
            echo '<a href="register-client.php">Volver al formulario de registro</a>';
        }

        // Cerrar la conexión a la base de datos
        $db = null;
    }
}
?>