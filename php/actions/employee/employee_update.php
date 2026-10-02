
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
        $document = trim($_POST['employee_document'] ?? '');
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
        $license_expiration_date = "";
        $license_category = "";

        if ($locality === "Otra localidad") {
            $locality = trim($_POST['other_locality'] ?? '');
        }

        if (validateEmptyData($employee_id)) {
            redirectionForError("El codigo de funcionario es obligatorio");
        }

        if (!validateEmployeeCode($employee_id)) {
            redirectionForError("El cÃ³digo de funcionario no es vÃ¡lido");
        }

        if (!validateEmptyData($first_name)) {
            if (!validateName($first_name)) {
                redirectionForError("El nombre ingresado no es vÃ¡lido");
            }
        }

        if (!validateEmptyData($last_name)) {
            if (!validateName($last_name)) {
                redirectionForError("El apellido ingresado no es vÃ¡lido");
            }
        }

        if (!validateEmptyData($document)) {
            if (!validateDocument($document)) {
                redirectionForError("El documento ingresado no es vÃ¡lido");
            }
        }

        if (!validateEmptyData($locality)) {
            if (!validateString($locality)) {
                redirectionForError("La localidad ingresada no es vÃ¡lida");
            }
        }

        if (!validateEmptyData($address)) {
            if (!validateString($address)) {
                redirectionForError("La direcciÃ³n ingresada no es vÃ¡lida");
            }
        }

        if (!validateEmptyData($birthdate)) {
            if (!validateDate($birthdate)) {
                redirectionForError("La fecha de nacimiento ingresada no es vÃ¡lida");
            }
        }

        if (!validateEmptyData($door_number)) {
            if (!validateDoorNumber($door_number)) {
                redirectionForError("El nÃºmero de puerta ingresado no es vÃ¡lido");
            }
        }

        if (!validateEmptyData($email)) {
            if (!validateEmail($email)) {
                redirectionForError("El correo electrÃ³nico ingresado no es vÃ¡lido");
            }
        }

        $employee_type = getEmployeeType($employee_id);

        $mysqli = connection_db();
        $employee = findEmployeeWithCode($employee_type, $mysqli, $employee_id);

        if(!$employee) {
            redirectWithError($mysqli, "El funcionario ingresado no existe, debe registrarlo");
        }

        $employee_active =  '';
        $employee_type = getEmployeeType($employee_id);

        switch ($employee_type) {
            case "FA":

                $employee_active = findAdministrative($mysqli, $employee_id);
                break;
            case "CO":

                $employee_active = findCopilot($mysqli, $employee_id);
                break;
            case "DR":

                $employee_active = findDriver($mysqli, $employee_id);
                break;
        }

        // this validation not is used
        if (!$employee_active) {
            redirectWithError($mysqli, "No se encontrÃ³ esta especializaciÃ³n para el funcionario");
        }

        if ((int) $employee_active["id_estado_especializacion"] === 2) {
            redirectWithError($mysqli, "Esta especializaciÃ³n estÃ¡ desactivada para el funcionario, debe actualizar la especializaciÃ³n activa");
        }
        

        if (!validateEmptyData($email)) {

            $employee_email = findEmployeeWithEmail($mysqli, $email);

            if ($employee_email && $employee_email["id_funcionario"] !== $employee["id_funcionario"]) {
                redirectWithError($mysqli, "El email ya estÃ¡ registrado para otro funcionario");            
            }

        }

        if (!validateEmptyData($document)) {

            $employee_document = findEmployeeWithDocument($mysqli, $document);

            if ($employee_document && $employee_document["id_funcionario"] !== $employee["id_funcionario"]) {
                redirectWithError($mysqli, "El documento ya estÃ¡ registrado para otro funcionario");
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

                $license_expiration_date = trim($_POST['employee_license_expiration'] ?? '');
                $license_category = trim($_POST['employee_license_category'] ?? '');

                if (validateEmptyData($license_expiration_date) || validateEmptyData($license_category)) {
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

                desactivateRole($employee["cargo"], $employee["id_funcionario"], $mysqli);
                $employee_code = $position . str_pad($employee["id_funcionario"], 8, "0", STR_PAD_LEFT);

                $specialization = findEmployeeWithCode($position, $mysqli, $employee_code);

                if ($specialization) {

                    switch ($position) {

                        case "FA":

                            activateAdministrative($employee["id_funcionario"], $mysqli);
                            break;
                        case "DR":

                            activateDriver($employee["id_funcionario"], $mysqli);
                            break;
                        case "CO":

                            activateCopilot($employee["id_funcionario"], $mysqli);
                            break;
                    }

                    updateRole($position, $employee["id_funcionario"], $mysqli, $permissions,
                        $speciality, $license_expiration_date, $license_category);

                } else {

                    insertRole($position, $employee["id_funcionario"], $mysqli, $permissions,
                        $speciality, $license_expiration_date, $license_category, $employee_code);
                }

            } else {

                switch ($position) {

                    case "FA":

                        $role = findAdministrative($mysqli, $employee_id);

                        if (!$role) {
                            redirectWithError($mysqli, "OcurriÃ³ un error al actualizar el administrativo");
                        }

                        $permissions = keepOldValue($permissions, $role["permisos"]);
                        break;
                    case "DR":

                        $role = findDriver($mysqli, $employee_id);

                        if (!$role) {
                            redirectWithError($mysqli, "OcurriÃ³ un error al actualizar el conductor");
                        }

                        $license_expiration_date = keepOldValue($license_expiration_date, $role["vencimiento_carnet"]);
                        $license_category = keepOldValue($license_category, $role["categoria_carnet"]);
                        break;
                    case "CO":

                        $role = findCopilot($mysqli, $employee_id);

                        if (!$role) {
                            redirectWithError($mysqli, "OcurriÃ³ un error al actualizar el copiloto");
                        }

                        $speciality = keepOldValue($speciality, $role["especialidad"]);
                        break;
                }
                
                updateRole($employee["cargo"], $employee["id_funcionario"], $mysqli, $permissions,
                    $speciality, $license_expiration_date, $license_category);
            }

            $first_name = keepOldValue($first_name, $employee["nombre"]);
            $last_name = keepOldValue($last_name, $employee["apellido"]);
            $document = keepOldValue($document, $employee["cedula"]);
            $nationality = keepOldValue($nationality, $employee["nacionalidad"]);
            $birthdate = keepOldValue($birthdate, $employee["fecha_nacimiento"]);
            $department = keepOldValue($department, $employee["departamento"]);
            $locality = keepOldValue($locality, $employee["localidad"]);
            $address = keepOldValue($address, $employee["direccion"]);
            $door_number = keepOldValue($door_number, $employee["numero_puerta"]);
            $email = keepOldValue($email, $employee["email"]);
            $entry_date = keepOldValue($entry_date, $employee["fecha_ingreso"]);

            updateEmployee($mysqli, $first_name, $last_name, $document, $nationality, $birthdate, $department,
                            $locality, $address, $door_number, $email, $position, $entry_date, $employee["id_funcionario"]);

            $mysqli->commit();
            $mysqli->close();
            $_SESSION["success"] = "Funcionario actualizado correctamente";
            header("Location: /php/pages/employee/update_employee.php");
            exit();
        
        } catch (mysqli_sql_exception $e) {

            $mysqli->rollback();
            $mysqli->close();

            $_SESSION["errors"] = "OcurriÃ³ un error al actualizar el funcionario." . $e->getMessage();
            header("Location: /php/pages/employee/update_employee.php");
            exit();
        }

    }
    
?>
