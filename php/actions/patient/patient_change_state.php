<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../models/patient_model.php";
    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../conection.php";
    
    $state_id = (int) $_POST['state_id'];
    $patient_id = (int) $_POST['patient_id'];

    if (validateEmptyData($state_id) || validateEmptyData($patient_id)) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del paciente: Datos incompletos.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $patient = findPatientWithId($mysqli, $patient_id);

        if (!$patient) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado del paciente: Empleado no encontrado.']
            );
            exit;
        }

        changeStatePatient($mysqli, $patient_id, $state_id);
        $patient = findPatientWithId($mysqli, $patient_id);

        if ((int) $patient['id_estado_paciente'] !== $state_id) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado del paciente: No se pudo actualizar el estado.']
            );
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Estado del paciente actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del paciente: ' . $e->getMessage()]
        );
    }

?>
