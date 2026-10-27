<?php

    session_start();
    require_once __DIR__ . '/../../functions/session.php';
    validateActionSession('administrative');
    
    require_once __DIR__ . "/../../models/transfer_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        echo json_encode(
            ['success' => false,
            'message' => $message]
        );
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        echo json_encode(
            ['success' => false,
            'message' => $message]
        );
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_POST['transfer_id'] ?? 0);
        $description = trim($_POST['transfer_description'] ?? '');

        if ($id <= 0 || validateEmptyData($description)) {
            redirectionForError("Los datos enviados no son válidos.");
        }

        if (!validateLargeString($description)) {
            redirectionForError("La descripción ingresada no es válida.");
        }

        $mysqli = connection_db();

        try {

            saveHistorial($mysqli, $id, $description);
            $mysqli->close();

            echo json_encode(
                ['success' => true,
                'message' => 'Los historia ha sido registrada con exito.']
            );
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            echo json_encode(
                ['success' => false,
                'message' => 'Ha ocurrido un error al registrar la historia.']
            );
            exit();
        }

    }
    
?>