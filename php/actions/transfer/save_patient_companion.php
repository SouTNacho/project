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

        $patient = (int) ($_POST['transfer_patient'] ?? 0);
        $companion = (int) ($_POST['transfer_companion'] ?? 0);

        if ($patient <= 0 || $companion <= 0) {

            redirectionForError("Los datos recibidos no son válidos.");
        }

        

        $mysqli = connection_db();

        try {

            savePatientCompanion($mysqli, $patient, $companion);

            $mysqli->close();

            echo json_encode(
                ['success' => true,
                'message' => 'El acompañante ha sido registrado con exito.']
            );
            exit();
            
        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al registrar: " . $e->getMessage());
            $mysqli->close();
            
            echo json_encode(
                ['success' => false,
                'message' => 'Ha ocurrido un error al registrar el acompañante.' . $e->getMessage()]
            );
            exit();
        }

    }
    
?>