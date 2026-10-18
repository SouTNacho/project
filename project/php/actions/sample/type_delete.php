<?php

    session_start();
    // Falta validar rol

    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../../models/sample_model.php";
    require_once __DIR__ . "/../../conection.php";

    function jsonResponse($status, $success, $message, $mysqli = null) {

        if ($mysqli) {
            $mysqli->close();
        }

        http_response_code($status);

        echo json_encode(
            [
                'success' => $success,
                'message' => $message
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        jsonResponse(405, false, "Método no permitido.");
    }

    $type_id = filter_var($_POST['type_id'] ?? '', FILTER_VALIDATE_INT);

    if ($type_id === false || $type_id <= 0) {
        jsonResponse(400, false, "El ID del tipo es incorrecto.");
    }

    $mysqli = connection_db();

    try {

        $type = findTypeWithId($mysqli, $type_id);

        if (!$type) {
            jsonResponse(404, false, "No se encontró el tipo.", $mysqli);
        }

        //COUNT(*) en sql
        $in_use = countSamplesWithType($mysqli, $type_id);

        if ($in_use > 0) {

            $detail = $in_use === 1 ? "hay 1 muestra que lo usa" : "hay " . $in_use . " muestras que lo usan";

            jsonResponse(409, false, "No se puede eliminar este tipo: " . $detail . ".", $mysqli);
        }

        deleteType($mysqli, $type_id);

        jsonResponse(200, true, "Tipo eliminado correctamente.", $mysqli);

    } catch (Throwable $e) {

        error_log("type_delete.php: " . $e->getMessage());

        if ((int) $e->getCode() === 1451) {
            jsonResponse(409, false, "No se puede eliminar este tipo: está siendo usado por muestras.", $mysqli);
        }

        jsonResponse(500, false, "Error: " . $e->getMessage(), $mysqli);    }