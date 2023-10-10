<?php

$db = new SQLite3('usuarios.db');

// Crear la tabla de usuarios si no existe
$query = "CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL
)";
$db->exec($query);

$db->close();

echo "Tabla de usuarios creada correctamente.";
?>
