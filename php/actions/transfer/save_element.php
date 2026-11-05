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
        $elements = ($_POST['transfer_elements'] ?? []);

        if ($id <= 0) {
            
            redirectionForError("Los datos enviados no son válidos.");
        }

        foreach ($elements as $element) {

            if ($element <= 0) redirectionForError("Los elementos seleccionados no son válidos.");
        }

        $mysqli = connection_db();

        try {

            $mysqli->begin_transaction();

            foreach ($elements as $element) {

                saveElement($mysqli, $id, $element);
            }

            $mysqli->commit();
            $mysqli->close();

            echo json_encode(
                ['success' => true,
                'message' => 'Los elementos han sido registrados con exito.']
            );
            exit();
            
        } catch (mysqli_sql_exception $e) {

            $mysqli->rollback();

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            echo json_encode(
                ['success' => false,
                'message' => 'Ha ocurrido un error al registrar los elementos.']
            );
            exit();
        }

    }
    
?>