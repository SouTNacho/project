<?php
    
    session_start();
    header("Content-Type: application/json");
    
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    $employee_id = (int) $_POST['employee_id'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!validatePassword($password)) {

        echo json_encode(
            ['success' => false,
            'message' => 'La contraseña ingresada no es valida.']
        );
        exit;
    }

    if ($password !== $confirm_password) {

        echo json_encode(
            ['success' => false,
            'message' => 'Las contraseñas no coinciden.']
        );
        exit;
    }

    $mysqli = connection_db();

    try {

        $employee = findEmployeeWithId($mysqli, $employee_id);

        if (!$employee) {

            echo json_encode(
                ['success' => false,
                'message' => 'El funcionario no fue encontrado.']
            );
            exit;
        }

        $password = password_hash($password, PASSWORD_BCRYPT);

        changePasswordEmployee($mysqli, $employee_id, $password);
        
        echo json_encode(
            ['success' => true,
            'message' => 'Contraseña cambiada correctamente.']
        );

    } catch (mysqli_sql_exception $e) {

        echo json_encode(
            ['success' => false,
            'message' => 'Error al buscar el funcionario']
        );
        exit;
    }

?>