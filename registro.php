<?php
$username = $_POST['username']; // nombre de usuario
$email = $_POST['email']; // correo electrónico
$password = $_POST['password']; // contraseña

// Validar que el nombre de usuario y el correo no estén vacíos
if(empty($username) || empty($email) || empty($password)) {
    echo "Por favor, completa todos los campos.";
    exit();
}

// Verificar si el usuario ya existe en la base de datos
$db = new SQLite3('usuarios.db');
$query = "SELECT * FROM usuarios WHERE username='$username' OR email='$email'";
$result = $db->query($query);
$row = $result->fetchArray(SQLITE3_ASSOC);

if($row) {
    echo "El nombre de usuario o correo electrónico ya está en uso.";
    $db->close();
    exit();
}

// Si el usuario no existe, proceder con el registro
$query = "INSERT INTO usuarios (username, email, password) VALUES ('$username', '$email', '$password')";
$db->exec($query);

// Iniciar sesión después del registro
session_start();
$_SESSION['username'] = $username;

// Redirigir a la página de bienvenida o a donde desees
header('Location: listado.php');

$db->close();
?>

