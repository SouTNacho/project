<?php

    session_start();

    require_once __DIR__ . "/../functions/session.php";
    redirectLoggedUser();

    require_once __DIR__ . "/../functions/employee_functions.php";
    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/login.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/login.php");
        exit();
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $employee_id = trim($_POST["employee_id"] ?? '');
        $password = $_POST["employee_password"] ?? '';

        if (validateEmptyData($employee_id) || validateEmptyData($password)) {
            redirectionForError("Todos los campos son obligatorios.");
        }

        if (!validateEmployeeCode($employee_id)) {
            redirectionForError("El código de funcionario no es válido.");
        }

        $user_type = getEmployeeType($employee_id);
        $mysqli = connection_db();

        try {

            $employee = null;

            switch ($user_type) {
                case 'FA':
                case 'SU':
                case 'DR':
                case 'CO':

                    $employee = findEmployeeWithCode($user_type, $mysqli, $employee_id);
                    break;
                
                default:
                    redirectWithError($mysqli, "Tipo de usuario inválido.");
                    break;
            }

            if (!$employee) {
                redirectWithError($mysqli, "El usuario ingresado no existe.");
            }

            if ((int) $employee["id_estado_funcionario"] !== 1) {
                redirectWithError($mysqli, "El usuario está bloqueado. Contacte con el administrador.");
            }

            if (!authenticateEmployee($password, $employee["pass"])) {
                redirectWithError($mysqli, "La contraseña es incorrecta.");
            }

            session_regenerate_id(true);
            $_SESSION = [];
            $roles = ['FA' => 'administrative', 'SU' => 'super_user', 'DR' => 'driver', 'CO' => 'copilot'];
            $_SESSION['logged'] = true;
            $_SESSION['username'] = $employee['nombre'];
            $_SESSION['employee_code'] = $employee['codigo'];
            $_SESSION['codigo'] = $employee['codigo'];
            $_SESSION['employee_id'] = $employee['id_funcionario'];
            $_SESSION['type_user'] = $roles[$user_type];
            $_SESSION['user_type'] = $roles[$user_type];
            $mysqli->close();
            redirectLoggedUser();

        } catch (mysqli_sql_exception $e) {

                // DESPUES QUITAR EL MENSAJE
                error_log("Error al iniciar sesión:" . $e->getMessage());
                $mysqli->close();

                $_SESSION["errors"] = 'Ha ocurrido un error al iniciar sesión.';
                header("Location: /php/pages/login.php");
                exit();
        }
    }

?>