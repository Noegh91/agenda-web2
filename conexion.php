<?php
function obtenerConexion(): PDO
{
  $host = getenv('AGENDA_DB_HOST') ?: 'localhost';
  $puerto = getenv('AGENDA_DB_PORT') ?: '3306';
  $baseDatos = getenv('AGENDA_DB_NAME') ?: 'agenda';
  $usuario = getenv('AGENDA_DB_USER') ?: 'root';
  $contrasena = getenv('AGENDA_DB_PASSWORD');

  if ($contrasena === false) {
    $contrasena = '';
  }

  $dsn = "mysql:host={$host};port={$puerto};dbname={$baseDatos};charset=utf8mb4";

  return new PDO($dsn, $usuario, $contrasena, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
}