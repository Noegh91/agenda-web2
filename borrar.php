<?php
require_once __DIR__ . '/eventos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit;
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
  header('Location: index.php');
  exit;
}

try {
  borrarEvento(obtenerConexion(), $id);
} catch (Throwable $error) {
  error_log($error->getMessage());
  header('Location: index.php?error=db');
  exit;
}

header('Location: index.php?borrado=1');
exit;