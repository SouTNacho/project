<?php

    session_start();

    require_once __DIR__ . "/../../functions/employee_functions.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/login.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $employee_id = trim($_POST["employee_id"] ?? "");
        $password = trim($_POST["employee_password"] ?? "");

        if (validateEmptyData($employee_id) || validateEmptyData($password)) {
            redirectionForError("Todos los campos son obligatorios");
        }

        if (!validateEmployeeCode($employee_id)) {
            redirectionForError("El código de funcionario no es válido");
        }

        $user_type = getEmployeeType($employee_id);
        $mysqli = connection_db();

        $employee = findEmployeeWithCode($user_type, $mysqli, $employee_id);
        $mysqli->close();

        if (!$employee) {
            redirectionForError("El usuario ingresado no existe");
        }

        if (authenticateEmployee($password, $employee["pass"])) {

            if ($employee["id_estado_funcionario"] !== 1) {
                redirectionForError("El funcionario esta bloqueado, contacte con el admin");
            }

            $_SESSION["username"] = $employee["nombre"];

            switch ($user_type) {

                case "FA":
                    
                    $_SESSION["user_type"] = "admin";
                    header("Location: /php/pages/administrative_panel.php");
                    exit();
                case "CO":

                    $_SESSION["user_type"] = "copilot";
                    header("Location: /php/pages/administrative_panel.php");
                    exit();
                case "DR":

                    $_SESSION["user_type"] = "driver";
                    header("Location: /php/pages/administrative_panel.php");
                    exit();
                case "SU":

                    $_SESSION["user_type"] = "superuser";
                    header("Location: /php/pages/super_user_panel.php");
                    exit();

                default:

                    redirectionForError("Tipo de usuario inválido");
            }
        } else {
            redirectionForError("La constraseña es incorrecta");
        }
    }
    
?>