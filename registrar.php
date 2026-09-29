<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = trim($_POST['titulo'] ?? '');
  $fecha = trim($_POST['fecha'] ?? '');
  $horaInicio = trim($_POST['hora-inicio'] ?? '');

  if ($titulo !== '' && $fecha !== '' && $horaInicio !== '') {
    $_SESSION['eventos'][] = [
      'id' => uniqid('evento_', true),
      'titulo' => $titulo,
      'fecha' => $fecha,
      'hora_inicio' => $horaInicio,
      'hora_fin' => trim($_POST['hora-fin'] ?? ''),
      'categoria' => trim($_POST['categoria'] ?? 'otro'),
      'notas' => trim($_POST['notas'] ?? ''),
    ];

    header('Location: index.php?ok=1');
    exit;
  }

  header('Location: registrar.php?error=1');
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nuevo evento · AgendaWeb</title>
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
        <a href="registrar.php" class="nav__link is-active">Nuevo evento</a>
      </nav>
    </div>
  </header>

<main class="af-page">
  <form class="af-card" action="registrar.php" method="post">
    <div class="af-header">
      <p class="af-eyebrow">AgendaWeb</p>
      <h1>Nuevo registro</h1>
      <p class="af-hint">Completa los datos del evento o cita. Los campos con * son obligatorios.</p>
    </div>

    <div class="af-field">
      <label class="af-label" for="titulo">Título del evento *</label>
      <input class="af-control" type="text" id="titulo" name="titulo" placeholder="Ej. Reunión con el equipo de diseño" required>
    </div>

    <div class="af-row">
      <div class="af-field">
        <label class="af-label" for="fecha">Fecha *</label>
        <input class="af-control" type="date" id="fecha" name="fecha" required>
      </div>
      <div class="af-field">
        <label class="af-label" for="hora-inicio">Hora de inicio *</label>
        <input class="af-control" type="time" id="hora-inicio" name="hora-inicio" required>
      </div>
      <div class="af-field">
        <label class="af-label" for="hora-fin">Hora de fin</label>
        <input class="af-control" type="time" id="hora-fin" name="hora-fin">
      </div>
    </div>

    <div class="af-field">
      <label class="af-label" for="ubicacion">Ubicación</label>
      <input class="af-control" type="text" id="ubicacion" name="ubicacion" placeholder="Oficina, videollamada, dirección...">
    </div>

    <div class="af-row af-row--two">
      <div class="af-field">
        <label class="af-label" for="categoria">Categoría</label>
        <select class="af-control" id="categoria" name="categoria">
          <option value="trabajo">Trabajo</option>
          <option value="personal">Personal</option>
          <option value="salud">Salud</option>
          <option value="social">Social</option>
          <option value="otro">Otro</option>
        </select>
      </div>
      <div class="af-field">
        <label class="af-label" for="prioridad">Prioridad</label>
        <select class="af-control" id="prioridad" name="prioridad">
          <option value="alta">Alta</option>
          <option value="media" selected>Media</option>
          <option value="baja">Baja</option>
        </select>
      </div>
    </div>

    <div class="af-field">
      <label class="af-label" for="notas">Notas</label>
      <textarea class="af-control" id="notas" name="notas" rows="4" placeholder="Agrega detalles, enlaces o recordatorios para este evento"></textarea>
    </div>

    <label class="af-checkbox" for="recordatorio">
      <input type="checkbox" id="recordatorio" name="recordatorio">
      <span class="af-checkbox-box" aria-hidden="true"></span>
      <span>Avisarme antes de que empiece</span>
    </label>

    <div class="af-actions">
      <a href="index.php" class="af-btn af-btn--ghost">Cancelar</a>
      <button type="submit" class="af-btn af-btn--primary">Guardar evento</button>
    </div>
  </form>
</main>

  <footer class="site-footer">
    <div class="contenedor">AgendaWeb · Tu nombre · 2026</div>
  </footer>
</body>
</html>