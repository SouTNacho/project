<?php

    session_start();
    
    require_once __DIR__ . "/../../functions/employee_functions.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/update_employee.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/employee/update_employee.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $employee_id = trim($_POST['employee_id'] ?? '');
        $first_name = trim($_POST['employee_first_name'] ?? '');
        $last_name = trim($_POST['employee_last_name'] ?? '');
        $nationality = trim($_POST['employee_nationality'] ?? '');
        $birthdate = trim($_POST['employee_birthdate'] ?? '');
        $department = trim($_POST['employee_department'] ?? '');
        $locality = trim($_POST['employee_locality'] ?? '');
        $address = trim($_POST['employee_address'] ?? '');
        $door_number = trim($_POST['employee_address_number'] ?? '');
        $email = trim($_POST['employee_email'] ?? '');
        $position = trim($_POST['employee_position'] ?? '');
        $entry_date = trim($_POST['employee_entry_date'] ?? '');
        $permissions = "";
        $speciality = "";
        $license_expiration = "";
        $license_category = "";

        if ($locality === "Otra localidad") {
            $locality = trim($_POST['other_locality'] ?? '');
        }

        if (validateEmptyData($employee_id)) {
            redirectionForError("El codigo de funcionario es obligatorios");
        }

        if (!validateEmptyData($first_name)) {
            if (!validateName($first_name)) {
                redirectionForError("El nombre ingresado no es válido");
            }
        }

        if (!validateEmptyData($last_name)) {
            if (!validateName($last_name)) {
                redirectionForError("El apellido ingresado no es válido");
            }
        }

        if (!validateEmptyData($locality)) {
            if (!validateString($locality)) {
                redirectionForError("La localidad ingresada no es válida");
            }
        }

        if (!validateEmptyData($address)) {
            if (!validateString($address)) {
                redirectionForError("La dirección ingresada no es válida");
            }
        }

        if (!validateEmptyData($birthdate)) {
            if (!validateDate($birthdate)) {
                redirectionForError("La fecha de nacimiento ingresada no es válido");
            }
        }

        if (!validateEmptyData($door_number)) {
            if (!validateDoorNumber($door_number)) {
                redirectionForError("El número de puerta ingresado no es válido");
            }
        }

        if (!validateEmployeeCode($employee_id)) {
            redirectionForError("El código de funcionario no es válido");
        }

        if (!validateEmptyData($email)) {
            if (!validateEmail($email)) {
                redirectionForError("El email no es válido");
            }
        }

        $employee_type = getEmployeeType($employee_id);

        if ($employee_type === "SU") {
            redirectionForError("El código de empleado no es válido");
        }

        $mysqli = connection_db();
        $employee = findEmployeeWithCode($employee_type, $mysqli, $employee_id);

        if(!$employee) {
            redirectWithError($mysqli, "El funcionario no existe, debe registrarlo");
        }

        if (!validateEmptyData($email)) {

            $employee_email = findEmployeeWithEmail($mysqli, $email);
            if ($employee_email && $employee_email["id_funcionario"] !== $employee["id_funcionario"]) {
                redirectWithError($mysqli, "El email ya está registrado para otro funcionario");            
            }
        }

        switch($position) {
            
            case "FA":

                $permissions = trim($_POST['employee_permissions'] ?? '');

                if (validateEmptyData($permissions)) {
                    redirectionForError("Los datos adicionales para el cargo son obligatorios");
                }
                break;
            case "DR":

                $license_expiration = trim($_POST['employee_license_expiration'] ?? '');
                $license_category = trim($_POST['employee_license_category'] ?? '');

                if (validateEmptyData($license_expiration) || validateEmptyData($license_category)) {
                    redirectionForError("Los datos adicionales para el cargo son obligatorios");
                }
                break;
            case "CO":

                $speciality = trim($_POST['employee_speciality'] ?? '');

                if (validateEmptyData($speciality)) {
                    redirectionForError("Los datos adicionales para el cargo son obligatorios");
                }
                break;
            default:

                $position = "";
                break;
        }

        $position = keepOldValue($position, $employee["cargo"]);

        try {

            $mysqli->begin_transaction();

            if ($position !== $employee["cargo"]) {

                deleteRole($employee["cargo"], $employee["id_funcionario"], $mysqli);
                $employee_id = $position . $employee["cedula"];
                insertRole($position, $employee["id_funcionario"], $mysqli, $permissions,
                    $speciality, $license_expiration, $license_category, $employee_id);
                
            } else {

                switch ($position) {

                    case "FA":

                        $specific_data = findAdministrative($mysqli, $employee_id);

                    if (!$specific_data) {
                        redirectWithError($mysqli, "Ocurrió un registro al actualizar el administrativo");
                    }

                    $permissions = keepOldValue($permissions, $specific_data["permisos"]);
                    break;
                case "DR":

                    $specific_data = findDriver($mysqli, $employee_id);

                    if (!$specific_data) {
                        redirectWithError($mysqli, "Ocurrió un registro al actualizar el conductor");
                    }

                    $license_expiration = keepOldValue($license_expiration, $specific_data["vencimiento_carnet"]);
                    $license_category = keepOldValue($license_category, $specific_data["categoria_carnet"]);
                    break;
                case "CO":

                    $specific_data = findCopilot($mysqli, $employee_id);

                    if (!$specific_data) {
                        redirectWithError($mysqli, "Ocurrió un registro al actualizar el copiloto");
                    }

                    $speciality = keepOldValue($speciality, $specific_data["especialidad"]);
                    break;
                }
                
                updateRole($employee_type, $employee["id_funcionario"], $mysqli, $permissions,
                    $speciality, $license_expiration, $license_category);
            }

            $first_name = keepOldValue($first_name, $employee["nombre"]);
            $last_name = keepOldValue($last_name, $employee["apellido"]);
            $nationality = keepOldValue($nationality, $employee["nacionalidad"]);
            $birthdate = keepOldValue($birthdate, $employee["fecha_nacimiento"]);
            $department = keepOldValue($department, $employee["departamento"]);
            $locality = keepOldValue($locality, $employee["localidad"]);
            $address = keepOldValue($address, $employee["direccion"]);
            $door_number = keepOldValue($door_number, $employee["numero_puerta"]);
            $email = keepOldValue($email, $employee["email"]);
            $entry_date = keepOldValue($entry_date, $employee["fecha_ingreso"]);

            updateEmployee($mysqli, $first_name, $last_name, $nationality, $birthdate, $department,
                            $locality, $address, $door_number, $email, $position, $entry_date, $employee["id_funcionario"]);

            $mysqli->commit();
            $mysqli->close();
            $_SESSION["success"] = "Funcionario actualizado correctamente";
            header("Location: /php/pages/employee/update_employee.php");
            exit();
        
        } catch (mysqli_sql_exception $e) {

            $mysqli->rollback();
            $mysqli->close();

            error_log( $e->getMessage());
            $_SESSION["errors"] = "Ocurrió un error al actualizar el funcionario.";
            header("Location: /php/pages/employee/update_employee.php");
            exit();
        }

    }
    
?>