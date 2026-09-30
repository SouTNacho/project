<?php
session_start();
// Falta implementar el rol

require_once __DIR__ . '/../../conection.php';

$con = connection_db();
$id_seleccionado = (int)($_GET['id'] ?? 0);
$ubicaciones = [];
$ubicacion = null;

$resultado = $con->query(
    "SELECT id_ubicacion, nombre, direccion, departamento, localidad, descripcion
     FROM ubicacion
     ORDER BY nombre ASC"
);

if ($resultado) {
    $ubicaciones = $resultado->fetch_all(MYSQLI_ASSOC);
}

if ($id_seleccionado > 0) {
    $stmt = $con->prepare(
        "SELECT id_ubicacion, nombre, direccion, departamento, localidad, descripcion
         FROM ubicacion
         WHERE id_ubicacion = ?"
    );

    $stmt->bind_param("i", $id_seleccionado);
    $stmt->execute();
    $result = $stmt->get_result();
    $ubicacion = $result->fetch_assoc();
    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Ubicaciones - BYP</title>
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

    <section class="form">
        <div>
            <h2>Seleccionar Ubicación</h2>
        </div>

        <div>
            <label for="ubicacion_selector">Ubicación:</label>
            <select id="ubicacion_selector">
                <option value="">Seleccione una ubicación...</option>
                <?php foreach ($ubicaciones as $item): ?>
                    <option
                        value="<?= (int)$item['id_ubicacion'] ?>"
                        <?= $id_seleccionado === (int)$item['id_ubicacion'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($item['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span></span>
        </div>

        <div>
            <a href="/php/pages/locations/register_locations.php">
                <i data-lucide="arrow-left"></i>
                Volver
            </a>
        </div>
    </section>

    <?php if ($ubicacion): ?>

        <form id="delete_location_form" class="form">

            <div>
                <h2>Eliminar Ubicación</h2>
            </div>

            <input type="hidden" id="id_ubicacion_delete" value="<?= (int)$ubicacion['id_ubicacion'] ?>">

            <div>
                <label for="nombre_ubicacion_delete">Ubicación:</label>
                <input
                    type="text"
                    id="nombre_ubicacion_delete"
                    name="nombre_ubicacion_delete"
                    value="<?= htmlspecialchars($ubicacion['nombre']) ?>"
                    readonly
                >
                <span></span>
            </div>

            <div>
                <a href="/php/pages/locations/register_locations.php">
                    <i data-lucide="arrow-left"></i>
                    Volver
                </a>

                <button type="submit" id="boton_delete_ubicacion" class="form-btn">
                    <i data-lucide="trash-2"></i>
                    Eliminar
                </button>

                <span class="form-btn-msg"></span>
            </div>

        </form>

        <form id="update_location_form" class="form">

            <div>
                <h2>Actualizar Ubicación</h2>
            </div>

            <input type="hidden" id="id_ubicacion_update" value="<?= (int)$ubicacion['id_ubicacion'] ?>">

            <div>
                <label for="nombre_ubicacion_update">Nombre:</label>
                <input
                    type="text"
                    id="nombre_ubicacion_update"
                    name="nombre_ubicacion_update"
                    value="<?= htmlspecialchars($ubicacion['nombre']) ?>"
                >
                <span></span>
            </div>

            <div>
                <label for="direccion_update">Dirección:</label>
                <input
                    type="text"
                    id="direccion_update"
                    name="direccion_update"
                    value="<?= htmlspecialchars($ubicacion['direccion']) ?>"
                >
                <span></span>
            </div>

            <div>
                <label for="departamento_update">Departamento:</label>
                <input
                    type="text"
                    id="departamento_update"
                    name="departamento_update"
                    value="<?= htmlspecialchars($ubicacion['departamento']) ?>"
                >
                <span></span>
            </div>

            <div>
                <label for="localidad_update">Localidad:</label>
                <input
                    type="text"
                    id="localidad_update"
                    name="localidad_update"
                    value="<?= htmlspecialchars($ubicacion['localidad']) ?>"
                >
                <span></span>
            </div>

            <div>
                <label for="descripcion_update">Descripción:</label>
                <textarea
                    id="descripcion_update"
                    name="descripcion_update"
                ><?= htmlspecialchars($ubicacion['descripcion'] ?? '') ?></textarea>
                <span></span>
            </div>

            <div>
                <a href="/php/pages/locations/register_locations.php">
                    <i data-lucide="arrow-left"></i>
                    Volver
                </a>

                <button type="submit" id="boton_update_ubicacion" class="form-btn">
                    <i data-lucide="refresh-cw"></i>
                    Actualizar
                </button>

                <span class="form-btn-msg"></span>
            </div>

        </form>

    <?php else: ?>

        <section class="form">
            <div>
                <h2>Seleccione una ubicación</h2>
            </div>
            <div>
                <p>Al seleccionar una ubicación se cargarán automáticamente sus datos para poder modificarlos o eliminarla.</p>
            </div>
        </section>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="/js/location/location_delete.js"></script>
<script src="/js/location/location_update.js"></script>
<script>
    lucide.createIcons();

    const selector = document.getElementById('ubicacion_selector');

    if (selector) {
        selector.addEventListener('change', () => {
            if (selector.value === '') return;
            window.location.href =
                '/php/pages/locations/delete_update_locations.php?id=' + selector.value;
        });
    }
</script>

</body>
</html>
