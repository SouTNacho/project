<?php

    session_start();
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";
    
    $stateId = (int) $_POST['state_id'];
    $employeeId = (int) $_POST['employee_id'];

    if (validateEmptyData($stateId) || validateEmptyData($employeeId)) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del empleado: Datos incompletos.']
        );
        exit;
    }

    $mysqli = connection_db();
    try {

        $employee = findEmployeeWithId($mysqli, $employeeId);
        if (!$employee) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado del empleado: Empleado no encontrado.']
            );
            exit;
        }

        changeStateEmployee($mysqli, $employeeId, $stateId);
        $employee = findEmployeeWithId($mysqli, $employeeId);

        if ((int) $employee['id_estado_funcionario'] !== $stateId) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado del empleado: No se pudo actualizar el estado.']
            );
            exit;
        }

        echo json_encode(
            ['success' => true,
            'message' => 'Estado del empleado actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado del empleado: ' . $e->getMessage()]
        );
    }

?>
