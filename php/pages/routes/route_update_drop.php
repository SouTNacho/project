<?php
session_start();
// Falta autenticar

$nombre_prefill = trim($_GET['nombre'] ?? '');
$ruta_actual = null;

if ($nombre_prefill !== '') {
    require_once __DIR__ . '/../../conection.php';

    $con = connection_db();

    $stmt = $con->prepare(
        "SELECT id_ruta, nombre, origen, destino, descripcion, id_estado_ruta
         FROM ruta
         WHERE nombre = ?"
    );

    $stmt->bind_param('s', $nombre_prefill);
    $stmt->execute();

    $result = $stmt->get_result();
    $ruta_actual = $result->fetch_assoc();

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Rutas - BYP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/src/logo_small.png" type="image/x-icon">
    <link rel="stylesheet" href="/styles/general_style.css">
    <link rel="stylesheet" href="/styles/form_style.css">
</head>

<body>

<?php
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../administrative_navbar.php';
?>

<main id="main" class="form-main">

    <form id="delete_ruta_form" class="form">

        <div>
            <h2>Desactivar Ruta</h2>
        </div>

        <div>
            <label for="ruta_delete">Nombre:</label>
            <input
                type="text"
                name="ruta_delete"
                id="ruta_delete"
                value="<?= htmlspecialchars($nombre_prefill) ?>"
                placeholder="Nombre de la ruta"
            >
            <span id="ruta_delete_msg"></span>
        </div>

        <div>
            <a href="/php/pages/routes/route_panel.php">
                <i data-lucide="arrow-left"></i>
                Volver
            </a>

            <button type="submit" id="boton_delete_ruta" class="form-btn">
                <i data-lucide="circle-x"></i>
                Desactivar
            </button>

            <span class="form-btn-msg"></span>
        </div>

    </form>

    <form id="update_ruta_form" class="form">

        <div>
            <h2>Actualizar Ruta</h2>
        </div>

        <div>
            <label for="nombre_ruta_update">Nombre actual:</label>
            <input
                type="text"
                name="nombre_ruta_update"
                id="nombre_ruta_update"
                value="<?= htmlspecialchars($ruta_actual['nombre'] ?? $nombre_prefill) ?>"
                placeholder="Nombre actual"
            >
            <span id="nombre_ruta_update_msg"></span>
        </div>

        <div>
            <label for="nombre_nuevo">Nuevo nombre:</label>
            <input
                type="text"
                name="nombre_nuevo"
                id="nombre_nuevo"
                placeholder="Nuevo nombre"
            >
            <span></span>
        </div>

        <div>
            <label for="origen_update">Origen:</label>
            <input
                type="text"
                name="origen_update"
                id="origen_update"
                value="<?= htmlspecialchars($ruta_actual['origen'] ?? '') ?>"
                placeholder="Origen"
            >
            <span></span>
        </div>

        <div>
            <label for="destino_update">Destino:</label>
            <input
                type="text"
                name="destino_update"
                id="destino_update"
                value="<?= htmlspecialchars($ruta_actual['destino'] ?? '') ?>"
                placeholder="Destino"
            >
            <span></span>
        </div>

        <div>
            <label for="descripcion_update">Descripción:</label>
            <textarea
                name="descripcion_update"
                id="descripcion_update"
                placeholder="Descripción"
            ><?= htmlspecialchars($ruta_actual['descripcion'] ?? '') ?></textarea>
            <span></span>
        </div>

        <div>
            <label for="id_estado_ruta_update">Estado:</label>
            <select name="id_estado_ruta_update" id="id_estado_ruta_update">
                <option value="1" <?= (($ruta_actual['id_estado_ruta'] ?? 1) == 1) ? 'selected' : '' ?>>Activa</option>
                <option value="2" <?= (($ruta_actual['id_estado_ruta'] ?? 1) == 2) ? 'selected' : '' ?>>Inactiva</option>
            </select>
            <span></span>
        </div>

        <div>
            <a href="/php/pages/routes/route_panel.php">
                <i data-lucide="arrow-left"></i>
                Volver
            </a>

            <button type="submit" id="boton_update_ruta" class="form-btn">
                <i data-lucide="refresh-cw"></i>
                Actualizar
            </button>

            <span class="form-btn-msg"></span>
        </div>

    </form>

</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="/js/rutes/ruta_delete.js"></script>
<script src="/js/rutes/ruta_update.js"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>
