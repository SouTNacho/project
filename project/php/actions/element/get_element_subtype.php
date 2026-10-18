
<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../../models/element_model.php";
    require_once __DIR__ . "/../../conection.php";

    $search = trim($_GET['search'] ?? '');
    $filter = $_GET['filter'] ?? 'all';

    // Validar el filtro recibido.
    if (!in_array($filter, ['all', 'bio', 'nonbio'], true)) {
        echo json_encode([
            'success' => false,
            'message' => 'Filtro no válido.'
        ]);
        exit;
    }

    $mysqli = connection_db();

    try {

        $subtypes = findSubtypes($mysqli, $search, $filter);

        echo json_encode([
            'success' => true,
            'message' => 'Solicitud exitosa.',
            'data' => $subtypes
        ]);

        $mysqli->close();

    } catch (mysqli_sql_exception $e) {

        $mysqli->close();

        echo json_encode([
            'success' => false,
            'message' => 'Ha ocurrido un error.'
        ]);
    }

?>