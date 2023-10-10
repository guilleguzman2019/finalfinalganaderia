<?php
session_start(); // Inicia la sesión

$username = $_POST['username']; // Nombre de usuario o correo electrónico
$password = $_POST['password']; // Contraseña

$db = new SQLite3('usuarios.db');

$query = "SELECT * FROM usuarios WHERE (username='$username' OR email='$username') AND password='$password'";
$result = $db->query($query);
$row = $result->fetchArray(SQLITE3_ASSOC);

if($row) {
    // Usuario autenticado correctamente
    $_SESSION['username'] = $row['username']; // Guarda el nombre de usuario en la sesión
    header('Location: listado.php'); // Redirige a la página de bienvenida
} else {
    // Usuario no autenticado
    echo "Usuario o contraseña incorrectos";
}

$db->close();
?>
