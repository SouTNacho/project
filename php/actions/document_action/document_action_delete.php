<?php

    header('Content-Type: application/json; charset=utf-8');

    require_once __DIR__ . '/../../models/document_action_model.php';
    require_once __DIR__ . '/../../conection.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido.'
        ]);
        exit();
    }

    $action_id = filter_var($_POST['action_id'] ?? null, FILTER_VALIDATE_INT);

    if ($action_id === false || $action_id === null || $action_id < 1) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'La acción seleccionada no es válida.'
        ]);
        exit();
    }

    $mysqli = connection_db();

    try {
        if (!findDocumentActionById($mysqli, $action_id)) {
            $mysqli->close();
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'La acción no existe.'
            ]);
            exit();
        }

        if (isRequiredDocumentAction($action_id)) {
            $mysqli->close();
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'No se puede eliminar una acción utilizada directamente por el sistema.'
            ]);
            exit();
        }

        if (isDocumentActionInUse($mysqli, $action_id)) {
            $mysqli->close();
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'No se puede eliminar una acción que ya está asociada al historial de documentos.'
            ]);
            exit();
        }

        deleteDocumentAction($mysqli, $action_id);
        $mysqli->close();

        echo json_encode([
            'success' => true,
            'message' => 'Acción de documento eliminada correctamente.'
        ]);
    } catch (mysqli_sql_exception $exception) {
        $mysqli->close();
        http_response_code($exception->getCode() === 1451 ? 409 : 500);
        echo json_encode([
            'success' => false,
            'message' => $exception->getCode() === 1451
                ? 'No se puede eliminar una acción que ya está asociada al historial de documentos.'
                : 'No se pudo eliminar la acción de documento.'
        ]);
    }

?>
