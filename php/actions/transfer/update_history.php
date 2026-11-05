<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    header("Content-type: application/json");
    validateActionSession('driver');

    require_once __DIR__ . "/../../models/transfer_model.php";
    require_once __DIR__ . "/../../conection.php";

    $id = (int) ($_POST['id'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if ($id <= 0 || $description === '') {

        echo json_encode([
            'success' => false,
            'message' => 'Error, los datos recibidos no son válidos.'
        ]);

        exit;
    }

    $mysqli = connection_db();

    try {

        updateHistory($mysqli, $id, $description);

        echo json_encode([
            'success' => true,
            'message' => 'Historial actualizado correctamente.'
        ]);

    } catch (mysqli_sql_exception $e) {

        // DESPUÉS QUITAR EL MENSAJE
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el historial: ' . $e->getMessage()
        ]);

    } finally {
        $mysqli->close();
    }

?>