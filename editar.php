<?php
require_once __DIR__ . '/eventos.php';

$id = filter_var($_POST['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
  header('Location: index.php');
  exit;
}

try {
  $conexion = obtenerConexion();
  $evento = obtenerEvento($conexion, $id);
} catch (Throwable $error) {
  error_log($error->getMessage());
  header('Location: index.php?error=db');
  exit;
}

if ($evento === null) {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = trim($_POST['titulo'] ?? '');
  $fecha = trim($_POST['fecha'] ?? '');
  $horaInicio = trim($_POST['hora-inicio'] ?? '');

  if ($titulo === '' || $fecha === '') {
    header('Location: editar.php?id=' . urlencode((string) $id) . '&error=1');
    exit;
  }

  $datos = [
    'titulo' => $titulo,
    'fecha' => $fecha,
    'hora_inicio' => $horaInicio,
    'hora_fin' => trim($_POST['hora-fin'] ?? ''),
    'categoria' => trim($_POST['categoria'] ?? 'otro'),
    'notas' => trim($_POST['notas'] ?? ''),
  ];

  try {
    actualizarEvento($conexion, $id, $datos);
  } catch (Throwable $error) {
    error_log($error->getMessage());
    header('Location: editar.php?id=' . urlencode((string) $id) . '&error=db');
    exit;
  }

  header('Location: index.php?actualizado=1');
  exit;
}

$valor = static fn (string $campo): string => htmlspecialchars((string) ($evento[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
$categoria = strtolower($evento['categoria']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar evento · AgendaWeb</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="estilos.css">
</head>
<body class="layout">
  <header class="site-header">
    <div class="contenedor site-header__inner">
      <a href="index.php" class="logo">Agenda<span>Web</span></a>
      <nav class="nav">
        <a href="index.php" class="nav__link">Mis eventos</a>
        <a href="registrar.php" class="nav__link">Nuevo evento</a>
      </nav>
    </div>
  </header>

  <main class="af-page">
    <form class="af-card" action="editar.php" method="post">
      <div class="af-header">
        <p class="af-eyebrow">AgendaWeb</p>
        <h1>Editar evento</h1>
        <p class="af-hint">Actualiza los datos del evento. El título y la fecha son obligatorios.</p>
      </div>

      <?php if (isset($_GET['error']) && $_GET['error'] === '1'): ?>
        <div class="alert" role="alert">Completa el título y la fecha.</div>
      <?php elseif (isset($_GET['error']) && $_GET['error'] === 'db'): ?>
        <div class="alert" role="alert">No se pudieron guardar los cambios. Revisa la conexión con MySQL.</div>
      <?php endif; ?>

      <input type="hidden" name="id" value="<?= $valor('id') ?>">
      <div class="af-field">
        <label class="af-label" for="titulo">Título del evento *</label>
        <input class="af-control" type="text" id="titulo" name="titulo" value="<?= $valor('titulo') ?>" required>
      </div>

      <div class="af-row">
        <div class="af-field">
          <label class="af-label" for="fecha">Fecha *</label>
          <input class="af-control" type="date" id="fecha" name="fecha" value="<?= $valor('fecha') ?>" required>
        </div>
        <div class="af-field">
          <label class="af-label" for="hora-inicio">Hora de inicio</label>
          <input class="af-control" type="time" id="hora-inicio" name="hora-inicio" value="<?= $valor('hora_inicio') ?>">
        </div>
        <div class="af-field">
          <label class="af-label" for="hora-fin">Hora de fin</label>
          <input class="af-control" type="time" id="hora-fin" name="hora-fin" value="<?= $valor('hora_fin') ?>">
        </div>
      </div>

      <div class="af-field">
        <label class="af-label" for="categoria">Categoría</label>
        <select class="af-control" id="categoria" name="categoria">
          <?php foreach (['trabajo' => 'Trabajo', 'personal' => 'Personal', 'salud' => 'Salud', 'social' => 'Social', 'otro' => 'Otro'] as $valorCategoria => $etiqueta): ?>
            <option value="<?= $valorCategoria ?>" <?= $categoria === $valorCategoria ? 'selected' : '' ?>><?= $etiqueta ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="af-field">
        <label class="af-label" for="notas">Notas</label>
        <textarea class="af-control" id="notas" name="notas" rows="4"><?= $valor('notas') ?></textarea>
      </div>

      <div class="af-actions">
        <a href="index.php" class="af-btn af-btn--ghost">Cancelar</a>
        <button type="submit" class="af-btn af-btn--primary">Guardar cambios</button>
      </div>
    </form>
  </main>

  <footer class="site-footer">
    <div class="contenedor">AgendaWeb · Tu nombre · 2026</div>
  </footer>
</body>
</html>