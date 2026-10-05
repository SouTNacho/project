<?php

    session_start();
    // Falta implementar el rol
    header("Content-Type: application/json");

    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../conection.php";
    
    $state_id = (int) ($_POST['state_id']?? 0);
    $employee_id = (int) ($_POST['employee_id']?? 0);

    if ($state_id <= 0 || $employee_id <= 0) {
        
        echo json_encode(
                ['success' => false,
                'message' => 'Error, los datos recibidos no son válidos.']
            );
        exit;
    }

    $mysqli = connection_db();

    try {

        $employee = findEmployeeWithId($mysqli, $employee_id);

        if (!$employee) {
            echo json_encode(
                ['success' => false,
                'message' => 'Error al cambiar el estado: empleado no encontrado.']
            );
            $mysqli->close();
            exit;
        }

        if ((int)$employee['id_estado_funcionario'] === 3 || (int)$employee['id_estado_funcionario'] === 8) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede modificar un funcionario eliminado o jubilado.'
            ]);
            $mysqli->close();
            exit;
        }

        changeStateEmployee($mysqli, $employee_id, $state_id);
        $mysqli->close();

        echo json_encode(
            ['success' => true,
            'message' => 'Estado actualizado correctamente.']
        );

    } catch (mysqli_sql_exception $e) {
        $mysqli->close();
        
        // DESPUES QUITAR EL MENSAJE
        echo json_encode(
            ['success' => false,
            'message' => 'Error al cambiar el estado: ' . $e->getMessage()]
        );
        exit;
    }

?>
