<?php

    session_start();

    require_once __DIR__ . "/../functions/employee_functions.php";
    require_once __DIR__ . "/../models/employee_model.php";
    require_once __DIR__ . "/../functions/validations.php";
    require_once __DIR__ . "/../conection.php";

    function redirectionForError($message) {
        $_SESSION["errors"] = $message;
        header("Location: /php/update_employee.php");
        exit();
    }

    function redirectWithError($mysqli, $message) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/manage_cellphone.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $employee_id = trim($_POST['employee_id'] ?? '');
        $action = trim($_POST['employee_cellphone_action'] ?? '');

        $new_code = "";
        $new_number = "";

        $new_fullphone = "";

        if (validateEmptyData($employee_id) || validateEmptyData($action)) {
                redirectionForError("Todos los campos son obligatorios");
        }

        if (!validateEmployeeCode($employee_id)) {
            redirectionForError("El código de funcionario no es válido");
        }

        if (!in_array($action, ["cr", "rm", "up"])) {
            redirectionForError("La acción seleccionada no es válida");
        }

        $cellphone_code = trim($_POST['cellphone_code'] ?? '');
        $cellphone_number = trim($_POST['cellphone_number'] ?? '');

        if ($action === "up") {
            $new_code = trim($_POST['other_cellphone_code'] ?? '');
            $new_number = trim($_POST['other_cellphone_number'] ?? '');
        }

        $mysqli = connection_db();
        $employee_type = getEmployeeType($employee_id);
        $employee = findEmployeeWithCode($employee_type, $mysqli, $employee_id);

        if (!$employee) {
            redirectWithError($mysqli, "El funcionario ingresado no existe");
        }

        if (validateEmptyData($cellphone_code) || validateEmptyData($cellphone_number)) {
            redirectWithError($mysqli, "Todos los campos son obligatorios");
        }

        $fullphone = $cellphone_code . $cellphone_number;

        if (!validatePhone($fullphone)) {
            redirectWithError($mysqli, "El Teléfono ingresado no es válido");
        }

        if ($action === "up") {

            if (validateEmptyData($new_code) || validateEmptyData($new_number)) {
                redirectWithError($mysqli, "Todos los campos son obligatorios");
            }

            $new_fullphone = $new_code . $new_number;

            if (!validatePhone($new_fullphone)) {
                redirectWithError($mysqli, "El Teléfono ingresado no es válido");
            }
        }

        $cellphone = findEmployeeCellphone($mysqli, $employee["id_funcionario"], $fullphone);

        try {

            switch($action) {

                case "rm":
                    if ($cellphone) {

                        deleteCellphone($mysqli, $cellphone["id_telefono"]);
                    }
                    else {
                        redirectWithError($mysqli, "El Teléfono ingresado no existe para este funcionario");
                    }
                    break;

                case "cr":
                    if (!$cellphone) {

                        insertCellphone($mysqli, $fullphone, $employee["id_funcionario"]);
                    }
                    else {
                        redirectWithError($mysqli, "El Teléfono ingresado ya existe para este funcionario");
                    }
                    break;

                case "up":
                    if ($cellphone) {

                        $new_cellphone = findEmployeeCellphone($mysqli, $employee["id_funcionario"], $new_fullphone);

                        if (!$new_cellphone) {
                            updateCellphone($mysqli, $new_fullphone, $cellphone["id_telefono"]);

                        } else {
                            redirectWithError($mysqli, "El Teléfono nuevo ya existe para este funcionario");
                        }

                    } else {
                        redirectWithError($mysqli, "El Teléfono que desea actualizar no existe para este funcionario");
                    }
                    break;
            }

            $mysqli->close();
            $_SESSION["success"] = "Teléfono gestionado correctamente";
            header("Location: /php/manage_cellphone.php");
            exit();

        } catch (mysqli_sql_exception $e) {

            error_log( $e->getMessage());
            redirectWithError($mysqli, "Ocurrió un error al gestionar el Teléfono");
        }

    }

?>