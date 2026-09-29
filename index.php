<?php
session_start();

$eventos = [
  [
    'id' => 1,
    'categoria' => 'Trabajo',
    'titulo' => 'Reunión de academia',
    'fecha' => '2026-09-25',
    'hora_inicio' => '10:30',
    'hora_fin' => '',
    'notas' => 'Revisar calificaciones del 1er parcial.',
  ],
  [
    'id' => 2,
    'categoria' => 'Salud',
    'titulo' => 'Cita con el dentista',
    'fecha' => '2026-10-02',
    'hora_inicio' => '17:00',
    'hora_fin' => '',
    'notas' => '',
  ],
  [
    'id' => 3,
    'categoria' => 'Personal',
    'titulo' => 'Entrega de documentos para el trámite de renovación de credencial y comprobante de domicilio',
    'fecha' => '2026-10-08',
    'hora_inicio' => '',
    'hora_fin' => '',
    'notas' => 'Llevar copias y originales.',
  ],
];

$eventos = array_merge($eventos, $_SESSION['eventos'] ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis eventos · AgendaWeb</title>
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
        <a href="index.php" class="nav__link is-active">Mis eventos</a>
        <a href="registrar.php" class="nav__link">Nuevo evento</a>
      </nav>
    </div>
  </header>

  <main class="contenedor">

    <!-- En P4 solo aparecerá si la URL trae ?ok=1 -->
        <?php if (isset($_GET['ok']) && $_GET['ok'] === '1'): ?>
      <div class="alert alert--ok" role="status">&#9989; Evento guardado.</div>
    <?php endif; ?>

    <div class="page__header">
      <div>
        <h1 class="page__title">Mis eventos</h1>
        <p class="page__subtitle"><?= count($eventos) ?> eventos registrados</p>
      </div>
      <a href="registrar.php" class="btn-primary">+ Nuevo evento</a>
    </div>

    <section class="card-list">

      <?php foreach ($eventos as $evento): ?>
        <?php
          $fecha = new DateTime($evento['fecha']);
          $fechaTexto = $fecha->format('d/m/Y');
          $horaTexto = $evento['hora_inicio'] !== '' ? ' · ' . $evento['hora_inicio'] : '';
          $fechaHora = $evento['fecha'] . ($evento['hora_inicio'] !== '' ? 'T' . $evento['hora_inicio'] : '');
        ?>
        <article class="card">
          <span class="card__badge"><?= htmlspecialchars(ucfirst($evento['categoria']), ENT_QUOTES, 'UTF-8') ?></span>
          <h2 class="card__title"><?= htmlspecialchars($evento['titulo'], ENT_QUOTES, 'UTF-8') ?></h2>
          <p class="card__meta">
            <time datetime="<?= htmlspecialchars($fechaHora, ENT_QUOTES, 'UTF-8') ?>"><?= $fechaTexto . $horaTexto ?></time>
          </p>
          <?php if ($evento['notas'] !== ''): ?>
            <p class="card__text"><?= htmlspecialchars($evento['notas'], ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>

          <div class="card__actions">
            <a href="editar.php?id=<?= urlencode((string) $evento['id']) ?>" class="btn-secondary btn-sm">Editar</a>
            <form method="post" action="borrar.php" class="form-inline">
              <input type="hidden" name="id" value="<?= htmlspecialchars((string) $evento['id'], ENT_QUOTES, 'UTF-8') ?>">
              <button type="submit" class="btn-danger btn-sm">Borrar</button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>

    </section>

    <!-- En P4 solo aparecerá si no hay eventos -->
  </main>

  <footer class="site-footer">
    <div class="contenedor">AgendaWeb · noe garcia · 2026</div>
  </footer>
</body>
</html>
