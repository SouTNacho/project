<?php

    session_start();
    // Falta validar rol

    require_once __DIR__ . "/../../models/cellphone_model.php";
    require_once __DIR__ . "/../../models/employee_model.php";
    require_once __DIR__ . "/../../functions/validations.php";
    require_once __DIR__ . "/../../conection.php";

    function redirectionForError($message, $id) {
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/cellphone/cellphone_form.php?id=" . $id);
        exit();
    }

    function redirectWithError($mysqli, $message, $id) {
        $mysqli->close();
        $_SESSION["errors"] = $message;
        header("Location: /php/pages/cellphone/cellphone_form.php?id=" . $id);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = (int) ($_GET['id'] ?? 0);
        $code = trim($_POST['cellphone_code'] ?? '');
        $number = trim($_POST['cellphone_number'] ?? '');

        if ($id <= 0) {
            redirectionForError("El ID no es válido.", $id);
        }

        $mysqli = connection_db();

        try {
            
            $cellphone = findCellphoneWithId($mysqli, $id);

            if(!$cellphone) {
                redirectWithError($mysqli, 'El teléfono no existe', $id);
            }

            $cellphone_number = $code . $number;

            if (!validatePhone($cellphone_number)) {
                redirectionForError("El teléfono no es válido.", $id);
            }

            if ($cellphone_number !== $cellphone['telefono']) {

                $cellphone_number_check = findCellphoneWithEmployeeAndNumber($mysqli, $cellphone['id_funcionario'], $cellphone_number);

                if ($cellphone_number_check) {
                    redirectWithError($mysqli, "Este teléfono ya está registrado, para este empleado.", $id);
                }
            }

            updateCellphone($mysqli, $cellphone_number, $id);
            $mysqli->close();

            $_SESSION["success"] = "Actualización exitosa.";
            header("Location: /php/pages/cellphone/cellphone_form.php?id=" . $id);
            exit();

        } catch (mysqli_sql_exception $e) {

            // DESPUES QUITAR EL MENSAJE
            error_log("Error al actualizar teléfono: " . $e->getMessage());
            $mysqli->close();

            $_SESSION["errors"] ="Ha ocurrido un error al actualizar.";
            header("Location: /php/pages/cellphone/cellphone_form.php?id=" . $id);
            exit();
        }
    }

?>