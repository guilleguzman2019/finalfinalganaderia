<?php
session_start();

// Destruir la sesión
session_destroy();

// Redirigir a la página de inicio de sesión (o a donde desees)
header('Location: index.html');
?>
