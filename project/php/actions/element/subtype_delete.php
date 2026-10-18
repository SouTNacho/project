<?php

    session_start();
    // Falta validar rol

    header("Content-Type: application/json; charset=UTF-8");

    require_once __DIR__ . "/../../models/element_model.php";
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

    $subtype_id = filter_var($_POST['subtype_id'] ?? '', FILTER_VALIDATE_INT);

    if ($subtype_id === false || $subtype_id <= 0) {
        jsonResponse(400, false, "El ID del subtipo es incorrecto.");
    }

    $mysqli = connection_db();

    try {

        $subtype = findSubtypeWithId($mysqli, $subtype_id);

        if (!$subtype) {
            jsonResponse(404, false, "No se encontró el subtipo.", $mysqli);
        }

        // Cuenta TODOS los elementos(tambien los Eliminados porque siguen haciendo referencia al subtipo)
        //COUNT(*) en sql
        $in_use = countElementsWithSubtype($mysqli, $subtype_id);

        if ($in_use > 0) {

            $detail = $in_use === 1 ? "hay 1 elemento que lo usa" : "hay " . $in_use . " elementos que lo usan";

            jsonResponse(409, false, "No se puede eliminar este subtipo: " . $detail . ".", $mysqli);
        }

        deleteSubtype($mysqli, $subtype_id);

        jsonResponse(200, true, "Subtipo eliminado correctamente.", $mysqli);

    } catch (Throwable $e) {

        error_log("subtype_delete.php: " . $e->getMessage());

        if ((int) $e->getCode() === 1451) {
            jsonResponse(409, false, "No se puede eliminar este subtipo: está siendo usado por elementos.", $mysqli);
        }

        jsonResponse(500, false, "Ocurrió un error al eliminar el subtipo.", $mysqli);
    }